<?php
/**
 * UFOCMS v2 Content Management System
 * 
 * @copyright   Copyright (C) 2005 - 2017 Enikeishik <enikeishik@gmail.com>. All rights reserved.
 * @author      Enikeishik <enikeishik@gmail.com>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Ufocms\Backend;

/**
 * Users roles functionality
 */
class Roles
{
    /**
     * Роль без ограничений и со всеми разрешениями, не требует проверки.
     * @const int
     */
    const ALLOW_ALL_ROLE_ID = 1;
    
    /**
     * Действие по-умолчанию.
     * @const string
     */
    const ACTION_DEFAULT = 'edit';
    
    /**
     * Действие для публикации.
     * @const string
     */
    const ACTION_PUBLISH = 'enable';
    
    /**
     * Значение, указывающее отсутствие ограничений (на модули ядра/модули/разделы).
     * @const string
     */
    const RESTRICT_NOTHING_VALUE = '';
    
    /**
     * Значение, указывающее полное ограничение (на модули ядра/модули/разделы).
     * @const string
     */
    const RESTRICT_ALL_VALUE = 'none';
    
    /**
     * Префикс названий модулей ядра (чтобы обрезать его).
     * @const string
     */
    const RESTR_COREMODULE_PREFIX = 'Core';
    
    /**
     * Calculated in constructor strlen(self::RESTR_COREMODULE_PREFIX)
     * @var int
     */
    protected $coremodulePrefixLen = 4;
    
    /**
     * Допустимые действия.
     * @var array<string canonicAction => array actions>
     */
    protected $actions = [
        'create'    => ['create', 'add', 'new', 'insert', 'import'], 
        'edit'      => ['edit', 'update'], 
        'disable'   => ['disable', 'off', 'hide'], 
        'enable'    => ['enable', 'on', 'show'], 
        'delete'    => ['delete', 'del', 'delconfirm', 'remove'], 
    ];
    
    /**
     * @var Debug
     */
    protected $debug = null;
    
    /**
     * @var Config
     */
    protected $config = null;
    
    /**
     * @var Db
     */
    protected $db = null;
    
    /**
     * @var array
     */
    protected $restrictions = null;
    
    /**
     * @var array
     */
    protected $corePermissions = null;
    
    /**
     * @var array
     */
    protected $modulePermissions = null;
    
    /**
     * Конструктор.
     * @param Config &$config
     * @param Db &$db
     * @param Debug &$debug = null
     */
    public function __construct(&$config, &$db, &$debug = null)
    {
        $this->config   =& $config;
        $this->debug    =& $debug;
        $this->db       =& $db;
        $this->coremodulePrefixLen = strlen(self::RESTR_COREMODULE_PREFIX);
    }
    
    /**
     * @param int $userId
     * @return array<int>
     */
    protected function getRolesIds($userId)
    {
        static $roles = array();
        if (isset($roles[$userId])) {
            return $roles[$userId];
        }
        
        $sql =  'SELECT RoleId' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'users_roles_relations' . 
                ' WHERE UserId=' . $userId;
        $roles[$userId] = $this->db->getValues($sql, 'RoleId');
        if (null === $roles[$userId]) {
            $roles[$userId] = array();
        }
        return $roles[$userId];
    }
    
    /**
     * @param array<int> $rolesIds
     * @return array
     */
    protected function getRolesRestrictions(array $rolesIds)
    {
        if (0 == count($rolesIds)) {
            return $this->getRoleRestrictionsEmpty();
        }
        
        if (null !== $this->restrictions) {
            return $this->restrictions;
        }
        
        $sql =  'SELECT CoreModules, Modules, Sections' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'users_roles_restrictions' . 
                ' WHERE RoleId IN (' . implode(',', $rolesIds) . ')';
        $dbRestrictions = $this->db->getItems($sql);
        if (null === $dbRestrictions || 0 == count($dbRestrictions)) {
            return $this->getRoleRestrictionsEmpty();
        }
        
        $coreModules = [];
        $modules = [];
        $sections = [];
        foreach ($dbRestrictions as $roleRestrictions) {
            $coreModules = array_merge($coreModules, ('' != $roleRestrictions['CoreModules'] ? explode(',', $roleRestrictions['CoreModules']) : [self::RESTRICT_NOTHING_VALUE]));
            $modules = array_merge($modules, ('' != $roleRestrictions['Modules'] ? explode(',', $roleRestrictions['Modules']) : [self::RESTRICT_NOTHING_VALUE]));
            $sections = array_merge($sections, ('' != $roleRestrictions['Sections'] ? explode(',', $roleRestrictions['Sections']) : [self::RESTRICT_NOTHING_VALUE]));
        }
        
        if (in_array(self::RESTRICT_NOTHING_VALUE, $coreModules)) {
            $coreModules = [];
        }
        if (in_array(self::RESTRICT_NOTHING_VALUE, $modules)) {
            $modules = [];
        }
        if (in_array(self::RESTRICT_NOTHING_VALUE, $sections)) {
            $sections = [];
        }
        
        $this->restrictions = [
            'CoreModules' => array_unique($coreModules), 
            'Modules' => array_unique($modules), 
            'Sections' => array_unique($sections), 
        ];
        
        return $this->restrictions;
    }
    
