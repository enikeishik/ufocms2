<?php
/**
 * UFOCMS v2 Content Management System
 * 
 * @copyright   Copyright (C) 2005 - 2017 Enikeishik <enikeishik@gmail.com>. All rights reserved.
 * @author      Enikeishik <enikeishik@gmail.com>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Ufocms\Backend;

use Ufocms\Frontend\Container;
use Ufocms\Frontend\Tools;

/**
 * Main application controller
 */
class Main //implements IController
{
    /**
     * @var Audit
     */
    protected $audit = null;
    
    /**
     * @var \Ufocms\Frontend\Debug
     */
    protected $debug = null;
    
    /**
     * Ссылка на объект конфигурации.
     * @var Config
     */
    protected $config = null;
    
    /**
     * @var Params
     */
    protected $params = null;
    
    /**
     * @var \Ufocms\Frontend\Db
     */
    protected $db = null;
    
    /**
     * @var Core
     */
    protected $core = null;
    
    /**
     * @var array
     */
    protected $module = null;
    
    /**
     * @param &$debug = null
     */
    public function __construct(&$debug = null)
    {
        $this->config = new Config();
        $this->audit = new Audit($this->config);
        $this->debug =& $debug;
        $this->params = new Params();
        $this->setState();
        $this->saveState();
    }
    
    /**
     * Init state structure ($params) with data from GET and COOKIE
     */
    protected function setState()
    {
        if (isset($_GET[$this->config->paramsNames['coreModule']])) {
            $this->params->coreModule = (string) $_GET[$this->config->paramsNames['coreModule']];
            if (!in_array($this->params->coreModule, array_keys($this->config->coreModules))) {
                $this->params->coreModule = null;
            }
        }
        if (isset($_GET[$this->config->paramsNames['sectionId']])) {
            $this->params->sectionId = (int) $_GET[$this->config->paramsNames['sectionId']];
        }
        if (isset($_GET[$this->config->paramsNames['itemId']])) {
            $this->params->itemId = (int) $_GET[$this->config->paramsNames['itemId']];
        }
        if (isset($_GET[$this->config->paramsNames['subModule']])) {
            $this->params->subModule = (string) $_GET[$this->config->paramsNames['subModule']];
        }
        
        $this->params->page = $this->config->pageDefault;
        $this->params->pageSize = $this->config->pageSizeDefault;
        $this->loadState();
        
        if (isset($_GET[$this->config->paramsNames['page']])) {
            $this->params->page = (int) $_GET[$this->config->paramsNames['page']];
            if ($this->params->page < $this->config->pageMin || $this->params->page > $this->config->pageMax) {
                $this->params->page = $this->config->pageDefault;
            }
        }
        if (isset($_GET[$this->config->paramsNames['pageSize']])) {
            $this->params->pageSize = (int) $_GET[$this->config->paramsNames['pageSize']];
            if ($this->params->pageSize < $this->config->pageSizeMin || $this->params->pageSize > $this->config->pageSizeMax) {
                $this->params->pageSize = $this->config->pageSizeDefault;
            }
        }
        if (isset($_GET[$this->config->paramsNames['action']])) {
            $this->params->action = (string) $_GET[$this->config->paramsNames['action']];
            if (in_array($this->params->action, $this->config->actions)) {
                if (isset($_GET[$this->config->paramsNames['filterName']])) {
                    $this->params->filterName = (string) $_GET[$this->config->paramsNames['filterName']];
                }
                if (isset($_GET[$this->config->paramsNames['filterValue']])) {
                    $this->params->filterValue = (string) $_GET[$this->config->paramsNames['filterValue']];
                }
                if (isset($_GET[$this->config->paramsNames['sortField']])) {
                    $this->params->sortField = (string) $_GET[$this->config->paramsNames['sortField']];
                }
                if (isset($_GET[$this->config->paramsNames['sortDirection']])) {
                    $this->params->sortDirection = (string) $_GET[$this->config->paramsNames['sortDirection']];
                }
                $this->params->actionUnsafe = false;
            } else {
                $this->params->actionUnsafe = true;
            }
        }
    }
    
