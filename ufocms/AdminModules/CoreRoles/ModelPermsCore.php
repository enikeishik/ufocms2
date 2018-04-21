<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\CoreRoles;

/**
 * Core [admin] roles permissions for core modules model class
 */
class ModelPermsCore extends ModelBase
{
    /**
     * @see parent
     */
    protected function init()
    {
        parent::init();
        $this->itemsTable = 'users_roles_perms_core';
        $this->itemIdField = 'Id';
        $this->itemDisabledField = '';
        $this->primaryFilter = 'RoleId=' . $this->roleId;
        $this->defaultSort = 'Id';
    }
    
    /**
     * @see parent
     */
    protected function setFields()
    {
        $this->fields = array(
            array('Type' => 'int',          'Name' => 'Id',             'Value' => 0,               'Title' => 'id',                'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => false),
            array('Type' => 'list',         'Name' => 'RoleId',         'Value' => $this->roleId,   'Title' => 'Роль',              'Filter' => true,   'Show' => true,     'Sort' => true,     'Edit' => true,     'Unchange' => true,             'Items' => 'getRoles'),
            array('Type' => 'slist',        'Name' => 'CoreModule',     'Value' => '',              'Title' => 'Модуль ядра',       'Filter' => true,   'Show' => true,     'Sort' => true,     'Edit' => true,     'Unchange' => true,             'Items' => 'getCoreModules'),
            array('Type' => 'subform',      'Name' => 'Permissions',    'Value' => '',              'Title' => 'Разрешения',        'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true,     'Schema' => 'getPermissions',   'Class' => 'small'),
        );
    }
    
    protected function getCoreModules()
    {
        $arr = [['Value' => '', 'Title' => 'Все модули']];
        foreach ($this->config->coreModules as $id => $item) {
            $arr[] = ['Value' => $id, 'Title' => strip_tags($item['Title'])];
        }
        return $arr;
    }
    
    protected function getPermissions()
    {
        $container = $this->core->getContainer([]);
        return new SchemaPermissions($container);
    }
}
