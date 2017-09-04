<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\Tales;

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
        $this->itemsTable = 'tales_sections';
        $this->itemDisabledField = '';
        $this->canCreateItems = false;
        $this->canDeleteItems = false;
        $this->params->itemId = $this->getItemIdBySectionId();
    }
    
    protected function setFields()
    {
        $this->fields = array(
            array('Type' => 'int',          'Name' => 'Id',                 'Value' => 0,       'Title' => 'id',                        'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => false),
            array('Type' => 'int',          'Name' => 'SectionId',          'Value' => 0,       'Title' => 'Раздел',                    'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => false),
            array('Type' => 'bigtext',      'Name' => 'BodyHead',           'Value' => '',      'Title' => 'Текст перед',               'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'bigtext',      'Name' => 'BodyFoot',           'Value' => '',      'Title' => 'Текст после',               'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'text',         'Name' => 'IconAttributes',     'Value' => '',      'Title' => 'Атрибуты картинки анонса',  'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'int',          'Name' => 'AnnounceLength',     'Value' => 0,       'Title' => 'Размер анонса',             'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'int',          'Name' => 'PageLength',         'Value' => 0,       'Title' => 'Записей на страницу',       'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'int',          'Name' => 'RssOutCount',        'Value' => 0,       'Title' => 'Записей в RSS',             'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'list',         'Name' => 'Orderby',            'Value' => 0,       'Title' => 'Сортировка',                'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true,     'Items' => 'getOrders'),
            array('Type' => 'bool',         'Name' => 'InheritMeta',        'Value' => 0,       'Title' => 'Наследование мета тэгов',   'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
        );
    }
    
    protected function getOrders()
    {
        return array(
            array('Value' => '0', 'Title' => 'по сортировке, по возрастанию'), 
            array('Value' => '1', 'Title' => 'по сортировке, по убыванию'), 
            array('Value' => '2', 'Title' => 'по названию, по возрастанию'), 
            array('Value' => '3', 'Title' => 'по названию, по убыванию'), 
            array('Value' => '4', 'Title' => 'по дате, по возрастанию'), 
            array('Value' => '5', 'Title' => 'по дате, по убыванию'), 
            array('Value' => '6', 'Title' => 'по URL, по возрастанию'), 
            array('Value' => '7', 'Title' => 'по URL, по убыванию'), 
            array('Value' => '8', 'Title' => 'по просмотрам, по возрастанию'), 
            array('Value' => '9', 'Title' => 'по просмотрам, по убыванию'), 
        );
    }
}
