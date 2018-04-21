<?php
/**
 * UFOCMS v2 Content Management System
 * 
 * @copyright   Copyright (C) 2005 - 2017 Enikeishik <enikeishik@gmail.com>. All rights reserved.
 * @author      Enikeishik <enikeishik@gmail.com>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Ufocms\Backend;

use Ufocms\Frontend\Debug;
use Ufocms\AdminModules\Model;

/**
 * Core functionality and data
 */
class Core extends \Ufocms\Frontend\Core
{
    /**
     * @var Roles
     */
    protected $roles = null;
    
    /**
     * Get common site settings
     * @return array|null
     */
    public function getSite()
    {
        if (null === $this->site) {
            $sql =  'SELECT *' . 
                    ' FROM ' . C_DB_TABLE_PREFIX . 'siteparams' . 
                    ' ORDER BY POrder';
            $params = $this->db->getItems($sql);
            if (null !== $params) {
                foreach ($params as $param) {
                    $this->site[$param['PName']] = $param;
                }
            }
        }
        return $this->site;
    }
    
    /**
     * Get (current) section information
     * @param int|null $sectionId = null
     * @param string|array|null $fields = null
     * @return array|null
     */
    public function getSection($sectionId = null, $fields = null)
    {
        if (is_null($fields)) {
            $fields = '*';
        } else if (is_array($fields)) {
            $fields = implode(',', $fields);
        }
        if (!is_null($sectionId)) {
            $sql =  'SELECT ' . $fields . 
                    ' FROM ' . C_DB_TABLE_PREFIX . 'sections' . 
                    ' WHERE id=' . $sectionId;
            return $this->db->getItem($sql);
        }
        if (is_null($this->section) 
        && !is_null($this->params->sectionId) 
        && 0 != $this->params->sectionId) {
            $sql =  'SELECT ' . $fields . 
                    ' FROM ' . C_DB_TABLE_PREFIX . 'sections' . 
                    ' WHERE id=' . $this->params->sectionId;
            $this->section = $this->db->getItem($sql);
        }
        return $this->section;
    }
    
    /**
     * Get section module information
     * @param string|array|null $fields = null
     * @return array|null
     */
    public function getModule($fields = null)
    {
        if (is_null($fields)) {
            $fields = '*';
        } else if (is_array($fields)) {
            $fields = implode(',', $fields);
        }
        if (is_null($this->module)) {
            $section = $this->getSection();
            $sql =  'SELECT ' . $fields .
                    ' FROM ' . C_DB_TABLE_PREFIX . 'modules' .
                    ' WHERE isenabled<>0 AND muid=' . $section['moduleid'];
            $this->module = $this->db->getItem($sql);
        }
        return $this->module;
    }
    
    /**
     * Get section module information by module name
     * @param string $moduleName
     * @param string|array|null $fields = null
     * @return array|null
     */
    public function getModuleByName($moduleName, $fields = null)
    {
        static $module = null;
        
        if (null !== $module) {
            return $module;
        }
        
        if (is_null($fields)) {
            $fields = '*';
        } else if (is_array($fields)) {
            $fields = implode(',', $fields);
        }
        
        $sql =  'SELECT ' . $fields .
                ' FROM ' . C_DB_TABLE_PREFIX . 'modules' .
                " WHERE isenabled<>0 AND madmin='mod_" . $this->db->addEscape(strtolower($moduleName)) . "'";
        $module = $this->db->getItem($sql);
        
        return $module;
    }
    
    /**
     * Get sections for building js treeview
     * @param int $sectionId
     * @return array
     */
    public function getSectionChildren($sectionId)
    {
        $sql =  'SELECT s.id,s.levelid,s.isparent,s.path,s.indic,m.mname,m.madmin' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'sections AS s' . 
                ' INNER JOIN ' . C_DB_TABLE_PREFIX . 'modules AS m' . 
                ' ON m.id = s.moduleid' . 
                ' WHERE s.parentid=' . $sectionId . 
                ' ORDER BY s.orderid';
        return $this->db->getItems($sql, 'id');
    }
    
    /**
     * Check referer and session state to prevent XSRF attacks.
     * todo: use session or complex cookie value
     * todo: on fail do audit, alert and some error output
     */
    public function checkXsrf()
    {
        //BOOKMARK: close slash
        $adminDir = $this->config->adminDir . '/';
        if (!isset($_SERVER['HTTP_REFERER'])
        || false === strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST'])) {
            if ($adminDir == $_SERVER['REQUEST_URI']) {
                if (isset($_COOKIE['xsrf_check'])) {
                    setcookie('xsrf_check', 0, time() - 9999);
                } else {
                    setcookie('xsrf_check', 1, 0, $adminDir, '', false, true);
                    $this->riseError(301, 'Referer required', $adminDir);
                }
            } else {
                if ($this->debug) {
                    $this->debug->varDump('XSRF check fail', false, false, true);
                } else {
                    Debug::varDump('XSRF check fail', false);
                }
            }
        }
        /*
        if ($this->params->action) {
            //react on any action (POST or GET sended)
        }
        if (0 == strcasecmp('POST', $_SERVER['REQUEST_METHOD'])) {
            //react on form posting (POST)
        }
        */
    }
    
    /**
     * @return Roles
     */
    public function getRoles()
    {
        if (null === $this->roles) {
            $this->roles = new Roles($this->config, $this->db, $this->debug);
        }
        return $this->roles;
        
    }
    
    /**
     * @param string|int $module
     * @param int $sectionId
     * @param string $action
     */
    public function checkUserAccess($module, $sectionId, $action)
    {
        //TODO 'adminlogin'
        if ('adminlogin' == $action || 'adminlogout' == $action) {
            return;
        }
        
        $user = $this->getUsers()->getCurrent();
        if (null === $user) {
            $this->riseError(403, 'User not set');
        }
        
        $roles = $this->getRoles();
        $restricted = $roles->rolesRestricted(
            $user['Id'], 
            $module, 
            $sectionId
        );
        if ($restricted) {
            $this->riseError(403, 'User access restricted');
        }
        
        //TODO: 'create'
        $permitted = $roles->rolesPermittedAction(
            $user['Id'], 
            $module, 
            (0 == $this->params->itemId ? 'create' : $action)
        );
        if (!$permitted) {
            $this->riseError(403, 'User do not have required permissions');
        }
    }
    
    /**
     * Дополнительные действия после действия Model::action, определяемые уровнем доступа пользователя.
     * @param Model $model
     * @param string $action
     */
    public function fixUserAction(Model $model, $action)
    {
        //TODO: 'update'
        if ('update' != $action) {
            return;
        }
        
        $user = $this->getUsers()->getCurrent();
        $roles = $this->getRoles();
        $module = $model->getModule();
        $module = (0 != $module['ModuleId'] ? (int) $module['ModuleId'] : (string) $module['Module']);
        
        if ($roles->isPublishRestricted($user['Id'], $module)) {
            if (0 == $this->params->itemId) {
                $this->params->itemId = $model->getLastInsertedId();
                $model->disable();
                $this->params->itemId = 0;
            } else {
                $model->disable();
            }
        }
    }
}
