<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\News2;

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
            default:
                $order = 'i.DateCreate DESC';
        }
        if (!$this->params['ShowInteractive'] && !$this->params['ShowLinked'] ) {
            //different SQLs because JOIN required TEMP table
            if (false === strpos($this->srcSections, ',')) {
                $section = $this->core->getSection((int) $this->srcSections, 'path,indic');
                $sql =  'SELECT Id, DateCreate, DateView, Title, Author, Icon, InsIcon, Announce, Body, ViewedCnt, ' . 
                        "'" . $section['path'] . "' AS path, '" . $this->db->addEscape($section['indic']) . "' AS indic" . 
                        ' FROM ' . C_DB_TABLE_PREFIX . 'news2 AS i' . 
                        ' WHERE SectionId=' . (int) $this->srcSections . 
                            ' AND IsHidden=0' . 
                            " AND DateCreate<='" . $now . "'" . 
                            $days . 
                        ' ORDER BY ' . $order . 
                        ' LIMIT ' . $this->params['ItemsStart'] . ', ' . $this->params['ItemsCount'];
                unset($section);
            } else {
                $sql =  'SELECT i.Id, i.DateCreate, i.DateView, i.Title,i.Author, i.Icon, i.InsIcon, i.Announce, i.Body, i.ViewedCnt, ' . 
                        's.path, s.indic' . 
                        ' FROM ' . C_DB_TABLE_PREFIX . 'news2 AS i' . 
                        ' INNER JOIN ' . C_DB_TABLE_PREFIX . 'sections AS s ON i.SectionId=s.id' . 
                        ' WHERE i.SectionId IN (' . $this->srcSections . ')' . 
                            ' AND i.IsHidden=0' . 
                            " AND i.DateCreate<='" . $now . "'" . 
                            $days . 
                        ' ORDER BY ' . $order . 
                        ' LIMIT ' . $this->params['ItemsStart'] . ', ' . $this->params['ItemsCount'];
            }
            
        } else if (!$this->params['ShowInteractive']) {
            $sql =  'SELECT i.Id, i.DateCreate, i.DateView, i.Title, i.Author, i.Icon, i.InsIcon, i.Announce, i.Body, i.ViewedCnt, ' . 
                    's.path, s.indic' . 
                    ' FROM ' . C_DB_TABLE_PREFIX . 'news2 AS i' . 
                    ' INNER JOIN ' . C_DB_TABLE_PREFIX . 'sections AS s ON i.SectionId=s.id' . 
                    ' LEFT JOIN ' . C_DB_TABLE_PREFIX . 'news2_ns AS t3 ON i.Id=t3.ItemId' . 
                    ' WHERE (i.SectionId IN (' . $this->srcSections . ') OR t3.AnotherSectionId IN (' . $this->srcSections . '))' . 
                        ' AND i.IsHidden=0' . 
                        " AND i.DateCreate<='" . $now . "'" . 
                        $days . 
                    ' ORDER BY ' . $order . 
                    ' LIMIT ' . $this->params['ItemsStart'] . ', ' . $this->params['ItemsCount'];
            
        } else if (!$this->params['ShowLinked']) {
            $sql =  'SELECT i.Id, i.DateCreate, i.DateView, i.Title, i.Author, i.Icon, i.InsIcon, i.Announce, i.Body, i.ViewedCnt, ' . 
                    's.path, s.indic, ' . 
                    't4.DateComment, t4.CommentsCnt, t4.CommentsStatusAvg, t4.DateRate, t4.RatesCnt, t4.Rating' . 
                    ' FROM ' . C_DB_TABLE_PREFIX . 'news2 AS i' . 
                    ' INNER JOIN ' . C_DB_TABLE_PREFIX . 'sections AS s ON i.SectionId=s.id' . 
                    ' LEFT JOIN ' . C_DB_TABLE_PREFIX . 'interaction_stat_items AS t4 ON (i.SectionId=t4.SectionId AND i.Id=t4.ItemId)' . 
                    ' WHERE i.SectionId IN (' . $this->srcSections . ') ' . 
                        ' AND i.IsHidden=0' . 
                        " AND i.DateCreate<='" . $now . "'" . 
                        $days . 
                        ' AND (t4.PeriodId=0 OR t4.PeriodId IS NULL)' . 
                    ' ORDER BY ' . $order . 
                    ' LIMIT ' . $this->params['ItemsStart'] . ', ' . $this->params['ItemsCount'];
            
        } else {
            $sql =  'SELECT i.Id, i.DateCreate, i.DateView, i.Title, i.Author, i.Icon, i.InsIcon, i.Announce, i.Body, i.ViewedCnt, ' . 
                    's.path, s.indic, ' . 
                    't4.DateComment, t4.CommentsCnt, t4.CommentsStatusAvg, t4.DateRate, t4.RatesCnt, t4.Rating' . 
                    ' FROM ' . C_DB_TABLE_PREFIX . 'news2 AS i' . 
                    ' INNER JOIN ' . C_DB_TABLE_PREFIX . 'sections AS s ON i.SectionId=s.id' . 
                    ' LEFT JOIN ' . C_DB_TABLE_PREFIX . 'news2_ns AS t3 ON i.Id=t3.ItemId' . 
                    ' LEFT JOIN ' . C_DB_TABLE_PREFIX . 'interaction_stat_items AS t4 ON (i.SectionId=t4.SectionId AND i.Id=t4.ItemId)' . 
                    ' WHERE (i.SectionId IN (' . $this->srcSections . ') OR t3.AnotherSectionId IN (' . $this->srcSections . '))' . 
                        ' AND i.IsHidden=0' . 
                        " AND i.DateCreate<='" . $now . "'" . 
                        $days . 
                        ' AND (t4.PeriodId=0 OR t4.PeriodId IS NULL)' . 
                    ' ORDER BY ' . $order . 
                    ' LIMIT ' . $this->params['ItemsStart'] . ', ' . $this->params['ItemsCount'];
            
        }
        return $this->db->getItems($sql);
    }
}
