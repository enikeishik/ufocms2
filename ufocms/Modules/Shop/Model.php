<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\Shop;

/**
 * Main module model
 */
class Model extends \Ufocms\Modules\Model //implements IModel
{
    /**
     * Количество символов на уровень в маске категорий.
     * @var int
     */
    protected $maskCharsPerLevel = 2;
    
    /**
     * @var ModelOrder
     */
    public $order = null;
    
    /**
     * Инициализация параметров и бизнес логики.
     */
    protected function init()
    {
        if (null === $this->moduleParams['categoryId']) {
            $this->moduleParams['categoryId'] = 0;
        }
        if (null === $this->moduleParams['itemId']) {
            $this->moduleParams['itemId'] = 0;
        } else {
            //redirect to /cat/itm/
            $itemAlias = $this->getItemAlias($this->moduleParams['itemId']);
            if (null === $itemAlias) {
                $this->core->riseError(404, 'Item not exists');
            }
            $itemCategoryAlias = $this->getItemCategoryAlias($this->moduleParams['itemId']);
            if (null === $itemCategoryAlias) {
                $this->core->riseError(404, 'Item category not exists');
            }
            $this->core->riseError(301, 'Use alias', $this->params->$sectionPath . $itemCategoryAlias . '/' . $itemAlias);
        }
        if (null !== $this->moduleParams['catAlias']) { // /cat/
            if (null !== $this->moduleParams['goodsAlias']) { // /cat/itm/
                list(
                    $this->moduleParams['categoryId'], 
                    $this->moduleParams['itemId']
                ) = 
                    $this->getCategoryItemIdsByAlias(
                        $this->moduleParams['catAlias'], 
                        $this->moduleParams['goodsAlias']
                    );
                $this->params->itemId = $this->moduleParams['itemId'];
                if (null === $this->moduleParams['categoryId'] || null === $this->moduleParams['itemId']) {
                    $this->core->riseError(404, 'Item not exists');
                }
            } else {
                $this->moduleParams['categoryId'] = 
                    $this->getCategoryIdByAlias($this->moduleParams['catAlias']);
                if (null === $this->moduleParams['categoryId']) {
                    $this->core->riseError(404, 'Item category not exists');
                }
            }
        }
        
        if (null !== $this->moduleParams['order']) {
            $container = $this->core->getContainer([
                'module'        => &$this->module, 
                'params'        => &$this->params, 
                'db'            => &$this->db, 
                'core'          => &$this->core, 
                'debug'         => &$this->debug, 
                'config'        => &$this->config, 
                'tools'         => &$this->tools, 
                'moduleParams'  => &$this->moduleParams, 
            ]);
            $this->order = new ModelOrder($container);
        }
    }
    
    public function getSettings()
    {
        if (null !== $this->settings) {
            return $this->settings;
        }
        $sql =  'SELECT *' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'shop_sections' . 
                ' WHERE SectionId=' . $this->params->sectionId;
        $this->settings = $this->db->getItem($sql);
        if (null === $this->moduleParams['pageSize']) {
            $this->params->pageSize = $this->settings['PageLength'];
        } else {
            if ($this->moduleParams['pageSize'] < $this->config->pageSizeMin
            || $this->moduleParams['pageSize'] > $this->config->pageSizeMax) {
                $this->params->pageSize = $this->settings['PageLength'];
            }
        }
        return $this->settings;
    }
    
