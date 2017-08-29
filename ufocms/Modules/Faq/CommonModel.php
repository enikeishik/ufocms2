<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\Faq;

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
            'PageLength'        => 10, 
            'Orderby'           => 0, 
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
        $sqlBase =  ' FROM ' . C_DB_TABLE_PREFIX . 'faq AS i' . 
                    ' INNER JOIN ' . C_DB_TABLE_PREFIX . 'faq_sections AS i2 ON i.SectionId=i2.SectionId' . 
                    ' INNER JOIN ' . C_DB_TABLE_PREFIX . 'sections AS s ON i.SectionId=s.id' . 
                    ' WHERE i.IsHidden=0';
        $sqlBase .= ' AND s.isenabled!=0';
        $sqlOrder = 'i.DateCreate DESC';
        $sql =  'SELECT i.*, i2.BodyHead, i2.BodyFoot, s.path, s.indic' . 
                $sqlBase;
        if ($this->moduleParams['isRss']) {
            $sql .= ' ORDER BY i.DateCreate DESC' . 
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
        $sqlBase =  ' FROM ' . C_DB_TABLE_PREFIX . 'faq AS i' . 
                    ' INNER JOIN ' . C_DB_TABLE_PREFIX . 'faq_sections AS i2 ON i.SectionId=i2.SectionId' . 
                    ' INNER JOIN ' . C_DB_TABLE_PREFIX . 'sections AS s ON i.SectionId=s.id' . 
                    " WHERE i.IsHidden=0 AND i.DateCreate<='" . $now . "'" . 
                        " AND i.DateCreate>='" . $this->moduleParams['date'] . " 00:00:00'" . 
                        " AND i.DateCreate<='" . $this->moduleParams['date'] . " 23:59:59'";
        $sqlOrder = 'i.DateCreate DESC';
        $sql =  'SELECT i.*, i2.BodyHead, i2.BodyFoot, s.path, s.indic' . 
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
        $sql =  'SELECT i.*, i2.BodyHead, i2.BodyFoot, s.path, s.indic' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'faq AS i' . 
                ' INNER JOIN ' . C_DB_TABLE_PREFIX . 'faq_sections AS i2 ON i.SectionId=i2.SectionId' . 
                ' INNER JOIN ' . C_DB_TABLE_PREFIX . 'sections AS s ON i.SectionId=s.id' . 
                ' WHERE i.Id=' . $this->params->itemId . 
                ' AND i.IsHidden=0';
        $this->item = $this->db->getItem($sql);
        return $this->item;
    }
}
