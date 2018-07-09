<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\News;

/**
 * Main module model
 */
class Model extends \Ufocms\Modules\Model //implements IModel
{
    /**
     * @see parent
     */
    public function getSettings()
    {
        if (null !== $this->settings) {
            return $this->settings;
        }
        $sql =  'SELECT *' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'news_sections' . 
                ' WHERE SectionId=' . $this->params->sectionId;
        $this->settings = $this->db->getItem($sql);
        $this->params->pageSize = $this->settings['PageLength'];
        return $this->settings;
    }
    
    /**
     * @return bool
     */
    protected function isFeed()
    {
        return $this->moduleParams['isRss'] 
            || $this->moduleParams['isYandex'] 
            || $this->moduleParams['isYaDzen'] 
            || $this->moduleParams['isRambler'] 
            || $this->moduleParams['isYaTurbo'];
    }
    
    /**
     * @return array|null
     */
    public function getItems()
    {
        if (null !== $this->items) {
            return $this->items;
        }
        $now = date('Y-m-d H:i:s');
        $sqlBase =  ' FROM ' . C_DB_TABLE_PREFIX . 'news' . 
                    ' WHERE SectionId=' . $this->params->sectionId . 
                    " AND IsHidden=0 AND DateCreate<='" . $now . "'";
        if ($this->isFeed()) {
            $sqlBase .= ' AND IsRss=1' . 
                        " AND DateCreate>DATE_ADD('" . $now . "', INTERVAL - " . $this->settings['RssExpireOffset'] . ' MINUTE)';
        }
        $sqlOrder = 'DateCreate DESC';
        $sql =  'SELECT *' . 
                $sqlBase;
        if ($this->isFeed()) {
            $sql .= ' ORDER BY DateCreate DESC' . 
                    ' LIMIT ' . ($this->params->pageSize * 2);
        } else {
            $sql .= ' ORDER BY ' . $sqlOrder . 
                    ' LIMIT ' . (($this->params->page - 1) * $this->params->pageSize) . 
                        ', ' . $this->params->pageSize;
        }
        $this->itemsCount = $this->db->getValue('SELECT COUNT(*) AS Cnt' . $sqlBase, 'Cnt');
        if (0 < $this->itemsCount) {
            $this->items = $this->db->getItems($sql);
        } else {
            $this->items = array();
        }
        return $this->items;
    }
    
    /**
     * @return array|null
     */
    public function getItemsByDate()
    {
        $now = date('Y-m-d H:i:s');
        $sqlBase =  ' FROM ' . C_DB_TABLE_PREFIX . 'news' . 
                    ' WHERE SectionId=' . $this->params->sectionId . 
                        " AND IsHidden=0 AND DateCreate<='" . $now . "'" . 
                        " AND DateCreate>='" . $this->moduleParams['date'] . " 00:00:00'" . 
                        " AND DateCreate<='" . $this->moduleParams['date'] . " 23:59:59'";
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
    
    /**
     * @return array|null
     */
    public function getItemsByAuthor()
    {
        $now = date('Y-m-d H:i:s');
        $sqlBase =  ' FROM ' . C_DB_TABLE_PREFIX . 'news' . 
                    ' WHERE SectionId=' . $this->params->sectionId . 
                        " AND IsHidden=0 AND DateCreate<='" . $now . "'" . 
                        " AND Author='" . $this->db->addEscape($this->moduleParams['author']) . "'";
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
    
    /**
     * @return array|null
     */
    public function getItem()
    {
        if (null !== $this->item) {
            return $this->item;
        }
        $now = date('Y-m-d H:i:s');
        $sql =  'SELECT *' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'news' . 
                ' WHERE Id=' . $this->params->itemId . 
                ' AND IsHidden=0' . 
                " AND DateCreate<='" . $now . "'";
        $this->item = $this->db->getItem($sql);
        
        //обновляем данные по количеству просмотров
        $this->updateViewCount($this->item['Id']);
        
        return $this->item;
    }
    
    /**
     * @param int $itemId
     */
    protected function updateViewCount($itemId)
    {
        $sql = 'UPDATE ' . C_DB_TABLE_PREFIX . 'news' . 
               ' SET ViewedCnt=ViewedCnt+1' . 
               ' WHERE Id=' . $itemId;
        $this->db->query($sql);
    }
}
