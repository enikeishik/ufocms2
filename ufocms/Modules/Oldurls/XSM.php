<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\Oldurls;

/**
 * Module level XmlSitemap generate base class
 */
class XSM extends \Ufocms\Modules\XSM //implements IXSM
{
    protected function getItems()
    {
        $sql =  'SELECT Url' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'oldurls' . 
                ' WHERE SectionId=' . $this->section['id'] . ' AND IsHidden=0' . 
                ' ORDER BY OrderNumber';
        if (null !== $items = $this->db->getItems($sql)) {
            foreach ($items as &$item) {
                $item = array('path' => $this->sectionPath . $item['Url']);
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
