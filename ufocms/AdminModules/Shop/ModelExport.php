<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\Shop;

/**
 * News module model class
 */
class ModelExport extends \Ufocms\AdminModules\Model
{
    const ERR_CATEGORY_UNSET = 'Category is unset';
    
    /**
     * @see parent
     */
    protected function init()
    {
        parent::init();
        $this->itemsTable = 'shop_items';
        $this->itemDisabledField = '';
        $this->canCreateItems = false;
        $this->canDeleteItems = false;
        $categoryId = isset($_GET['categoryid']) ? (int) $_GET['categoryid'] : 0;
        if (0 == $categoryId) {
            throw new \Exception(self::ERR_CATEGORY_UNSET);
        }
        $this->primaryFilter .= ' AND CategoryId=' . $categoryId;
    }
    
    protected function setFields()
    {
        $this->fields = array(
            array('Type' => 'int',          'Name' => 'Id',             'Value' => 0,   'Title' => 'id',                'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => false),
            array('Type' => 'text',         'Name' => 'Title',          'Value' => '',  'Title' => 'Заголовок',         'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true,     'Required' => true),
            array('Type' => 'image',        'Name' => 'Thumbnail',      'Value' => '',  'Title' => 'Картинка мал.',     'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'image',        'Name' => 'Image',          'Value' => '',  'Title' => 'Картинка бол.',     'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'mediumtext',   'Name' => 'ShortDesc',      'Value' => '',  'Title' => 'Кратк. текст',      'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'bigtext',      'Name' => 'FullDesc',       'Value' => '',  'Title' => 'Полн. текст',       'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'float',        'Name' => 'Price',          'Value' => 0,   'Title' => 'Цена',              'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
        );
    }
    
    protected function getItemsSqlLimits()
    {
        return  ' LIMIT 1000';
    }
}
