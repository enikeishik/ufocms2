<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\Shop;

/**
 * Module level XmlSitemap generate base class
 */
class XSM extends \Ufocms\Modules\XSM //implements IXSM
{
    protected function getItems()
    {
        $sql =  'SELECT i.Alias, c.Alias AS CategoryAlias' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'shop_items AS i' . 
                ' INNER JOIN ' . C_DB_TABLE_PREFIX . 'shop_categories AS c ON i.CategoryId=c.Id' . 
                ' WHERE i.SectionId=' . $this->section['id'] . 
                ' AND i.IsHidden=0 AND c.IsHidden=0' . 
                ' ORDER BY i.OrderNumber';
        $items = $this->db->getItems($sql);
        if (null === $items) {
            $items = array();
        }
        foreach ($items as &$item) {
            $item = array('path' => $this->sectionPath . $item['CategoryAlias'] . '/' . $item['Alias']);
        }
        unset($item);
        
        $sql =  'SELECT Alias' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'shop_categories' . 
                ' WHERE SectionId=' . $this->section['id'] . 
                ' AND IsHidden=0' . 
                ' ORDER BY Mask';
        $categories = $this->db->getItems($sql);
        if (null !== $categories) {
            foreach ($categories as &$item) {
                $item = array('path' => $this->sectionPath . $item['Alias']);
            }
            unset($item);
            $items = array_merge($items, $categories);
            unset($categories);
        }
        
        return $items;
    }
    
    protected function getPageLength()
    {
       return 0;
    }
}
