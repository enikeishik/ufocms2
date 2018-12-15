<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\Shop;

/**
 * News module model class
 */
class ModelCategories extends \Ufocms\AdminModules\Model
{
    use Statistic, Aliases;
    
    /**
     * Mask sybmols per level count
     */
    const MASK_SPL = 2;
    
    /**
     * Mask max nested levels
     */
    const MASK_ML = 5;
    
    /*
     * Errors messages.
     */
    const ERR_MASK_LIMIT_EXCEEDS = 'Max items per level or nesting limit exceeds';
    const ERR_MASK_WRONG = 'Mask length is not multiple of the SPL';
    const ERR_CATEGORY_IS_PARENT = 'Category have nested categories';
    const ERR_CATEGORY_NOT_EMPTY = 'Category not empty';
    const ERR_BORDER_REACHED = 'Item already on border';
    
    /**
     * @see parent
     */
    protected function init()
    {
        parent::init();
        $this->itemsTable = 'shop_categories';
        $this->itemDisabledField = 'IsHidden';
        $this->defaultSort = 'Mask';
        $this->config->registerAction('up');
        $this->config->registerMakeAction('up');
        $this->config->registerAction('down');
        $this->config->registerMakeAction('down');
        $this->params->actionUnsafe = false;
    }
    
    protected function setItems()
    {
        $section = $this->core->getSection();
        $sectionPath = $section['path'];
        unset($section);
        $sql =  'SELECT c.Id AS itemid, IsHidden AS disabled,' . 
                " CONCAT('" . $sectionPath . "', c.Alias, '/') AS path," . 
                ' LevelId, TemplateId, Title, SelfItemsCount, TotalItemsCount' . 
               ' FROM `' . C_DB_TABLE_PREFIX . 'shop_categories` AS c' . 
               ' WHERE c.SectionId=' . $this->params->sectionId . 
               ' ORDER BY c.Mask';
        $this->items = $this->db->getItems($sql);
        $this->itemsCount = count($this->items);
    }
    
