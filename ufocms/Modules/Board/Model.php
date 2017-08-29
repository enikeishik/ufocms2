<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\Board;

/**
 * Main module model
 */
class Model extends \Ufocms\Modules\Model //implements IModel
{
    protected $messageMarks = array('{PATH}', '{DT}', '{IP}', '{TITLE}', '{MESSAGE}', '{CONTACTS}');
    
    public function getSettings()
    {
        if (null !== $this->settings) {
            return $this->settings;
        }
        $sql =  'SELECT *' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'board_sections' . 
                ' WHERE SectionId=' . $this->params->sectionId;
        $this->settings = $this->db->getItem($sql);
        $this->params->pageSize = $this->settings['PageLength'];
        return $this->settings;
    }
    
    public function getItems()
    {
        if (null !== $this->items) {
            return $this->items;
        }
        $sqlBase =  ' FROM ' . C_DB_TABLE_PREFIX . 'board' . 
                    ' WHERE SectionId=' . $this->params->sectionId . 
                    ' AND IsHidden=0';
        $sqlOrder = 'DateCreate DESC';
        $sql =  'SELECT *' . 
                $sqlBase . 
                ' ORDER BY ' . $sqlOrder . 
                ' LIMIT ' . (($this->params->page - 1) * $this->params->pageSize) . 
                        ', ' . $this->params->pageSize;
        $this->itemsCount = $this->db->getValue('SELECT COUNT(*) AS Cnt' . $sqlBase, 'Cnt');
        if (0 < $this->itemsCount) {
            $this->items = $this->db->getItems($sql);
        } else {
            $this->items = array();
        }
        return $this->items;
    }
    
    public function getItem()
    {
        if (null !== $this->item) {
            return $this->item;
        }
        $sql =  'SELECT *' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'board' . 
                ' WHERE Id=' . $this->params->itemId . 
                ' AND IsHidden=0';
        $this->item = $this->db->getItem($sql);
        return $this->item;
    }
    
    public function add()
    {
        $this->actionResult = array(
            'referer' => false, 
            'correct' => false, 
            'human'   => false, 
            'db'      => false, 
            'email'   => false, 
        );
        $this->getSettings();
        
        //проверяем корректность источника
        if ($this->settings['IsReferer']) {
            if (!isset($_SERVER['HTTP_REFERER'])) {
                return;
            }
            if (false === strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST'])) {
                return;
            }
        }
        $this->actionResult['referer'] = true;
        
        //проверяем наличие входных данных
        //не проверяем наличие флажков, 
        //поскольку если они не отмечены, то не передаются
        if (   !isset($_POST['title']) 
            || !isset($_POST['message']) 
            || !isset($_POST['contacts'])) {
            return;
        }
        $this->actionResult['correct'] = true;
        
        //проверяем что отправил форму человек
        if ($this->settings['IsCaptcha']) {
            if (!$this->tools->getCaptcha()->check()) {
                return;
            }
        }
        $this->actionResult['human'] = true;
        
        //получаем очищенные переменные
        $messageMaxLength = (int) $this->settings['MessageMaxLen'];
        if (0 < $messageMaxLength) {
            $message = substr($_POST['message'], 0, $messageMaxLength);
            $contacts = substr($_POST['contacts'], 0, $messageMaxLength);
        } else {
            $message = $_POST['message'];
            $contacts = $_POST['contacts'];
        }
        
        $item = array(
            $this->params->sectionPath, 
            date('Y.m.d H:i:s'), 
            substr($_SERVER['REMOTE_ADDR'], 0, 15), 
            strip_tags($_POST['title']), 
            nl2br(strip_tags($message), false), 
            nl2br(strip_tags($contacts), false), 
        );
        
        //добавляем сообщение в БД
        $sql =  'INSERT INTO ' . C_DB_TABLE_PREFIX . 'board' . 
                ' (SectionId,DateCreate,IsHidden,IP,Title,Message,Contacts)' . 
                ' VALUES(' . $this->params->sectionId . ',NOW(),' . 
                (int) $this->settings['IsModerated'] . ',' . 
                "'" . $this->db->addEscape($item[2]) . "'," . 
                "'" . $this->db->addEscape($item[3]) . "'," . 
                "'" . $this->db->addEscape($item[4]) . "'," . 
                "'" . $this->db->addEscape($item[5]) . "')";
        $this->actionResult['db'] = $this->db->execQuery($sql);
        
        //отправляем уведомление на email, если задан
        if ('' != $this->settings['AlertEmail']) {
            $this->actionResult['email'] = $this->tools->getMessenger($this->config)->sendEmail(
                $this->settings['AlertEmail'], 
                str_replace($this->messageMarks, $item, $this->settings['AlertEmailSubj']), 
                str_replace($this->messageMarks, $item, $this->settings['AlertEmailBody'])
            );
        }
    }
}
