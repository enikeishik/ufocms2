<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\News;

/**
 * Widget class
 */
class WidgetCalendar extends \Ufocms\Modules\Widget
{
    /**
     * @see parent
     */
    protected function setContext()
    {
        parent::setContext();
        
        $items      = array();
        $linkEmpty  = false;
        $showCount  = true;
        $sourcePath = null;
        
        if (is_array($this->params)) {
            $items = $this->getItems();
            if (null !== $items) {
                $linkEmpty = $this->params['LinkEmpty'];
                $showCount = $this->params['ShowCount'];
                if (false === strpos($this->srcSections, ',')) {
                    $section = $this->core->getSection((int) $this->srcSections, 'path');
                    $sourcePath = $section['path'];
                }
            } else {
                $items = array();
            }
        }
        
        $this->context = array_merge(
            $this->context, 
            array(
                'items'         => $items, 
                'linkEmpty'     => $linkEmpty, 
                'showCount'     => $showCount, 
                'sourcePath'    => $sourcePath, 
            )
        );
    }
    
    /**
     * @return array|null
     */
    protected function getItems()
    {
        $y = date('Y');
        $m = date('m');
        $now = date('Y-m-d H:i:s');
        $sql =  'SELECT DATE(DateCreate) AS Dt, COUNT(*) AS Cnt' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'news' . 
                ' WHERE SectionId IN (' . $this->srcSections . ')' . 
                    ' AND IsHidden=0' . 
                    " AND DateCreate<='" . $now . "'" . 
                    " AND DateCreate>='" . $y . '-' . str_pad($m, 2, '0', STR_PAD_LEFT) . "-01'" . 
                    " AND DateCreate<'" . (12 > $m ? $y . '-' . str_pad(++$m, 2, '0', STR_PAD_LEFT) : ++$y . '-01') . "-01'" . 
                ' GROUP BY DATE(DateCreate)' . 
                ' ORDER BY DateCreate';
        return $this->db->getItems($sql, 'Dt');
    }
}
