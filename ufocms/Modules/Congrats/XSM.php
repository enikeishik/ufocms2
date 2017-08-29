<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\Congrats;

/**
 * Module level XmlSitemap generate base class
 */
class XSM extends \Ufocms\Modules\XSM //implements IXSM
{
    protected function getItems()
    {
        $now = date('Y-m-d H:i:s');
        $sql =  'SELECT Id' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'congrats_items' . 
                ' WHERE SectionId=' . $this->section['id'] . ' AND IsDisabled=0' . 
                " AND DateStart<='" . $now . "'" . 
                " AND DateStop>'" . $now . "'" . 
                ' ORDER BY IsPinned DESC, DateStart DESC';
        if (null !== $items = $this->db->getItems($sql)) {
            foreach ($items as &$item) {
                $item = array('path' => $this->sectionPath . $item['Id']);
            }
            unset($item);
            return $items;
        } else {
            return array();
        }
    }
    
    protected function getPageLength()
    {
        return 0;
    }
}
