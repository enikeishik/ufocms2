<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\Tales;

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
        switch ($this->params['SortOrder']) {
            case 0:
                $order = 'i.OrderNumber';
                break;
            case 1:
                $order = 'i.OrderNumber DESC';
                break;
            case 2:
                $order = 'i.Title';
                break;
            case 3:
                $order = 'i.Title DESC';
                break;
            case 4:
                $order = 'i.DateCreate';
                break;
            case 5:
                $order = 'i.DateCreate DESC';
                break;
            case 6:
                $order = 'i.Url';
                break;
            case 7:
                $order = 'i.Url DESC';
                break;
            case 8:
                $order = 'i.ViewedCnt';
                break;
            case 9:
                $order = 'i.ViewedCnt DESC';
                break;
            default:
                $order = 'i.OrderNumber';
        }
        //different SQLs because JOIN required TEMP table
        if (false === strpos($this->srcSections, ',')) {
            $section = $this->core->getSection((int) $this->srcSections, 'path,indic');
            $sql =  'SELECT Id,DateCreate,DateView,Url,Title,Author,Icon,Announce,Body,ViewedCnt,' . 
                    "'" . $section['path'] . "' AS path,'" . $this->db->addEscape($section['indic']) . "' AS indic" . 
                    ' FROM ' . C_DB_TABLE_PREFIX . 'tales AS i' . 
                    ' WHERE SectionId=' . (int) $this->srcSections . 
                        ' AND IsHidden=0' . 
                    ' ORDER BY ' . $order . 
                    ' LIMIT ' . $this->params['ItemsStart'] . ', ' . $this->params['ItemsCount'];
            unset($section);
        } else {
            $sql =  'SELECT i.Id,i.DateCreate,i.DateView,i.Url,i.Title,i.Author,i.Icon,i.Announce,i.Body,i.Body,i.ViewedCnt,' . 
                    's.path,s.indic' . 
                    ' FROM ' . C_DB_TABLE_PREFIX . 'tales AS i' . 
                    ' INNER JOIN ' . C_DB_TABLE_PREFIX . 'sections AS s ON i.SectionId=s.id' . 
                    ' WHERE i.SectionId IN (' . $this->srcSections . ')' . 
                        ' AND i.IsHidden=0' . 
                    ' ORDER BY ' . $order . 
                    ' LIMIT ' . $this->params['ItemsStart'] . ', ' . $this->params['ItemsCount'];
        }
        return $this->db->getItems($sql);
    }
}
