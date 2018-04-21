<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\CoreRoles;

/**
 * Core [admin] roles permissions for section modules model class
 */
class ModelPermsMods extends ModelBase
{
    /**
     * @see parent
     */
    protected function init()
    {
        parent::init();
        $this->itemsTable = 'users_roles_perms_mods';
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
            array('Type' => 'list',         'Name' => 'ModuleId',       'Value' => 0,               'Title' => 'Модуль раздела',    'Filter' => true,   'Show' => true,     'Sort' => true,     'Edit' => true,     'Unchange' => true,             'Items' => 'getModules'),
            array('Type' => 'subform',      'Name' => 'Permissions',    'Value' => '',              'Title' => 'Разрешения',        'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true,     'Schema' => 'getPermissions',   'Class' => 'small'),
        );
    }
    
    protected function getModules()
    {
        $sql =  'SELECT muid AS Value, mname AS Title' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'modules' . 
                ' ORDER BY mname';
        return array_merge(
            [['Value' => 0, 'Title' => 'Все модули']], 
            $this->db->getItems($sql) ?: []
        );
    }
    
    protected function getPermissions()
    {
        $container = $this->core->getContainer([]);
        return new SchemaPermissions($container);
    }
}
