<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\Faq;

/**
 * Widget class
 */
class WidgetForm extends \Ufocms\Modules\Widget
{
    /**
     * @see parent
     */
    protected function setContext()
    {
        parent::setContext();
        
        $items = $this->getItems();
        if (null === $items) {
            $items = array();
        }
        $isCaptcha = false;
        foreach ($items as $item) {
            if (0 != $item['IsCaptcha']) {
                $isCaptcha = true;
                break;
            }
        }
        
        $this->context = array_merge(
            $this->context, 
            array('items' => $items, 'isCaptcha' => $isCaptcha)
        );
    }
    
    /**
     * @return array|null
     */
    protected function getItems()
    {
        if (false === strpos($this->srcSections, ',')) {
            $sql =  'SELECT i2.IsCaptcha, s.id, s.parentid, s.path, s.indic' . 
                    ' FROM ' . C_DB_TABLE_PREFIX . 'faq_sections AS i2' . 
                    ' INNER JOIN ' . C_DB_TABLE_PREFIX . 'sections AS s ON i2.SectionId=s.id' . 
                    ' WHERE s.id=' . (int) $this->srcSections;
        } else {
            //JOIN uses temporary table
            $sql =  'SELECT (' . 
                        'SELECT IsCaptcha FROM ' . C_DB_TABLE_PREFIX . 'faq_sections WHERE SectionId=s.id' . 
                    ') AS IsCaptcha, s.id, s.parentid, s.path, s.indic' . 
                    ' FROM ' . C_DB_TABLE_PREFIX . 'sections AS s' . 
                    ' WHERE s.id IN (' . $this->srcSections . ')' . 
                    ' ORDER BY s.mask';
        }
        return $this->db->getItems($sql);
    }
}
