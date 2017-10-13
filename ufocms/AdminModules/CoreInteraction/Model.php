<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\CoreInteraction;

/**
 * Core interaction model class
 */
class Model extends \Ufocms\AdminModules\Model
{
    protected function init()
    {
        parent::init();
        $this->itemsTable = 'interaction_comments';
        $this->itemIdField = 'Id';
        $this->itemDisabledField = 'IsDisabled';
        $this->primaryFilter = '';
        $this->defaultSort = 'ic.DateCreate DESC';
        $this->canCreateItems = false;
        $this->config->registerAction('blacklist');
        $this->config->registerMakeAction('blacklist');
        $this->params->actionUnsafe = false;
    }
    
    protected function setItems()
    {
        $sql = $this->getItemsSqlBase(
            'ic', 
            ' INNER JOIN ' . C_DB_TABLE_PREFIX . 'sections AS s ON ic.SectionId=s.id'
        );
        $sql .= $this->getItemsSqlCondition('ic');
        $this->itemsCount = $this->db->getValue('SELECT COUNT(*) AS Cnt' . $sql, 'Cnt');
        if (0 == $this->itemsCount) {
            $this->items = array();
            return;
        }
        
        $sql =  'SELECT ic.*, ic.Id AS itemid, ic.IsDisabled AS disabled, s.path, s.indic, u.Title AS UserTitle' . 
                $sql . ' LEFT JOIN ' . C_DB_TABLE_PREFIX . 'users AS u ON ic.UserId=u.Id' . 
                $this->getItemsSqlSorting('ic') . 
                $this->getItemsSqlLimits();
        $this->items = $this->db->getItems($sql);
        if (null === $this->items) {
            $this->items = array();
        }
    }
    
