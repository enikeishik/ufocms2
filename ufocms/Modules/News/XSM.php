<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\News;

/**
 * Module level XmlSitemap generate base class
 */
class XSM extends \Ufocms\Modules\XSM //implements IXSM
{
    protected function getItems()
    {
        $sql =  'SELECT Id' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'news' . 
                ' WHERE SectionId=' . $this->section['id'] . ' AND IsHidden=0' . 
                ' ORDER BY DateCreate DESC';
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
        $sql =  'SELECT PageLength' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'news_sections' . 
                ' WHERE SectionId=' . $this->section['id'] . ' AND IsArchive!=0';
        $pageLength = $this->db->getValue($sql, 'PageLength');
        return null !== $pageLength ? $pageLength : 1;
    }
}
