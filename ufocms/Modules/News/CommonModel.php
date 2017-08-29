<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\News;

/**
 * Main module model
 */
class CommonModel extends \Ufocms\Modules\Model //implements IModel
{
    public function getSettings()
    {
        if (null !== $this->settings) {
            return $this->settings;
        }
        $this->settings = array(
            'BodyHead'          => '', 
            'BodyFoot'          => '', 
            'AnnounceLength'    => 255, 
            'IsArchive'         => true, 
        );
        return $this->settings;
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
        $sqlBase =  ' FROM ' . C_DB_TABLE_PREFIX . 'news AS n' . 
                    ' INNER JOIN ' . C_DB_TABLE_PREFIX . 'news_sections AS ns ON n.SectionId=ns.SectionId' . 
                    ' INNER JOIN ' . C_DB_TABLE_PREFIX . 'sections AS s ON n.SectionId=s.id' . 
                    " WHERE n.IsHidden=0 AND n.DateCreate<='" . $now . "'";
        if ($this->moduleParams['isRss'] || $this->moduleParams['isYandex'] || $this->moduleParams['isYaDzen']) {
            $sqlBase .= ' AND n.IsRss=1';
        }
        $sqlBase .= ' AND s.isenabled!=0';
        $sqlOrder = 'n.DateCreate DESC';
        $sql =  'SELECT n.*, ns.BodyHead, ns.BodyFoot, s.path, s.indic' . 
                $sqlBase;
        if ($this->moduleParams['isRss'] || $this->moduleParams['isYandex'] || $this->moduleParams['isYaDzen']) {
            $sql .= ' ORDER BY n.DateCreate DESC' . 
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
        $sqlBase =  ' FROM ' . C_DB_TABLE_PREFIX . 'news AS n' . 
                    ' INNER JOIN ' . C_DB_TABLE_PREFIX . 'news_sections AS ns ON n.SectionId=ns.SectionId' . 
                    ' INNER JOIN ' . C_DB_TABLE_PREFIX . 'sections AS s ON n.SectionId=s.id' . 
                    " WHERE n.IsHidden=0 AND n.DateCreate<='" . $now . "'" . 
                        " AND n.DateCreate>='" . $this->moduleParams['date'] . " 00:00:00'" . 
                        " AND n.DateCreate<='" . $this->moduleParams['date'] . " 23:59:59'";
        $sqlOrder = 'n.DateCreate DESC';
        $sql =  'SELECT n.*, ns.BodyHead, ns.BodyFoot, s.path, s.indic' . 
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
    public function getAuthors()
    {
        $sql =  'SELECT COUNT(DISTINCT Author) AS Cnt' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'news' . 
                '';
        $this->itemsCount = $this->db->getValue($sql, 'Cnt');
        if (0 == $this->itemsCount) {
            $this->items = array();
            return;
        }
        
        $now = date('Y-m-d H:i:s');
        $sql =  'SELECT Author, COUNT(*) AS Cnt' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'news AS n' . 
                ' INNER JOIN ' . C_DB_TABLE_PREFIX . 'news_sections AS ns ON n.SectionId=ns.SectionId' . 
                ' INNER JOIN ' . C_DB_TABLE_PREFIX . 'sections AS s ON n.SectionId=s.id' . 
                " WHERE n.IsHidden=0 AND n.DateCreate<='" . $now . "'" . 
                ' GROUP BY n.Author' . 
                ' ORDER BY n.Author' . 
                ' LIMIT ' . (($this->params->page - 1) * $this->params->pageSize) . 
                        ', ' . $this->params->pageSize;
        $this->items = $this->db->getItems($sql);
        return $this->items;
    }
    
    /**
     * @return array|null
     */
    public function getItemsByAuthor()
    {
        $now = date('Y-m-d H:i:s');
        $sqlBase =  ' FROM ' . C_DB_TABLE_PREFIX . 'news AS n' . 
                    ' INNER JOIN ' . C_DB_TABLE_PREFIX . 'news_sections AS ns ON n.SectionId=ns.SectionId' . 
                    ' INNER JOIN ' . C_DB_TABLE_PREFIX . 'sections AS s ON n.SectionId=s.id' . 
                    " WHERE n.IsHidden=0 AND n.DateCreate<='" . $now . "'" . 
                        " AND n.Author='" . $this->db->addEscape($this->moduleParams['author']) . "'";
        $sqlOrder = 'n.DateCreate DESC';
        $sql =  'SELECT n.*, ns.BodyHead, ns.BodyFoot, s.path, s.indic' . 
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
     * 
     */
    public function getItem()
    {
        if (null !== $this->item) {
            return $this->item;
        }
        $now = date('Y-m-d H:i:s');
        $sql =  'SELECT n.*, ns.BodyHead, ns.BodyFoot, s.path, s.indic' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'news AS n' . 
                ' INNER JOIN ' . C_DB_TABLE_PREFIX . 'news_sections AS ns ON n.SectionId=ns.SectionId' . 
                ' INNER JOIN ' . C_DB_TABLE_PREFIX . 'sections AS s ON n.SectionId=s.id' . 
                ' WHERE n.Id=' . $this->params->itemId . 
                ' AND n.IsHidden=0' . 
                " AND n.DateCreate<='" . $now . "'";
        $this->item = $this->db->getItem($sql);
        return $this->item;
    }
}