    public function getItems()
    {
        if (null !== $this->items) {
            return $this->items;
        }
        
        /* разные запросы для подсчета и выборки и при ограничении по категории */
        $fields =   'i.Id, i.SectionId, i.CategoryId, i.RelatedInfoId, ' . 
                    'i.DateCreate, i.Alias, i.Title, i.Thumbnail, i.ShortDesc, i.FullDesc, i.Price, i.ViewedCnt';
        if (0 != $this->moduleParams['categoryId']) {
            $parentCatObj = $this->getCategory($this->moduleParams['categoryId']);
            $mask = " AND c.Mask LIKE '" . $parentCatObj['Mask'] . "%'";
            $maskCnt = " AND (SELECT Mask FROM " . C_DB_TABLE_PREFIX . "shop_categories WHERE Id=i.CategoryId) LIKE '" . $parentCatObj['Mask'] . "%'";
            $sqlBase =  ' FROM ' . C_DB_TABLE_PREFIX . 'shop_items AS i' . 
                        ' INNER JOIN ' . C_DB_TABLE_PREFIX . 'shop_categories AS c ON i.CategoryId=c.Id' . 
                        ' WHERE i.SectionId=' . $this->params->sectionId . 
                        ' AND i.IsHidden=0 AND c.IsHidden=0' . 
                        $mask;
            $sqlCnt =   ' FROM ' . C_DB_TABLE_PREFIX . 'shop_items AS i' . 
                        ' WHERE i.SectionId=' . $this->params->sectionId . 
                        ' AND i.IsHidden=0 AND (SELECT IsHidden FROM ' . C_DB_TABLE_PREFIX . 'shop_categories WHERE Id=i.CategoryId)=0' . 
                        $maskCnt;
            switch ($this->settings['Orderby']) {
                case 0:
                    $sqlOrder = 'i.OrderNumber';
                    break;
                case 1:
                    $sqlOrder = 'i.OrderNumber DESC';
                    break;
                case 2:
                    $sqlOrder = 'i.Title';
                    break;
                case 3:
                    $sqlOrder = 'i.Title DESC';
                    break;
                case 4:
                    $sqlOrder = 'i.DateCreate';
                    break;
                case 5:
                    $sqlOrder = 'i.DateCreate DESC';
                    break;
                case 6:
                    $sqlOrder = 'i.Price';
                    break;
                case 7:
                    $sqlOrder = 'i.Price DESC';
                    break;
                default:
                    $sqlOrder = 'i.OrderNumber';
            }
            $sql =  'SELECT ' . $fields . 
                    ', c.Alias AS CategoryAlias, c.Title AS CategoryTitle' . 
                    $sqlBase;
        } else {
            $sqlBase =  ' FROM ' . C_DB_TABLE_PREFIX . 'shop_items AS i' . 
                        ' WHERE i.SectionId=' . $this->params->sectionId . 
                        ' AND i.IsHidden=0 AND (SELECT IsHidden FROM ' . C_DB_TABLE_PREFIX . 'shop_categories WHERE Id=i.CategoryId)=0';
            $sqlCnt = $sqlBase;
            $sqlOrder = 'i.OrderNumber';
            $sql =  'SELECT ' . $fields . 
                    ', (SELECT Alias FROM ' . C_DB_TABLE_PREFIX . 'shop_categories WHERE Id=i.CategoryId) AS CategoryAlias' . 
                    ', (SELECT Title FROM ' . C_DB_TABLE_PREFIX . 'shop_categories WHERE Id=i.CategoryId) AS CategoryTitle' . 
                    $sqlBase;
        }
        if ($this->moduleParams['isRss']) {
            $sql .= ' ORDER BY ' . $sqlOrder . 
                    ' LIMIT ' . (10 * $this->params->pageSize);
        } else {
            $sql .= ' ORDER BY ' . $sqlOrder . 
                    ' LIMIT ' . (($this->params->page - 1) * $this->params->pageSize) . 
                        ', ' . $this->params->pageSize;
        }
        $this->itemsCount = $this->db->getValue('SELECT COUNT(*) AS Cnt' . $sqlCnt, 'Cnt');
        
        if (0 < $this->itemsCount) {
            $this->items = $this->db->getItems($sql);
        } else {
            $this->items = array();
        }
        return $this->items;
    }
    
    public function getItem()
    {
        if (null !== $this->item) {
            return $this->item;
        }
        $sql =  'SELECT i.*, c.Alias AS CategoryAlias, c.Title AS CategoryTitle' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'shop_items AS i' . 
                ' INNER JOIN ' . C_DB_TABLE_PREFIX . 'shop_categories AS c ON i.CategoryId=c.Id' . 
                ' WHERE i.Id=' . $this->params->itemId . 
                ' AND i.IsHidden=0 AND c.IsHidden=0';
        $this->item = $this->db->getItem($sql);
        //обновляем данные по количеству просмотров
        $this->updateViewCount($this->params->itemId);
        return $this->item;
    }
    
    /**
     * @param int $itemId
     * @return bool
     */
    protected function updateViewCount($itemId)
    {
        $sql = 'UPDATE ' . C_DB_TABLE_PREFIX . 'shop_items' . 
               ' SET DateView=NOW(), ViewedCnt=ViewedCnt+1' . 
               ' WHERE Id=' . $itemId;
        $this->db->query($sql);
    }
    
    /**
     * Get categories.
     * @return array|null
     */
    public function getCategories() {
        $sql =  'SELECT *' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'shop_categories' . 
                ' WHERE SectionId=' . $this->params->sectionId . 
                ' AND IsHidden=0' . 
                ' ORDER BY Mask';
        return $this->db->getItems($sql);
    }
    
    /**
     * @param int $topCount
     * @param int $childCount
     * @return array|null
     */
    public function getTopCategories($topCount, $childCount)
    {
        $sql =  'SELECT c.*' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'shop_categories AS c' . 
                ' WHERE SectionId=' . $this->params->sectionId . 
                ' AND IsHidden=0' . 
                ' AND LevelId=1' . 
                ' ORDER BY OrderId' . 
                ' LIMIT ' . $topCount;
        $items = $this->db->getItems($sql);
        if (0 < $childCount && null !== $items && 0 < count($items)) {
            $sqls = array();
            foreach ($items as $item) {
                $sqls[] =   '(SELECT *' . 
                            ' FROM ' . C_DB_TABLE_PREFIX . 'shop_categories' . 
                            ' WHERE ParentId=' . $item['Id'] . 
                            ' AND IsHidden=0' . 
                            ' ORDER BY OrderId' . 
                            ' LIMIT ' . $childCount . ')';
                
            }
            $children = $this->db->getItems(implode(' UNION ', $sqls));
            foreach ($items as &$item) {
                $item['Children'] = array();
                foreach ($children as $child) {
                    if ($child['ParentId'] == $item['Id']) {
                        $item['Children'][] = $child;
                    }
                }
            }
            unset($item);
            unset($children);
        }
        return $items;
    }
    
