<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\Shop;

/**
 * News module model class
 */
class Model extends \Ufocms\AdminModules\Model
{
    use Statistic, Aliases;
    
    /**
     * @var ModelCategories
     */
    protected $categories = null;
    
    /**
     * @see parent
     */
    protected function init()
    {
        parent::init();
        $this->itemsTable = 'shop_items';
        $this->itemDisabledField = 'IsHidden';
        $this->defaultSort = 'OrderNumber';
    }
    
    protected function setItems()
    {
        $section = $this->core->getSection();
        $sectionPath = $section['path'];
        unset($section);
        parent::setItems();
        foreach ($this->items as &$item) {
            $item['path'] = $sectionPath . $item['Id'];
        }
        unset($item);
    }
    
    protected function setFields()
    {
        $this->fields = array(
            array('Type' => 'int',          'Name' => 'Id',             'Value' => 0,                           'Title' => 'id',                'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => false),
            array('Type' => 'list',         'Name' => 'SectionId',      'Value' => $this->params->sectionId,    'Title' => 'Раздел',            'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => true,     'Required' => true,     'Items' => 'getSections'),
            array('Type' => 'list',         'Name' => 'CategoryId',     'Value' => 0,                           'Title' => 'Категория',         'Filter' => true,   'Show' => true,     'Sort' => false,    'Edit' => true,     'Required' => true,     'Items' => 'getCategories'),
            array('Type' => 'list',         'Name' => 'RelatedInfoId',  'Value' => 0,                           'Title' => 'Инф. раздел',       'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => false,    'Required' => true,     'Items' => 'getRelated'),
            array('Type' => 'int',          'Name' => 'OrderNumber',    'Value' => 0,                           'Title' => 'Порядок',           'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => true),
            array('Type' => 'datetime',     'Name' => 'DateCreate',     'Value' => date('Y-m-d H:i:s'),         'Title' => 'Создано',           'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => true,     'Required' => true,     'Class' => 'small'),
            array('Type' => 'text',         'Name' => 'Alias',          'Value' => '',                          'Title' => 'URL',               'Filter' => true,   'Show' => true,     'Sort' => true,     'Edit' => true,     'Required' => true),
            /* deprecated array('Type' => 'text',         'Name' => 'Caption',        'Value' => '',                          'Title' => 'Название',          'Filter' => true,   'Show' => true,     'Sort' => true,     'Edit' => true,     'Required' => true), */
            array('Type' => 'text',         'Name' => 'Title',          'Value' => '',                          'Title' => 'Заголовок',         'Filter' => true,   'Show' => true,     'Sort' => true,     'Edit' => true,     'Required' => true,     'Autofocus' => true),
            array('Type' => 'text',         'Name' => 'MetaKeys',       'Value' => '',                          'Title' => 'Ключевые слова',    'Filter' => false,  'Show' => false,    'Sort' => true,     'Edit' => true),
            array('Type' => 'text',         'Name' => 'MetaDesc',       'Value' => '',                          'Title' => 'SEO описание',      'Filter' => false,  'Show' => false,    'Sort' => true,     'Edit' => true),
            array('Type' => 'image',        'Name' => 'Thumbnail',      'Value' => '',                          'Title' => 'Картинка мал.',     'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => true),
            array('Type' => 'image',        'Name' => 'Image',          'Value' => '',                          'Title' => 'Картинка бол.',     'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => true),
            array('Type' => 'mediumtext',   'Name' => 'ShortDesc',      'Value' => '',                          'Title' => 'Кратк. текст',      'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => true),
            array('Type' => 'bigtext',      'Name' => 'FullDesc',       'Value' => '',                          'Title' => 'Полн. текст',       'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => true),
            array('Type' => 'float',        'Name' => 'Price',          'Value' => 0,                           'Title' => 'Цена',              'Filter' => true,   'Show' => true,     'Sort' => true,     'Edit' => true),
            array('Type' => 'int',          'Name' => 'ViewedCnt',      'Value' => 0,                           'Title' => 'Просмотров',        'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => false),
            array('Type' => 'bool',         'Name' => 'IsHidden',       'Value' => false,                       'Title' => 'Скрыто',            'Filter' => true,   'Show' => false,    'Sort' => false,    'Edit' => true,     'Items' => array(array('Value' => 1, 'Title' => 'Скрыто'), array('Value' => 0, 'Title' => 'Открыто'))),
        );
    }
    
    protected function getCategories()
    {
        if (null === $this->categories) {
            $container = $this->core->getContainer([
                'debug'     => &$this->debug, 
                'config'    => &$this->config, 
                'params'    => &$this->params, 
                'db'        => &$this->db, 
                'core'      => &$this->core, 
            ]);
            $this->categories = new ModelCategories($container);
        }
        return $this->categories->getCategories();
    }
    
    protected function getRelated()
    {
        return array();
    }
    
    /**
     * @param int $categoryId
     * @return string
     */
    public function getAliases($categoryId)
    {
        $sql =  'SELECT Alias FROM ' . C_DB_TABLE_PREFIX . 'shop_items' . 
                ' WHERE CategoryId=' . $categoryId;
        if (0 != $this->params->itemId) {
            $sql .= ' AND Id!=' . $this->params->itemId;
        }
        return $this->db->getValues($sql, 'Alias');
    }
    
    /**
     * @see parent
     */
    protected function getFormFieldData(array $field)
    {
        if ('Alias' == $field['Name']) {
            $aliases = $this->getAliases((int) $_POST['CategoryId']);
            $alias = $this->getUnicAlias($this->getAliasFromText($_POST['Alias']), $aliases);
            unset($aliases);
            return array('Type' => $field['Type'], 'Value' => $alias);
        }
        return parent::getFormFieldData($field);
    }
    
    /**
     * @see parent
     */
    protected function actionAfterAll()
    {
        return $this->statisticUpdate();
    }
}
