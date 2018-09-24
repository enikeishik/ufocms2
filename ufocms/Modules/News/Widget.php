<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\News;

/**
 * Widget class
 */
class Widget extends \Ufocms\Modules\Widget
{
    use Tools;
    
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
        $author = '';
        if (!empty($this->params['Author'])) {
            $author = " AND i.Author='" . $this->db->addEscape($this->params['Author']) . "'";
        }
        switch ($this->params['SortOrder']) {
            case 0:
                $order = 'i.DateCreate DESC';
                break;
            case 1:
                $order = 'i.DateCreate';
                break;
            case 2:
                $order = 'i.Title';
                break;
            case 3:
                $order = 'i.Title DESC';
                break;
            case 4:
                $order = 'i.ViewedCnt DESC';
                break;
            case 5:
                $order = 'i.ViewedCnt';
                break;
            default:
                $order = 'i.DateCreate DESC';
        }
        //different SQLs because JOIN required TEMP table
        if (false === strpos($this->srcSections, ',')) {
            $section = $this->core->getSection((int) $this->srcSections, 'path,indic');
            if (null === $section) {
                return null;
            }
            $sql =  'SELECT Id, DateCreate, Title, Author, Icon, Announce, Body, ViewedCnt, ' . 
                    "'" . $section['path'] . "' AS path, '" . $this->db->addEscape($section['indic']) . "' AS indic" . 
                    ' FROM ' . C_DB_TABLE_PREFIX . 'news AS i' . 
                    ' WHERE SectionId=' . (int) $this->srcSections . 
                        ' AND IsHidden=0' . 
                        " AND DateCreate<='" . $now . "'" . 
                        $days . 
                        $author . 
                    ' ORDER BY ' . $order . 
                    ' LIMIT ' . $this->params['ItemsStart'] . ', ' . $this->params['ItemsCount'];
            unset($section);
        } else {
            $sql =  'SELECT i.Id, i.DateCreate, i.Title, i.Author, i.Icon, i.Announce, i.Body, i.ViewedCnt, ' . 
                    's.path, s.indic' . 
                    ' FROM ' . C_DB_TABLE_PREFIX . 'news AS i' . 
                    ' INNER JOIN ' . C_DB_TABLE_PREFIX . 'sections AS s ON i.SectionId=s.id' . 
                    ' WHERE i.SectionId IN (' . $this->srcSections . ')' . 
                        ' AND i.IsHidden=0' . 
                        " AND i.DateCreate<='" . $now . "'" . 
                        $days . 
                        $author . 
                    ' ORDER BY ' . $order . 
                    ' LIMIT ' . $this->params['ItemsStart'] . ', ' . $this->params['ItemsCount'];
        }
        return $this->db->getItems($sql);
    }
}