    protected function setFields()
    {
        $this->fields = array(
            array('Type' => 'int',          'Name' => 'Id',             'Value' => 0,                           'Title' => 'id',                'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => false),
            array('Type' => 'list',         'Name' => 'SectionId',      'Value' => $this->params->sectionId,    'Title' => 'Раздел',            'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => true,     'Required' => true, 'Items' => 'getSections',   'Unchange' => true),
            array('Type' => 'list',         'Name' => 'ParentId',       'Value' => 0,                           'Title' => 'Род.категория',     'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => true,     'Required' => true, 'Items' => 'getCategories'),
            array('Type' => 'int',          'Name' => 'TemplateId',     'Value' => 0,                           'Title' => 'Ид. шаблона',       'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => true),
            array('Type' => 'bool',         'Name' => 'IsParent',       'Value' => false,                       'Title' => 'Есть подразделы',   'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => false,),
            array('Type' => 'bool',         'Name' => 'IsHidden',       'Value' => false,                       'Title' => 'Скрыто',            'Filter' => true,   'Show' => false,    'Sort' => false,    'Edit' => true,     'Items' => array(array('Value' => 1, 'Title' => 'Скрыто'), array('Value' => 0, 'Title' => 'Открыто'))),
            array('Type' => 'text',         'Name' => 'Mask',           'Value' => '',                          'Title' => 'Маска',             'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => false),
            array('Type' => 'text',         'Name' => 'Alias',          'Value' => '',                          'Title' => 'Псевдоним (URL)',   'Filter' => true,   'Show' => true,     'Sort' => true,     'Edit' => true,     'Required' => true),
            /* deprecated array('Type' => 'text',         'Name' => 'Caption',        'Value' => '',      'Title' => 'Название',          'Filter' => true,   'Show' => true,     'Sort' => true,     'Edit' => true,     'Required' => true), */
            array('Type' => 'text',         'Name' => 'Title',          'Value' => '',                          'Title' => 'Заголовок',         'Filter' => true,   'Show' => true,     'Sort' => true,     'Edit' => true,     'Required' => true,     'Autofocus' => true),
            array('Type' => 'text',         'Name' => 'MetaKeys',       'Value' => '',                          'Title' => 'Ключевые слова',    'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => false),
            array('Type' => 'text',         'Name' => 'MetaDesc',       'Value' => '',                          'Title' => 'SEO описание',      'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => false),
            array('Type' => 'image',        'Name' => 'Thumbnail',      'Value' => '',                          'Title' => 'Картинка мал.',     'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => true),
            array('Type' => 'image',        'Name' => 'Image',          'Value' => '',                          'Title' => 'Картинка бол.',     'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => true),
            array('Type' => 'bigtext',      'Name' => 'Info',           'Value' => '',                          'Title' => 'Описание',          'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => false),
        );
    }
    
    /**
     * @param int $sectionId = null
     * @return array
     */
    public function getCategories($sectionId = null)
    {
        if (null === $sectionId) {
            $sectionId = $this->params->sectionId;
        }
        $item = array('Id' => 0, 'Level' => 0, 'Title' => '(нет)', 'Parent' => false);
        $sql = 'SELECT Id, LevelId - 1 AS Level, Title, IsParent AS Parent' . 
               ' FROM ' . C_DB_TABLE_PREFIX . 'shop_categories' . 
               ' WHERE SectionId=' . $sectionId . 
               ' ORDER BY Mask';
        $items = $this->db->getItems($sql);
        if (null === $items) {
            return array($item);
        }
        $items = array_merge(array($item), $items);
        foreach ($items as &$item) {
            $item = array(
                'Value'         => $item['Id'], 
                'Title'         => str_pad('', ($item['Level']) * 4, '.', STR_PAD_LEFT) . $item['Title'], 
                'Parent'        => $item['Parent'], 
            );
        }
        unset($item);
        return $items;
    }
    
    /**
     * Gets category data.
     * @param int $categoryId
     * @param string $fields = '*'
     * @return array|null
     */
    public function getCategory($categoryId, $fields = '*')
    {
        $sql =  'SELECT ' . $fields . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'shop_categories' . 
                ' WHERE Id=' . $categoryId;
        return $this->db->getItem($sql);
    }
    
    /**
     * Check is category have nested subcategories.
     * @param int $categoryId
     * @return boolean
     */
    public function isParent($categoryId)
    {
        $sql =  'SELECT EXISTS (SELECT *' . 
                                ' FROM `' . C_DB_TABLE_PREFIX . 'shop_categories`' . 
                                ' WHERE Mask!=c.Mask' . 
                                ' AND Mask LIKE CONCAT(c.Mask, \'%\')) AS Parent' . 
                ' FROM `' . C_DB_TABLE_PREFIX . 'shop_categories` AS c' . 
                ' WHERE c.Id=' . $categoryId;
        return (bool) (int) $this->db->getValue($sql, 'Parent');
    }
    
    /**
     * Calculate max items in each level of hierarchy.
     * @return int
     */
    public function getMaxItemsPerLevel()
    {
        $n = 10;
        for ($i = 1; $i < self::MASK_SPL; $i++) {
            $n *= 10;
        }
        $n -= 1;
        return $n;
    }
    
    /**
     * Calculate nesting level by mask.
     * @param string $mask
     * @return int
     * @throws Exception
     */
    protected function getLevel($mask)
    {
        if (0 != strlen($mask) % self::MASK_SPL) {
            throw new \Exception(self::ERR_MASK_WRONG);
        }
        return strlen($mask) / self::MASK_SPL;
    }
    
    /**
     * Gets mask of the category.
     * @param int $id
     * @return string
     */
    protected function getMask($id)
    {
        if (0 == $id) {
            return '';
        }
        $sql = 'SELECT Mask FROM ' . C_DB_TABLE_PREFIX . 'shop_categories WHERE Id=' . $id;
        $mask = $this->db->getValue($sql, 'Mask');
        if (!is_null($mask)) {
            return $mask;
        } else {
            return '';
        }
    }
    
    /**
     * Generate mask by parent and siblings
     * @param int $parentId
     * @return string
     * @throws Exception
     */
    protected function getGeneratedMask($parentId)
    {
        $siblingsCount = $this->getChildrenCount($parentId);
        if ($siblingsCount >= $this->getMaxItemsPerLevel()) {
            throw new \Exception(self::ERR_MASK_LIMIT_EXCEEDS);
        }
        
        $mask = $this->getMask($parentId) . 
                str_pad(++$siblingsCount, self::MASK_SPL, '0', STR_PAD_LEFT);
        if ($this->getLevel($mask) > self::MASK_ML) {
            throw new \Exception(self::ERR_MASK_LIMIT_EXCEEDS);
        }
        
        return $mask;
    }
    
    /**
     * Calculate count of nested subcategories.
     * @param int $categoryId
     * @return int
     */
    protected function getChildrenCount($categoryId) {
        if (0 != $categoryId) {
            $sql =  'SELECT COUNT(*) AS Cnt' . 
                    ' FROM ' . C_DB_TABLE_PREFIX . 'shop_categories' . 
                    ' WHERE Mask LIKE CONCAT((SELECT Mask' . 
                                                ' FROM ' . C_DB_TABLE_PREFIX . 'shop_categories' . 
                                                " WHERE Id=" . $categoryId . "), '%')" . 
                    ' AND LENGTH(Mask)=(SELECT LENGTH(Mask)' . 
                                                ' FROM ' . C_DB_TABLE_PREFIX . 'shop_categories' . 
                                                ' WHERE Id=' . $categoryId . ')+' . self::MASK_SPL;
        } else {
            $sql =  'SELECT COUNT(*) AS Cnt' . 
                    ' FROM ' . C_DB_TABLE_PREFIX . 'shop_categories' . 
                    ' WHERE LENGTH(Mask)=' . self::MASK_SPL;
        }
        return $this->db->getValue($sql, 'Cnt');
    }
    
    /**
     * Gets aliases of all categories (in the same section).
     * @return array
     */
    public function getAliases() {
        $sql =  'SELECT Alias FROM ' . C_DB_TABLE_PREFIX . 'shop_categories' . 
                ' WHERE SectionId=' . $this->params->sectionId;
        return $this->db->getValues($sql, 'Alias');
    }
    
    /**
     * Gets alias of the category.
     * @param int $categoryId
     * @return string
     */
    public function getAlias($categoryId) {
        $sql =  'SELECT Alias FROM ' . C_DB_TABLE_PREFIX . 'shop_categories' . 
                ' WHERE Id=' . $categoryId;
        return $this->db->getValue($sql, 'Alias');
    }
    
    protected function getInsertSql()
    {
        $parentId = (int) $_POST['ParentId'];
        $mask = $this->getGeneratedMask($parentId);
        $maskLength = strlen($mask);
        
        $aliases = $this->getAliases();
        $alias = $this->getUnicAlias(
            $this->getAliasFromText($_POST['Alias'] ? $_POST['Alias'] : $_POST['Title']), 
            $aliases
        );
        unset($aliases);
        
        //TopId will be set in actionAfterInsert
        $sql =  'INSERT INTO ' . C_DB_TABLE_PREFIX . 'shop_categories' . 
                ' (SectionId, TopId, ParentId, OrderId, LevelId,' . 
                ' TemplateId, IsHidden, Mask, Alias, Title, Thumbnail, Image)' . 
                ' VALUES(' . $this->params->sectionId . ', ' . 
                '0, ' . 
                $parentId . ', ' . 
                substr($mask, $maskLength - self::MASK_SPL + 1) . ', ' . 
                ($maskLength / self::MASK_SPL) . ', ' . 
                (int) $_POST['TemplateId'] . ', ' . 
                (isset($_POST['IsHidden']) ? (int) $_POST['IsHidden'] : '0') . ', ' . 
                "'" . $mask . "', " . 
                "'" . $alias . "', " . 
                "'" . $this->db->addEscape($_POST['Title']) . "'," . 
                "'" . $this->db->addEscape($_POST['Thumbnail']) . "'," . 
                "'" . $this->db->addEscape($_POST['Image']) . "'" . 
                ')';
        return $sql;
    }
    
    protected function getUpdateSql()
    {
        $item = $this->getItem();
        
        if ($_POST['Alias'] != $item['Alias']) {
            $aliases = $this->getAliases();
            $alias = $this->getUnicAlias(
                $this->getAliasFromText($_POST['Alias'] ? $_POST['Alias'] : $_POST['Title']), 
                $aliases
            );
            unset($aliases);
        } else {
            $alias = '';
        }
        
        $changeParentSql = '';
        $changeMaskSql = '';
        if ($_POST['ParentId'] != $item['ParentId']) {
            //for now change nesting (mask) only available if have not children
            //no need to change TopId, OrderId, LevelId here, it will be updated with statistics
            if (!$this->isParent($this->params->itemId)) {
                $parentId = (int) $_POST['ParentId'];
                $mask = $this->getGeneratedMask($parentId);
                $changeParentSql = ' ParentId=' . $parentId . ', ';
                $changeMaskSql = " Mask='" . $mask . "', ";
            }
        }
        
        $sql =  'UPDATE ' . C_DB_TABLE_PREFIX . 'shop_categories' . 
                ' SET' . $changeParentSql . 
                ' TemplateId=' . (int) $_POST['TemplateId'] . ', ' . 
                $changeMaskSql . 
                ('' != $alias ? " Alias='" . $alias . "', " : '') . 
                " Title='" . $this->db->addEscape($_POST['Title']) . "', " . 
                " Thumbnail='" . $this->db->addEscape($_POST['Thumbnail']) . "', " . 
                " Image='" . $this->db->addEscape($_POST['Image']) . "', " . 
                ' IsHidden=' . (isset($_POST['IsHidden']) ? (int) $_POST['IsHidden'] : '0') . ' ' . 
                ' WHERE Id=' . $this->params->itemId;
        return $sql;
    }
    
    /**
     * @param int $categoryId
     * @return int
     */
    protected function getCategoryItemsCount($categoryId) {
        $sql =  'SELECT COUNT(*) AS Cnt' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'shop_items' . 
                ' WHERE CategoryId=' . $categoryId;
        return $this->db->getValue($sql, 'Cnt');
    }
    
    /**
     * Reset masks for all categories.
     */
    protected function resetMasks() {
        $sql =  'SELECT Id, Mask FROM ' . C_DB_TABLE_PREFIX . 'shop_categories' . 
                ' ORDER BY Mask';
        $items = $this->db->getItems($sql);
        $masks = array();
        for ($i = 1; $i <= self::MASK_ML; $i++) {
            $masks[$i] = 0;
        }
        
        $sqls = array();
        $level_ = 0;
        foreach ($items as $item) {
            $level = $this->getLevel($item['Mask']);
            if ($level_ > $level) {
                for ($i = $level_; $i > $level; $i--) {
                    $masks[$i] = 0;
                }
            }
            $mask = '';
            for ($i = 1; $i < $level; $i++) {
                $mask .= str_pad($masks[$i], self::MASK_SPL, '0', STR_PAD_LEFT);
            }
            $mask .= str_pad(++$masks[$level], self::MASK_SPL, '0', STR_PAD_LEFT);
            $sqls[] =   'UPDATE ' . C_DB_TABLE_PREFIX . 'shop_categories' . 
                        " SET Mask='" . $mask . "'" . 
                        ' WHERE Id=' . $item['Id'];
            $level_ = $level;
        }
        
        foreach ($sqls as $sql) {
            if (!$this->db->query($sql)) {
                $this->result = 'Multistep DB error: ' . $this->db->getError();
            }
        }
    }
    
    public function update()
    {
        if (is_null($this->params->itemId)) {
            $this->result = 'Request error: param `itemid` not set';
            return;
        }
        if (is_null($this->fields)) {
            $this->setFields();
        }
        if (0 == $this->params->itemId) {
            $sql = $this->getInsertSql();
        } else {
            $sql = $this->getUpdateSql();
        }
        if ($this->db->query($sql)) {
            $this->result = 'updated';
            if (0 == $this->params->itemId) {
                $this->lastInsertedId = $this->db->getLastInsertedId();
                return $this->actionAfterInsert();
            } else {
                return $this->actionAfterUpdate();
            }
        } else {
            $this->result = 'DB error: ' . $this->db->getError();
        }
    }
    
    protected function actionAfterInsert()
    {
        $sql =  'UPDATE `' . C_DB_TABLE_PREFIX . 'shop_categories` AS c' . 
                ' SET c.TopId=(' . 
                    'SELECT Id FROM (' . 
                        'SELECT Id, Mask' . 
                        ' FROM `' . C_DB_TABLE_PREFIX . 'shop_categories`' . 
                        ' WHERE SectionId=' . $this->params->sectionId . 
                    ') AS tmp WHERE Mask=SUBSTR(c.Mask, 1, ' . self::MASK_SPL . ')' . 
                ')';
        return $this->db->query($sql);
    }
    
    protected function checkBeforeDelete()
    {
        if (0 != $this->getChildrenCount($this->params->itemId)) {
            $this->result = 'Request error: ' . self::ERR_CATEGORY_IS_PARENT;
            return false;
        }
        if (0 != $this->getCategoryItemsCount($this->params->itemId)) {
            $this->result = 'Request error: ' . self::ERR_CATEGORY_NOT_EMPTY;
            return false;
        }
        return true;
    }
    
    protected function actionAfterDelete()
    {
        $this->resetMasks();
        parent::actionAfterDelete();
    }
    
    protected function actionAfterDisable()
    {
        //disable all children
        $category = $this->getCategory($this->params->itemId, 'Mask');
        $sql =  'UPDATE ' . C_DB_TABLE_PREFIX . 'shop_categories' . 
                ' SET IsHidden=1' . 
                " WHERE Mask LIKE '" . $category['Mask'] . "%'";
        $this->db->query($sql);
        
        return $this->actionAfterChange();
    }
    
    protected function actionAfterEnable()
    {
        //enable all parents
        $category = $this->getCategory($this->params->itemId, 'LevelId, Mask');
        $masks = array();
        for ($i = 0, $end = $category['LevelId'] - 1; $i < $end; $i++) {
            $masks[] = (0 < $i ? $masks[$i - 1] : '') . substr($category['Mask'], $i * self::MASK_SPL, self::MASK_SPL);
        }
        $sql =  'UPDATE ' . C_DB_TABLE_PREFIX . 'shop_categories' . 
                ' SET IsHidden=0' . 
                " WHERE Mask IN('" . implode("','", $masks) . "')";
        $this->db->query($sql);
        
        return $this->actionAfterChange();
    }
    
    protected function actionAfterChange()
    {
        return $this->statisticUpdate();
    }
    
    /**
     * Move category up in siblings
     */
    public function up() {
        if (is_null($this->params->itemId)) {
            $this->result = 'Request error: param `itemid` not set';
            return;
        }
        $mask = $this->getMask($this->params->itemId);
        $order = (int) substr($mask, -self::MASK_SPL);
        if ($order <= 1) {
            $this->result = 'Request error: ' . self::ERR_BORDER_REACHED;
            return;
        }
        
        //select another one wich will be used to swap
        $sql =  'SELECT Mask FROM ' . C_DB_TABLE_PREFIX . 'shop_categories' . 
                ' WHERE SectionId=' . $this->params->sectionId . 
                ' AND LENGTH(Mask)=' . strlen($mask) . 
                " AND Mask LIKE '" . substr($mask, 0, -self::MASK_SPL) . "%'" . 
                " AND Mask<'" . $mask . "'" . 
                ' ORDER BY MASK DESC' . 
                ' LIMIT 1';
        $swapMask = $this->db->getValue($sql, 'Mask');
        if (is_null($swapMask)) {
            $this->result = 'Request error: ' . self::ERR_BORDER_REACHED;
            return;
        }
        
        $this->swapMasks($mask, $swapMask);
    }
    
    /**
     * Move category down in siblings
     */
    public function down() {
        if (is_null($this->params->itemId)) {
            $this->result = 'Request error: param `itemid` not set';
            return;
        }
        $mask = $this->getMask($this->params->itemId);
        $order = (int) substr($mask, -self::MASK_SPL);
        if ($order >= $this->getMaxItemsPerLevel()) {
            $this->result = 'Request error: ' . self::ERR_BORDER_REACHED;
            return;
        }
        
        //select another one wich will be used to swap
        $sql =  'SELECT Mask FROM ' . C_DB_TABLE_PREFIX . 'shop_categories' . 
                ' WHERE SectionId=' . $this->params->sectionId . 
                ' AND LENGTH(Mask)=' . strlen($mask) . 
                " AND Mask LIKE '" . substr($mask, 0, -self::MASK_SPL) . "%'" . 
                " AND Mask>'" . $mask . "'" . 
                ' ORDER BY MASK ASC' . 
                ' LIMIT 1';
        $swapMask = $this->db->getValue($sql, 'Mask');
        if (is_null($swapMask)) {
            $this->result = 'Request error: ' . self::ERR_BORDER_REACHED;
            return;
        }
        
        $this->swapMasks($mask, $swapMask);
    }
    
    /**
     * @param string $mask
     * @param string $swapMask
     */
    protected function swapMasks($mask, $swapMask) {
        $sql =  'UPDATE ' . C_DB_TABLE_PREFIX . 'shop_categories' . 
                " SET Mask=CONCAT('-', '" . $swapMask . "', SUBSTRING(Mask, " . (strlen($swapMask) + 1) . "))" . 
                " WHERE Mask LIKE '" . $mask . "%'";
        if (!$this->db->query($sql)) {
            $this->result = 'DB error: ' . $this->db->getError();
            return;
        }
        $sql =  'UPDATE ' . C_DB_TABLE_PREFIX . 'shop_categories' . 
                " SET Mask=CONCAT('" . $mask . "', SUBSTRING(Mask, " . (strlen($mask) + 1) . "))" . 
                " WHERE Mask LIKE '" . $swapMask . "%'";
        if (!$this->db->query($sql)) {
            $this->result = 'DB error: ' . $this->db->getError();
            return;
        }
        $sql =  'UPDATE ' . C_DB_TABLE_PREFIX . 'shop_categories' . 
                ' SET Mask=SUBSTRING(Mask, 2)' . 
                " WHERE Mask LIKE '-%'";
        if (!$this->db->query($sql)) {
            $this->result = 'DB error: ' . $this->db->getError();
            return;
        }
    }
}
