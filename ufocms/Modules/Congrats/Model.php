<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\Congrats;

/**
 * Main module model
 */
class Model extends \Ufocms\Modules\Model //implements IModel
{
    public function getSettings()
    {
        if (null !== $this->settings) {
            return $this->settings;
        }
        $sql =  'SELECT *' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'congrats_settings' . 
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
        $this->getSettings();
        $now = date('Y-m-d H:i:s');
        $sqlBase =  ' FROM ' . C_DB_TABLE_PREFIX . 'congrats_items' . 
                    ' WHERE SectionId=' . $this->params->sectionId . 
                    ' AND IsDisabled=0' . 
                    " AND DateStart<='" . $now . "'" . 
                    " AND DateStop>'" . $now . "'";
        //TODO: switch ($this->params['SortOrder']) {
        $sqlOrder = 'IsPinned DESC, DateStart DESC';
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
        $now = date('Y-m-d H:i:s');
        $sql =  'SELECT *' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'congrats_items' . 
                ' WHERE Id=' . $this->params->itemId . 
                ' AND IsDisabled=0' . 
                " AND DateStart<='" . $now . "'" . 
                " AND DateStop>'" . $now . "'";
        $this->item = $this->db->getItem($sql);
        //обновляем данные по количеству просмотров
        $this->updateViewCount($this->params->itemId);
        return $this->item;
    }
    
    /**
     * @param int $itemId
     * @return bool
     */
    protected function updateViewCount($itemId)
    {
        $sql = 'UPDATE ' . C_DB_TABLE_PREFIX . 'congrats_items' . 
               ' SET DateView=NOW(), ViewedCnt=ViewedCnt+1' . 
               ' WHERE Id=' . $itemId;
        $this->db->query($sql);
    }
}
