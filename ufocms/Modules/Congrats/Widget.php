<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\Congrats;

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
            $days = " AND i.DateStart>=DATE_ADD('" . $now . "', INTERVAL - " . $this->params['DaysLimit'] . ' DAY)';
        } else {
            $days = '';
        }
        $pinned = '';
        if (1 == $this->params['Pinned']) {
            $pinned = ' AND i.IsPinned!=0';
        } else if (2 == $this->params['Pinned']) {
            $pinned = ' AND i.IsPinned=0';
        }
        $highlighted = '';
        if (1 == $this->params['Highlighted']) {
            $highlighted = ' AND i.IsHighlighted!=0';
        } else if (2 == $this->params['Highlighted']) {
            $highlighted = ' AND i.IsHighlighted=0';
        }
        switch ($this->params['SortOrder']) {
            case 0:
                $order = 'i.IsPinned DESC, i.DateStart DESC';
                break;
            case 1:
                $order = 'i.IsPinned, i.DateStart';
                break;
            case 2:
                $order = 'i.DateStart DESC';
                break;
            case 3:
                $order = 'i.DateStart';
                break;
            case 4:
                $order = 'i.ViewedCnt DESC';
                break;
            case 5:
                $order = 'i.ViewedCnt';
                break;
            default:
                $order = 'i.IsPinned DESC, i.DateStart DESC';
        }
        //different SQLs because JOIN required TEMP table
        if (false === strpos($this->srcSections, ',')) {
            $section = $this->core->getSection((int) $this->srcSections, 'path,indic');
            $sql =  'SELECT Id,DateStart,IsPinned,IsHighlighted,ViewedCnt,Thumbnail,ShortDesc,' . 
                    "'" . $section['path'] . "' AS path,'" . $this->db->addEscape($section['indic']) . "' AS indic" . 
                    ' FROM ' . C_DB_TABLE_PREFIX . 'congrats_items AS i' . 
                    ' WHERE SectionId=' . (int) $this->srcSections . 
                        ' AND IsDisabled=0' . 
                        " AND DateStart<='" . $now . "'" . 
                        " AND DateStop>'" . $now . "'" . 
                        $days . 
                        $pinned . 
                        $highlighted . 
                    ' ORDER BY ' . $order . 
                    ' LIMIT ' . $this->params['ItemsStart'] . ', ' . $this->params['ItemsCount'];
            unset($section);
        } else {
            $sql =  'SELECT i.Id,i.DateStart,i.IsPinned,i.IsHighlighted,i.ViewedCnt,i.Thumbnail,i.ShortDesc,' . 
                    's.path,s.indic' . 
                    ' FROM ' . C_DB_TABLE_PREFIX . 'congrats_items AS i' . 
                    ' INNER JOIN ' . C_DB_TABLE_PREFIX . 'sections AS s ON i.SectionId=s.id' . 
                    ' WHERE i.SectionId IN (' . $this->srcSections . ')' . 
                        ' AND i.IsDisabled=0' . 
                        " AND i.DateStart<='" . $now . "'" . 
                        " AND i.DateStop>'" . $now . "'" . 
                        $days . 
                        $pinned . 
                        $highlighted . 
                    ' ORDER BY ' . $order . 
                    ' LIMIT ' . $this->params['ItemsStart'] . ', ' . $this->params['ItemsCount'];
        }
        return $this->db->getItems($sql);
    }
}
