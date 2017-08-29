<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\News;

/**
 * Widget class
 */
class WidgetAuthors extends \Ufocms\Modules\Widget
{
    /**
     * @see parent
     */
    protected function setContext()
    {
        parent::setContext();
        
        $items      = array();
        $showCount  = true;
        $sourcePath = null;
        
        if (is_array($this->params)) {
            $items = $this->getItems();
            if (null !== $items) {
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
        $now = date('Y-m-d H:i:s');
        $sql =  'SELECT Author, COUNT(*) AS Cnt' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'news' . 
                ' WHERE SectionId IN (' . $this->srcSections . ')' . 
                ' AND IsHidden=0' . 
                " AND DateCreate<='" . $now . "'" . 
                " AND Author!=''" . 
                ' GROUP BY Author' . 
                ($this->params['Random'] ? ' ORDER BY RAND()' : ' ORDER BY Author') . 
                (0 < $this->params['Limit'] ? ' LIMIT ' . $this->params['Limit'] : '');
        return $this->db->getItems($sql);
    }
}