    protected function setFields()
    {
        $this->fields = array(
            array('Type' => 'int',          'Name' => 'Id',             'Value' => 0,       'Title' => 'id',            'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => false),
            array('Type' => 'int',          'Name' => 'UserId',         'Value' => 0,       'Title' => 'UserId',        'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => false),
            array('Type' => 'int',          'Name' => 'SectionId',      'Value' => 0,       'Title' => 'SectionId',     'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => false),
            array('Type' => 'int',          'Name' => 'ItemId',         'Value' => 0,       'Title' => 'ItemId',        'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => false),
            array('Type' => 'int',          'Name' => 'TopId',          'Value' => 0,       'Title' => 'TopId',         'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => false),
            array('Type' => 'int',          'Name' => 'ParentId',       'Value' => 0,       'Title' => 'ParentId',      'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => false),
            array('Type' => 'int',          'Name' => 'OrderId',        'Value' => 0,       'Title' => 'OrderId',       'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => false),
            array('Type' => 'int',          'Name' => 'LevelId',        'Value' => 0,       'Title' => 'LevelId',       'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => false),
            array('Type' => 'text',         'Name' => 'Mask',           'Value' => '',      'Title' => 'Mask',          'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => false),
            array('Type' => 'datetime',     'Name' => 'DateCreate',     'Value' => '',      'Title' => 'Дата/время',    'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => false),
            array('Type' => 'text',         'Name' => 'IP',             'Value' => '',      'Title' => 'IP',            'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => false),
            array('Type' => 'bigtext',      'Name' => 'Info',           'Value' => '',      'Title' => 'Информация',    'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => false,    'Raw' => true),
            array('Type' => 'bigtext',      'Name' => 'CommentText',    'Value' => '',      'Title' => 'Комментарий',   'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'text',         'Name' => 'CommentAuthor',  'Value' => '',      'Title' => 'Подпись',       'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'text',         'Name' => 'CommentEmail',   'Value' => '',      'Title' => 'Email',         'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'text',         'Name' => 'CommentUrl',     'Value' => '',      'Title' => 'Url',           'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'int',          'Name' => 'CommentStatus',  'Value' => 0,       'Title' => 'Статус',        'Filter' => true,   'Show' => true,     'Sort' => true,     'Edit' => true,     'Items' => $this->getStatuses()),
            array('Type' => 'bigtext',      'Name' => 'AnswerText',     'Value' => '',      'Title' => 'Ответ',         'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'text',         'Name' => 'AnswerAuthor',   'Value' => '',      'Title' => 'Подпись отв.',  'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'text',         'Name' => 'AnswerEmail',    'Value' => '',      'Title' => 'Email отв.',    'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'text',         'Name' => 'AnswerUrl',      'Value' => '',      'Title' => 'Url отв.',      'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'bool',         'Name' => 'IsDisabled',     'Value' => false,   'Title' => 'Отключен',      'Filter' => true,   'Show' => true,     'Sort' => true,     'Edit' => true),
            array('Type' => 'int',          'Name' => 'RatesCnt',       'Value' => '',      'Title' => 'Кол. оценок',   'Filter' => true,   'Show' => true,     'Sort' => true,     'Edit' => false),
            array('Type' => 'int',          'Name' => 'Rating',         'Value' => '',      'Title' => 'Оценка',        'Filter' => true,   'Show' => true,     'Sort' => true,     'Edit' => false),
        );
    }
    
    protected function getStatuses()
    {
        return array(
            array('Value' => 0, 'Title' => ':|'),
            array('Value' => 1, 'Title' => ':)'),
            array('Value' => -1, 'Title' => ':('),
        );
    }
    
    /**
     * @see parent
     */
    protected function actionBeforeAll()
    {
        $this->getItem(); //cache current item data
    }
    
    /**
     * @see parent
     */
    protected function actionAfterChange()
    {
        $item = $this->getItem(); //get cached before delete data
        $this->updateRelations($item['UserId'], $item['SectionId'], $item['ItemId']);
        return true;
    }
    
    /**
     * @see parent
     */
    protected function actionAfterDelete()
    {
        $item = $this->getItem(); //get cached before delete data
        
        $this->updateRelations($item['UserId'], $item['SectionId'], $item['ItemId']);
        
        //получаем идентификаторы пользователей, оставивших отметки данному комментарию
        $sql =  'SELECT UserId' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'interaction_comments_rates' . 
                ' WHERE UserId!=0 AND CommentId=' . $this->params->itemId;
        $usersIds = $this->db->getValues($sql, 'UserId');
        if (null !== $usersIds) {
            //удаляем все отметки данного комментария и пересчитываем статистику пользователей этих отметок
            $statistic = new Statistic($this->db, $this->core, $this->debug);
            $ret3 = true;
            foreach ($usersIds as $userId) {
                $ret3 = $ret3 && $statistic->updateUserStat($userId, 2);
            }
        }
        
        return true;
    }
    
    /**
     * @param int|null $userId = null
     * @param int|null $sectionId = null
     * @param int|null $itemId = null
     */
    protected function updateRelations($userId, $sectionId, $itemId)
    {
        $statistic = new Statistic($this->db, $this->core, $this->debug);
        
        //обновляем статистику материала
        $ret1 = $statistic->updateItemStat($sectionId, $itemId, 0);
        
        //обновляем статистику пользователя
        $ret2 = true;
        if (0 != $userId) {
            $ret2 = $statistic->updateUserStat($userId, 0);
        }
    }
    
    public function blacklist()
    {
        $sql =  'SELECT IP FROM ' . C_DB_TABLE_PREFIX . 'interaction_comments' . 
                ' WHERE Id=' . $this->params->itemId;
        $item = $this->db->getItem($sql);
        if (!is_null($item)) {
            $sql = 'INSERT INTO ' . C_DB_TABLE_PREFIX . 'interaction_blacklist' . 
                   ' (DateCreate, IP)' . 
                   " VALUES(NOW(), '" . $this->db->addEscape($item['IP']) . "')";
            if ($this->db->query($sql)) {
                $this->result = 'added to black list';
            } else {
                $this->result = 'DB error: ' . $this->db->getError();
            }
        } else {
            $this->result = 'Error: item not exists';
        }
    }
}