    /**
     * Retur key suffix for load/save state of current page.
     * @return string
     */
    protected function getStateSuffix()
    {
        return  $this->params->sectionId . 
                (null !== $this->params->coreModule ? $this->params->coreModule : '') . 
                (null !== $this->params->subModule ? $this->params->subModule : '');
    }
    
    /**
     * Load saved state from cookies
     */
    protected function loadState()
    {
        $suffix = $this->getStateSuffix();
        
        if (isset($_COOKIE[$this->config->paramsNames['page'] . $suffix])) {
            $this->params->page = (int) $_COOKIE[$this->config->paramsNames['page'] . $suffix];
            if ($this->params->page < $this->config->pageMin || $this->params->page > $this->config->pageMax) {
                $this->params->page = $this->config->pageDefault;
            }
        }
        if (isset($_COOKIE[$this->config->paramsNames['pageSize'] . $suffix])) {
            $this->params->pageSize = (int) $_COOKIE[$this->config->paramsNames['pageSize'] . $suffix];
            if ($this->params->pageSize < $this->config->pageSizeMin || $this->params->pageSize > $this->config->pageSizeMax) {
                $this->params->pageSize = $this->config->pageSizeDefault;
            }
        }
        if (isset($_COOKIE[$this->config->paramsNames['filterName'] . $suffix])) {
            $this->params->filterName = (string) $_COOKIE[$this->config->paramsNames['filterName'] . $suffix];
        }
        if (isset($_COOKIE[$this->config->paramsNames['filterValue'] . $suffix])) {
            $this->params->filterValue = (string) $_COOKIE[$this->config->paramsNames['filterValue'] . $suffix];
        }
        if (isset($_COOKIE[$this->config->paramsNames['sortField'] . $suffix])) {
            $this->params->sortField = (string) $_COOKIE[$this->config->paramsNames['sortField'] . $suffix];
        }
        if (isset($_COOKIE[$this->config->paramsNames['sortDirection'] . $suffix])) {
            $this->params->sortDirection = (string) $_COOKIE[$this->config->paramsNames['sortDirection'] . $suffix];
        }
    }
    
    /**
     * Save current state to cookies
     */
    protected function saveState()
    {
        $suffix = $this->getStateSuffix();
        
        if (isset($this->params->filterName) && isset($this->params->filterValue)) {
            setcookie($this->config->paramsNames['filterName'] . $suffix, $this->params->filterName, null, null, null, false, true);
            setcookie($this->config->paramsNames['filterValue'] . $suffix, $this->params->filterValue, null, null, null, false, true);
        }
        if (isset($this->params->sortField) && isset($this->params->sortDirection)) {
            setcookie($this->config->paramsNames['sortField'] . $suffix, $this->params->sortField, null, null, null, false, true);
            setcookie($this->config->paramsNames['sortDirection'] . $suffix, $this->params->sortDirection, null, null, null, false, true);
        }
        if (isset($this->params->page)) {
            setcookie($this->config->paramsNames['page'] . $suffix, $this->params->page, null, null, null, false, true);
        }
        if (isset($this->params->page)) {
            setcookie($this->config->paramsNames['pageSize'] . $suffix, $this->params->pageSize, null, null, null, false, true);
        }
    }
    
