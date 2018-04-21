<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\CoreRoles;

/**
 * Core [admin] roles mechanism model class
 */
class Model extends \Ufocms\AdminModules\Model
{
    /**
     * @see parent
     */
    protected function init()
    {
        parent::init();
        $this->itemsTable = 'users_roles';
        $this->itemIdField = 'Id';
        $this->itemDisabledField = '';
        $this->primaryFilter = '';
        $this->defaultSort = 'Id';
    }
    
    /**
     * @see parent
     */
    protected function setFields()
    {
        $this->fields = array(
            array('Type' => 'int',          'Name' => 'Id',             'Value' => 0,       'Title' => 'id',            'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => false),
            array('Type' => 'bool',         'Name' => 'IsSystem',       'Value' => false,   'Title' => 'Встроенная',    'Filter' => true,   'Show' => true,     'Sort' => true,     'Edit' => false,    'Items' => array(array('Value' => '0', 'Title' => 'Созданная'), array('Value' => '1', 'Title' => 'Встроенная'))),
            array('Type' => 'text',         'Name' => 'Title',          'Value' => '',      'Title' => 'Название',      'Filter' => true,   'Show' => true,     'Sort' => true,     'Edit' => true,     'Required' => true),
            array('Type' => 'mediumtext',   'Name' => 'Description',    'Value' => '',      'Title' => 'Описание',      'Filter' => true,   'Show' => true,     'Sort' => false,    'Edit' => true),
        );
    }
    
    /**
     * @see parent
     */
    protected function checkBeforeInsert()
    {
        $item = $this->getItem();
        if ($item['IsSystem']) {
            return false;
        }
        return true;
    }
    
    /**
     * @see parent
     */
    protected function checkBeforeUpdate()
    {
        $item = $this->getItem();
        if ($item['IsSystem']) {
            return false;
        }
        return true;
    }
    
    /**
     * @see parent
     */
    protected function checkBeforeDelete()
    {
        $item = $this->getItem();
        if ($item['IsSystem']) {
            return false;
        }
        return true;
    }
}
