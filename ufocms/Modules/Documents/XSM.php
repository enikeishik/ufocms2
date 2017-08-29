<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\Documents;

/**
 * Module level XmlSitemap generate base class
 */
class XSM extends \Ufocms\Modules\XSM //implements IXSM
{
    protected function getItems()
    {
        return array();
    }
    
    protected function getPageLength()
    {
        return 1;
    }
    
    protected function getPagesCount()
    {
        //вывод страниц
        //параметр PageLength всегда равен 1
        //определяем содержит ли текст документа разделитель страниц
        $sql =  'SELECT Body' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'documents' . 
                ' WHERE SectionId=' . $this->section['id'] . 
                " AND 0!=INSTR(Body, '" . $this->db->addEscape(C_SITE_PAGEBREAK_SEPERATOR) . "')";
        $body = $this->db->getValue($sql, 'Body');
        if (null !== $body) {
            return substr_count($body, C_SITE_PAGEBREAK_SEPERATOR);
        } else {
            return 1;
        }
    }
}
