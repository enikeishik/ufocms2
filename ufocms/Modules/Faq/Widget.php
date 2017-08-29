<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\Faq;

/**
 * Widget class
 */
class Widget extends \Ufocms\Modules\Widget
{
    /**
     * @see parent
     */
    protected function setContext()
    {
        parent::setContext();
        
        $items = array();
        if (is_array($this->params)) {
            $items = $this->getItems();
            if (null === $items) {
                $items = array();
            }
        }
        
        $this->context = array_merge($this->context, array('items' => $items));
    }
    
    /**
     * @return array|null
     */
    protected function getItems()
    {
        if (0 >= $this->params['ItemsCount']) {
            return null;
        }
        if ($this->params['ItemsStart'] < 0) {
            $this->params['ItemsStart'] = 0;
        }
        $now = date('Y-m-d H:i:s');
        if (0 < $this->params['DaysLimit']) {
            $days = " AND i.DateCreate>=DATE_ADD('" . $now . "', INTERVAL - " . $this->params['DaysLimit'] . ' DAY)';
        } else {
            $days = '';
        }
        switch ($this->params['SortOrder']) {
            case 0:
                $order = 'i.DateCreate DESC';
                break;
            case 1:
                $order = 'i.DateCreate';
                break;
            case 2:
                $order = 'i.DateAnswer';
                break;
            case 3:
                $order = 'i.DateAnswer DESC';
                break;
            default:
                $order = 'i.DateCreate DESC';
        }
        //different SQLs because JOIN required TEMP table
        if (false === strpos($this->srcSections, ',')) {
            $section = $this->core->getSection((int) $this->srcSections, 'path,indic');
            $sql =  'SELECT Id,DateCreate,DateAnswer,' . 
                    'USign,UEmail,UUrl,UMessage,' . 
                    'ASign,AEmail,AUrl,AMessage,' . 
                    "'" . $section['path'] . "' AS path,'" . $this->db->addEscape($section['indic']) . "' AS indic" . 
                    ' FROM ' . C_DB_TABLE_PREFIX . 'faq AS i' . 
                    ' WHERE SectionId=' . (int) $this->srcSections . 
                        ' AND IsHidden=0' . 
                        " AND DateCreate<='" . $now . "'" . 
                        $days . 
                    ' ORDER BY ' . $order . 
                    ' LIMIT ' . $this->params['ItemsStart'] . ', ' . $this->params['ItemsCount'];
            unset($section);
        } else {
            $sql =  'SELECT i.Id,i.DateCreate,i.DateAnswer,' . 
                    'i.USign,i.UEmail,i.UUrl,i.UMessage,' . 
                    'i.ASign,i.AEmail,i.AUrl,i.AMessage,' . 
                    's.path,s.indic' . 
                    ' FROM ' . C_DB_TABLE_PREFIX . 'faq AS i' . 
                    ' INNER JOIN ' . C_DB_TABLE_PREFIX . 'sections AS s ON i.SectionId=s.id' . 
                    ' WHERE i.SectionId IN (' . $this->srcSections . ')' . 
                        ' AND i.IsHidden=0' . 
                        " AND i.DateCreate<='" . $now . "'" . 
                        $days . 
                    ' ORDER BY ' . $order . 
                    ' LIMIT ' . $this->params['ItemsStart'] . ', ' . $this->params['ItemsCount'];
        }
        return $this->db->getItems($sql);
    }
}
