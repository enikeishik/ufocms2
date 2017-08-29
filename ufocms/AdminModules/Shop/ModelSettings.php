<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\Shop;

/**
 * News module model class
 */
class ModelSettings extends \Ufocms\AdminModules\Model
{
    /**
     * @see parent
     */
    protected function init()
    {
        parent::init();
        $this->itemsTable = 'shop_sections';
        $this->itemDisabledField = '';
        $this->canCreateItems = false;
        $this->canDeleteItems = false;
        $this->params->itemId = $this->getItemIdBySectionId();
    }
    
    protected function setFields()
    {
        $this->fields = array(
            array('Type' => 'int',          'Name' => 'Id',                         'Value' => 0,       'Title' => 'id',                        'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => false),
            array('Type' => 'int',          'Name' => 'SectionId',                  'Value' => 0,       'Title' => 'Раздел',                    'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => false),
            array('Type' => 'bigtext',      'Name' => 'BodyHead',                   'Value' => '',      'Title' => 'Текст перед',               'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'bigtext',      'Name' => 'BodyFoot',                   'Value' => '',      'Title' => 'Текст после',               'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'int',          'Name' => 'PageLength',                 'Value' => 0,       'Title' => 'Записей на страницу',       'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'list',         'Name' => 'Orderby',                    'Value' => 0,       'Title' => 'Сортировка',                'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true,     'Items' => 'getOrders'),
            array('Type' => 'bool',         'Name' => 'InheritMeta',                'Value' => false,   'Title' => 'Наследовать мета тэги',     'Filter' => true,   'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'text',         'Name' => 'ThumbnailAttributes',        'Value' => '',      'Title' => 'Атрибуты картинки анонса',  'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'text',         'Name' => 'InsThumbnailAttributes',     'Value' => '',      'Title' => 'Атрибуты картинки вставки', 'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
        );
    }
    
    protected function getOrders()
    {
        return  array(
            array('Value' => 0, 'Title' => 'По порядку по возрастанию'),
            array('Value' => 1, 'Title' => 'По порядку по убыванию'),
            array('Value' => 2, 'Title' => 'По названию по возрастанию'),
            array('Value' => 3, 'Title' => 'По названию по убыванию'),
            array('Value' => 4, 'Title' => 'По дате по возрастанию'),
            array('Value' => 5, 'Title' => 'По дате по убыванию'),
            array('Value' => 6, 'Title' => 'По цене по возрастанию'),
            array('Value' => 7, 'Title' => 'По цене по убыванию'),
        );
    }
}