    /**
     * @return array
     */
    protected function getRoleRestrictionsEmpty()
    {
        return [
            'CoreModules' => [], 
            'Modules' => [], 
            'Sections' => [], 
        ];
    }
    
    /**
     * @param array<int> $rolesIds
     * @return array
     */
    protected function getRolesCorePermissions(array $rolesIds)
    {
        if (0 == count($rolesIds)) {
            return array();
        }
        
        if (null !== $this->corePermissions) {
            return $this->corePermissions;
        }
        
        $sql =  'SELECT CoreModule AS Module, Permissions' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'users_roles_perms_core' . 
                ' WHERE RoleId IN (' . implode(',', $rolesIds) . ')';
        $this->corePermissions = $this->db->getItems($sql);
        if (null === $this->corePermissions) {
            return array();
        }
        
        return $this->corePermissions;
    }
    
    /**
     * @param array<int> $rolesIds
     * @return array
     */
    protected function getRolesModulesPermissions(array $rolesIds)
    {
        if (0 == count($rolesIds)) {
            return array();
        }
        
        if (null !== $this->modulePermissions) {
            return $this->modulePermissions;
        }
        
        $sql =  'SELECT ModuleId AS Module, Permissions' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'users_roles_perms_mods' . 
                ' WHERE RoleId IN (' . implode(',', $rolesIds) . ')';
        $this->modulePermissions = $this->db->getItems($sql);
        if (null === $this->modulePermissions) {
            return array();
        }
        
        return $this->modulePermissions;
    }
    
    /**
     * @param string $action
     * @return string
     */
    protected function getAction($action)
    {
        foreach ($this->actions as $canonicAction => $actions) {
            if (in_array($action, $actions)) {
                return $canonicAction;
            }
        }
        
        return self::ACTION_DEFAULT;
    }
    
    /**
     * @param int $userId
     * @param string|int $module
     * @return bool
     */
    public function isPublishRestricted($userId, $module)
    {
        return !$this->rolesPermittedAction($userId, $module, self::ACTION_PUBLISH);
    }
    
    /**
     * @param int $userId
     * @return array
     */
    public function getUserRestrictions($userId)
    {
        $rolesIds = $this->getRolesIds($userId);
        if (in_array(self::ALLOW_ALL_ROLE_ID, $rolesIds)) {
            return $this->getRoleRestrictionsEmpty();
        }
        return $this->getRolesRestrictions($rolesIds);
    }
    
    /**
     * @param int $userId
     * @param string|int $module
     * @param int $sectionId = null
     * @return bool
     */
    public function rolesRestricted($userId, $module, $sectionId = null)
    {
        $rolesIds = $this->getRolesIds($userId);
        if (in_array(self::ALLOW_ALL_ROLE_ID, $rolesIds)) {
            return false;
        }
        
        $restrictions = $this->getRolesRestrictions($rolesIds);
        
        if (is_string($module)) {
            if (0 == count($restrictions['CoreModules'])) {
                return false;
            }
            
            if (0 === strpos($module, self::RESTR_COREMODULE_PREFIX)) {
                $module = strtolower(substr($module, $this->coremodulePrefixLen));
            } else {
                $module = strtolower($module);
            }
            
            return !in_array($module, $restrictions['CoreModules']);
            
        } else if (is_int($module)) {
            $modulesRestricted = false;
            if (0 < count($restrictions['Modules'])) {
                $modulesRestricted = !in_array($module, $restrictions['Modules']);
            }
            
            $sectionsRestricted = false;
            if (null !== $sectionId && 0 < count($restrictions['Sections'])) {
                $sectionsRestricted = !in_array($sectionId, $restrictions['Sections']);
            }
            
            return $modulesRestricted || $sectionsRestricted;
            
        } else {
            //throws \Exception
            return true;
        }
    }
    
    /**
     * @param int $userId
     * @param string|int $module
     * @param string $action
     * @param bool $own
     * @return bool
     */
    public function rolesPermittedAction($userId, $module, $action)
    {
        $rolesIds = $this->getRolesIds($userId);
        if (in_array(self::ALLOW_ALL_ROLE_ID, $rolesIds)) {
            return true;
        }
        
        $action = $this->getAction($action);
        
        if (is_string($module)) {
            $allModules = '';
            $permissions = $this->getRolesCorePermissions($rolesIds);
            if (0 === strpos($module, self::RESTR_COREMODULE_PREFIX)) {
                $module = strtolower(substr($module, $this->coremodulePrefixLen));
            } else {
                $module = strtolower($module);
            }
        } else if (is_int($module)) {
            $allModules = 0;
            $permissions = $this->getRolesModulesPermissions($rolesIds);
        } else {
            //throws \Exception
            return false;
        }
        
        foreach ($permissions as $permission) {
            $schema = json_decode($permission['Permissions'], true);
            if ($allModules == $permission['Module'] || $module == $permission['Module']) {
                if (isset($schema[$action]) && $schema[$action]) {
                    return true;
                }
            }
        }
        
        return false;
    }
}
