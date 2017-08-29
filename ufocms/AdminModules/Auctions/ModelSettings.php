<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\Auctions;

/**
 * Module settings model class
 */
class ModelSettings extends \Ufocms\AdminModules\Model
{
    /**
     * @see parent
     */
    protected function init()
    {
        parent::init();
        $this->itemsTable = 'auctions_settings';
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
            array('Type' => 'int',          'Name' => 'PageLength',         'Value' => 10,      'Title' => 'Записей на страницу',       'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'int',          'Name' => 'UpdateTimeout',      'Value' => 10,      'Title' => 'Обновление инф-ии, сек.',   'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'list',         'Name' => 'UpdateType',         'Value' => 0,       'Title' => 'Тип обновления',            'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true,     'Items' => 'getUpdateTypes'),
        );
    }
    
    protected function getUpdateTypes()
    {
        return array(
            array('Value' => 0, 'Title' => 'iframe'), 
            array('Value' => 1, 'Title' => 'meta refresh'), 
            array('Value' => 2, 'Title' => 'AJAX'), 
        );
    }
}