    /**
     * Controller dispatcher, make calls for model and view
     */
    public function dispatch()
    {
        $this->db = new Db($this->audit, $this->debug);
        $this->core = new Core($this->config, $this->params, $this->db, $this->debug);
        
        $this->core->checkXsrf();
        
        if (C_ADMIN_SYS_AUTH && !isset($_SERVER['REMOTE_USER'])) {
            $this->core->riseError(500, 'System authentication required');
        }
        
        $currentUser = $this->core->getUsers()->getCurrent();
        
        //TODO 'adminlogout'
        if (null === $currentUser || 'adminlogout' == $this->params->action) {
            $this->setModule('AdminLogin');
        } else {
            $this->setModule();
        }
        
        $this->audit->setUser(
            $currentUser['Id'], 
            ('' != $currentUser['Login'] ? $currentUser['Login'] : $currentUser['ExtUID'])
        );
        
        if (!is_null($controller = $this->getController())) {
            $controller->dispatch();
            return;
        }
        
        $model = $this->getModel();
        
        $action = $this->params->action;
        if (in_array($action, $this->config->actionsMake)) {
            $module = (0 != $this->module['ModuleId'] ? (int) $this->module['ModuleId'] : (string) $this->module['Module']);
            $this->core->checkUserAccess($module, $this->params->sectionId, $action);
            
            $model->$action();
            
            $this->core->fixUserAction($model, $action);
        }
        
        $view = $this->getView($model);
        $view->render();
    }
    
    /**
     * Set current section module data by $this->params->sectionId
     * @param string $moduleName
     */
    protected function setModule($moduleName = '')
    {
        $moduleId = 0;
        if ('' == $moduleName) {
            if (!is_null($this->params->coreModule)) {
                $moduleName = 'Core' . ucfirst($this->params->coreModule);
            } else {
                if (is_null($this->params->sectionId) || 0 == $this->params->sectionId) {
                    $moduleName = 'AdminStart';
                } else {
                    $module = $this->core->getModule();
                    $moduleId = $module['muid'];
                    $moduleName = ucfirst(substr($module['madmin'], 4));
                    unset($module);
                }
            }
        }
        $this->module = array(
            'ModuleId'      => $moduleId, 
            'Module'        => $moduleName, 
            'Controller'    => '\\Ufocms\\AdminModules\\' . $moduleName . '\\Controller', 
            'Model'         => '\\Ufocms\\AdminModules\\' . $moduleName . '\\Model', 
            'View'          => '\\Ufocms\\AdminModules\\' . $moduleName . '\\View', 
        );
    }
    
    /**
     * @return \Ufocms\AdminModules\Controller|null
     */
    protected function getController()
    {
        if (isset($this->module['Controller'])) {
            $class = $this->module['Controller'];
            if (class_exists($class)) {
                $container = new Container([
                    'debug'     => &$this->debug, 
                    'config'    => &$this->config, 
                    'params'    => &$this->params, 
                    'db'        => &$this->db, 
                    'core'      => &$this->core, 
                    'module'    => &$this->module, 
                ]);
                return new $class($container);
            }
        }
        return null;
    }
    
    /**
     * @return \Ufocms\AdminModules\Model
     */
    protected function getModel()
    {
        $container = new Container([
            'debug'         => &$this->debug, 
            'config'        => &$this->config, 
            'params'        => &$this->params, 
            'db'            => &$this->db, 
            'core'          => &$this->core, 
            'module'        => &$this->module, 
            'tools'         => new Tools($this->config, $this->params, $this->db, $this->debug), 
        ]);
        if (isset($this->module['Model'])) {
            $class = $this->module['Model'];
            if (class_exists($class)) {
                return new $class($container);
            }
        }
        $class = '\\Ufocms\\AdminModules\\Model';
        return new $class($container);
    }
    
    /**
     * @param \Ufocms\AdminModules\Model &$model
     * @return \Ufocms\AdminModules\View
     */
    protected function getView(&$model)
    {
        $container = new Container([
            'debug'         => &$this->debug, 
            'config'        => &$this->config, 
            'params'        => &$this->params, 
            'db'            => &$this->db, 
            'core'          => &$this->core, 
            'module'        => &$this->module, 
            'tools'         => new Tools($this->config, $this->params, $this->db, $this->debug), 
            'model'         => &$model, 
        ]);
        if (isset($this->module['View'])) {
            $class = $this->module['View'];
            if (class_exists($class)) {
                return new $class($container);
            }
        }
        $class = '\\Ufocms\\AdminModules\\View';
        return new $class($container);
    }
}
