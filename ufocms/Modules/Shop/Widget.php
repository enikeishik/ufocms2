<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\Shop;

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
        $category = '';
        if (0 != $this->params['CategoryId']) {
            if (0 == $this->params['CatChildren']) {
                $category = ' AND i.CategoryId=' . (int) $this->params['CategoryId'];
            } else {
                $category = ' AND c.Mask LIKE (' . 
                                "SELECT CONCAT(Mask, '%')" . 
                                ' FROM ' . C_DB_TABLE_PREFIX . 'shop_categories' . 
                                ' WHERE Id=' . (int) $this->params['CategoryId'] . 
                            ')';
            }
        }
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
                $order = 'i.DateCreate DESC';
                break;
            case 3:
                $order = 'i.DateCreate';
                break;
            case 4:
                $order = 'i.Title';
                break;
            case 5:
                $order = 'i.Title DESC';
                break;
            case 6:
                $order = 'i.ViewedCnt DESC';
                break;
            case 7:
                $order = 'i.ViewedCnt';
                break;
            default:
                $order = 'i.OrderNumber';
        }
        $section = $this->core->getSection((int) $this->srcSections, 'path,indic');
        $sql =  'SELECT i.Id,i.DateCreate,i.Alias,i.Title,i.Thumbnail,i.ShortDesc,i.Price,i.ViewedCnt,' . 
                'c.Alias AS CategoryAlias,c.Title AS CategoryTitle,' . 
                "'" . $section['path'] . "' AS path,'" . $this->db->addEscape($section['indic']) . "' AS indic" . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'shop_items AS i' . 
                ' INNER JOIN ' . C_DB_TABLE_PREFIX . 'shop_categories AS c ON i.CategoryId=c.Id' . 
                ' WHERE i.SectionId=' . (int) $this->srcSections . 
                    ' AND i.IsHidden=0' . 
                    ' AND c.IsHidden=0' . 
                    $category . 
                ' ORDER BY ' . $order . 
                ' LIMIT ' . $this->params['ItemsStart'] . ', ' . $this->params['ItemsCount'];
        unset($section);
        return $this->db->getItems($sql);
    }
}
