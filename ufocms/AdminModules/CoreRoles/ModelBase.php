<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\CoreRoles;

/**
 * Core [admin] roles base model class for restrictions and permissions models
 */
abstract class ModelBase extends \Ufocms\AdminModules\Model
{
    /**
     * @var int
     */
    protected $roleId = null;
    
    /**
     * @see parent
     */
    protected function init()
    {
        $this->roleId = isset($_GET['roleid']) ? (int) $_GET['roleid'] : 0;
        parent::init();
    }
    
    /**
     * Gets non system roles.
     * @return array
     */
    protected function getRoles()
    {
        $sql =  'SELECT Id AS Value, Title' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'users_roles' . 
                ' WHERE IsSystem=0' . 
                ' ORDER BY Id';
        return $this->db->getItems($sql) ?: array();
    }
    
    /**
     * Gets current role.
     * @return string
     */
    public function getRole()
    {
        static $role = null;
        if (null === $role) {
            $sql =  'SELECT *' . 
                    ' FROM ' . C_DB_TABLE_PREFIX . 'users_roles' . 
                    ' WHERE Id=' . $this->roleId;
            $role = $this->db->getItem($sql);
        }
        return $role;
    }
    
    /**
     * @see parent
     */
    protected function checkBeforeInsert()
    {
        $item = $this->getRole();
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
        $item = $this->getRole();
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
        $item = $this->getRole();
        if ($item['IsSystem']) {
            return false;
        }
        return true;
    }
}