    /**
     * @return array|null
     */
    public function getCategoryParents()
    {
        $category = $this->getCategory($this->moduleParams['categoryId']);
        if (null === $category) {
            return null;
        }
        $cpl = $this->maskCharsPerLevel;
        $masks = array();
        for ($i = 0, $end = $category['LevelId']; $i < $end; $i++) {
            $masks[] = (0 < $i ? $masks[$i - 1] : '') . substr($category['Mask'], $i * $cpl, $cpl);
        }
        $sql =  'SELECT *, (Id=' . $this->moduleParams['categoryId'] . ') AS Current' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'shop_categories' . 
                " WHERE Mask IN('" . implode("','", $masks) . "')" . 
                ' AND IsHidden=0' . 
                ' ORDER BY Mask';
        return $this->db->getItems($sql);
    }
    
    /**
     * @param int $categoryId
     * @return array|null
     */
    public function getCategorySiblings($categoryId)
    {
        $sql =  'SELECT *, (Id=' . $categoryId . ') AS Current' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'shop_categories' . 
                ' WHERE ParentId=(' . 
                    'SELECT ParentId' . 
                    ' FROM ' . C_DB_TABLE_PREFIX . 'shop_categories' . 
                    ' WHERE Id=' . $categoryId . 
                ')' . 
                ' AND IsHidden=0' . 
                ' ORDER BY OrderId';
        return $this->db->getItems($sql);
    }
    
    /**
     * @param int $categoryId
     * @return array|null
     */
    public function getCategoryChildren($categoryId)
    {
        $sql =  'SELECT *' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'shop_categories' . 
                ' WHERE ParentId=' . $categoryId . 
                ' AND IsHidden=0' . 
                ' ORDER BY OrderId';
        return $this->db->getItems($sql);
    }
    
    /**
     * @param int $categoryId
     * @return array|null
     */
    public function getCategory($categoryId) {
        if (0 == $categoryId) {
            return null;
        }
        $sql =  'SELECT *' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'shop_categories' . 
                ' WHERE Id=' . $categoryId . 
                ' AND IsHidden=0';
        return $this->db->getItem($sql);
    }
    
    /**
     * @param string $url
     * @return string
     */
    protected function getClearedUrl($url) {
        return preg_replace('/[^A-Za-z0-9~_\/\-]|(\/{2})/i', '', $url);
    }
    
    /**
     * @param string $url
     * @return string
     */
    protected function getClearedAlias($url) {
        return preg_replace('/[^A-Za-z0-9~_\-]/i', '', $url);
    }
    
    /**
     *  Возвращает идентификатор категории по переданному значению алиаса или идентификатора.
     *  @param mixed $value     category alias or id
     *  @return int|null
     */
    protected function getCategoryIdByValue($value = null) {
        if (is_null($value)) {
            return $this->moduleParams['categoryId'];
        } else if (is_string($value) && $value == $this->getClearedAlias($value)) {
            return $this->getCategoryIdByAlias($value);
        } else if (is_int($value)) {
            return (int) $value; //на всякий случай дополнительно делаем кастинг
        } else {
            return null;
        }
    }
    
    /**
     * @param string $categoryAlias
     * @param string $itemAlias
     * @return array<int|null, int|null>
     */
    protected function getCategoryItemIdsByAlias($categoryAlias, $itemAlias) {
        $sql =  'SELECT Id' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'shop_items' . 
                ' WHERE SectionId=' . $this->params->sectionId . 
                ' AND IsHidden=0' . 
                " AND Alias='" . $this->db->addEscape($itemAlias) . "'";
        return array($this->getCategoryIdByAlias($categoryAlias), $this->db->getValue($sql, 'Id'));
    }
    
    /**
     * @param string $alias
     * @return int|null
     */
    protected function getCategoryIdByAlias($alias) {
        $sql =  'SELECT Id' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'shop_categories' . 
                ' WHERE SectionId=' . $this->params->sectionId . 
                ' AND IsHidden=0' . 
                " AND Alias='" . $this->db->addEscape($alias) . "'";
        return $this->db->getValue($sql, 'Id');
    }
    
    /**
     * @param int $itemId
     * @return string
     */
    protected function getItemAlias($itemId)
    {
        $sql =  'SELECT Alias' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'shop_items' . 
                ' WHERE IsHidden=0' . 
                ' AND Id=' . $itemId;
        return $this->db->getValue($sql, 'Alias');
    }
    
    /**
     * @param int $itemId
     * @return string
     */
    protected function getItemCategoryAlias($itemId)
    {
        $sql =  'SELECT c.Alias' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'shop_categories AS c' . 
                ' INNER JOIN ' . C_DB_TABLE_PREFIX . 'shop_items AS i ON c.Id=i.CategoryId' . 
                ' WHERE c.IsHidden=0 AND i.IsHidden=0' . 
                ' AND i.Id=' . $itemId;
        return $this->db->getValue($sql, 'Alias');
    }
}
