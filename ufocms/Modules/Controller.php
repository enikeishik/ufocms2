<?php
/**
 * UFOCMS v2 Content Management System
 * 
 * @copyright   Copyright (C) 2005 - 2017 Enikeishik <enikeishik@gmail.com>. All rights reserved.
 * @author      Enikeishik <enikeishik@gmail.com>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Ufocms\Modules;

use Ufocms\Frontend\DIObject;
use Ufocms\Frontend\Tools;

/**
 * Module level controller base class
 */
class Controller extends DIObject implements ControllerInterface
{
    /**
     * @var array
     */
    protected $module = null;
    
    /**
     * @var \Ufocms\Frontend\Debug
     */
    protected $debug = null;
    
    /**
     * Ссылка на объект конфигурации.
     * @var \Ufocms\Frontend\Config
     */
    protected $config = null;
    
    /**
     * @var \Ufocms\Frontend\Params
     */
    protected $params = null;
    
    /**
     * @var \Ufocms\Frontend\Db
     */
    protected $db = null;
    
    /**
     * @var \Ufocms\Frontend\Core
     */
    protected $core = null;
    
    /**
     * @var \Ufocms\Frontend\Tools
     */
    protected $tools = null;
    
    /**
     * Available module level parameters.
     * @var array<string paramName => array<string type, string from, string prefix, bool additional, mixed value, mixed default> paramSet>
     */
    protected $moduleParamsStruct = null;
    
    /**
     * Available module level parameters.
     * @var array<string paramName => mixed paramValue>
     */
    protected $moduleParams = null;
    
    /**
     * @var array
     */
    protected $moduleParamsAssigned = array();
    
    /**
     * Распаковка контейнера.
     */
    protected function unpackContainer()
    {
        $this->debug =& $this->container->getRef('debug');
        $this->config =& $this->container->getRef('config');
        $this->params =& $this->container->getRef('params');
        $this->db =& $this->container->getRef('db');
        $this->core =& $this->container->getRef('core');
        $this->module =& $this->container->getRef('module');
        if ($this->container->has('tools')) {
            $this->tools =& $this->container->getRef('tools');
        } else {
            $this->tools = new Tools($this->config, $this->params, $this->db, $this->debug);
        }
    }
    
    /**
     * Инициализация объекта. Переопределяется в потомках по необходимости.
     */
    protected function init()
    {
        $this->setModuleParamsStruct();
        $this->setParams();
    }
    
    /**
     * Диспетчер контроллера, создает объекты модели и представления, выполняет действие модели и генерацию вывода представления.
     */
    public function dispatch()
    {
        $this->applicationAction();
        
        $model = $this->getModel();
        if (null === $model) {
            $this->core->riseError(404, 'Model not exists'); //exit('404-model'); //throw new Exception
        }
        $this->modelAction($model);
        
        $view = $this->getView($model, $this->getModuleContext($model), $this->getLayout());
        $view->render();
    }
    
    /**
     * Параметры уровня модуля.
     */
    protected function setModuleParamsStruct()
    {
        //BOOKMARK: DateTime format
        /*
         * type         bool|int|date|string|user defined
         * from         none - some internal flags, path - gets from path, get - gets from $_GET
         * prefix       parameter prefix for from-path, or name for from-get
         * additional   false - must be only one (no other params, except additional), true - can be with other params
         * value        initial value
         * default      default value
         */
        $this->moduleParamsStruct = array(
            'isRoot'        => ['type' => 'bool',   'from' => 'none',   'prefix' => '',             'additional' => false,  'value' => null, 'default' => true], 
            'isRss'         => ['type' => 'bool',   'from' => 'path',   'prefix' => 'rss',          'additional' => false,  'value' => null, 'default' => false], 
            'date'          => ['type' => 'date',   'from' => 'path',   'prefix' => 'dt',           'additional' => false,  'value' => null, 'default' => date('Y-m-d')], 
            'itemId'        => ['type' => 'int',    'from' => 'path',   'prefix' => 'id',           'additional' => false,  'value' => null, 'default' => 0], 
            'page'          => ['type' => 'int',    'from' => 'path',   'prefix' => 'page',         'additional' => true,   'value' => null, 'default' => $this->config->pageDefault], 
            'pageSize'      => ['type' => 'int',    'from' => 'path',   'prefix' => 'psize',        'additional' => true,   'value' => null, 'default' => $this->config->pageSizeDefault], 
            'commentsPage'  => ['type' => 'int',    'from' => 'path',   'prefix' => 'comments',     'additional' => true,   'value' => null, 'default' => $this->config->pageDefault], 
            'actionId'      => ['type' => 'int',    'from' => 'path',   'prefix' => 'action',       'additional' => true,   'value' => null, 'default' => 0], 
            'action'        => ['type' => 'string', 'from' => 'get',    'prefix' => 'action',       'additional' => false,  'value' => null, 'default' => ''], 
            'commentsAdd'   => ['type' => 'int',    'from' => 'get',    'prefix' => 'commentsadd',  'additional' => true,   'value' => null, 'default' => 0], 
            'interaction'   => ['type' => 'int',    'from' => 'get',    'prefix' => 'interaction',  'additional' => true,   'value' => null, 'default' => 0], 
        );
    }
    
    /**
     * Установка параметров.
     */
    protected function setParams()
    {
        //set params from path
        $this->setPathParams();
        
        //set params from GET
        $this->setGetParams();
        
        //set unidentified bool-type params to its default values
        $this->setUnidentifiedBoolParams();
        
        //get plain array from array of structs
        $this->moduleParams = $this->getModuleParams();
        
        //set some application level params
        $this->setAppParam('itemId', $this->moduleParams['itemId'], 0);
        $this->setAppParam('actionId', $this->moduleParams['actionId']);
        $this->setAppParam('action', $this->moduleParams['action']);
        $this->setAppParam('page', $this->moduleParams['page'], $this->config->pageDefault);
        $this->setAppParam('pageSize', $this->moduleParams['pageSize'], $this->config->pageSizeDefault);
        //TODO: set all appl level params (like itemPath, filterName and other when it will be used)
        
        //check common used params
        if (null === $this->moduleParams['commentsPage']) {
            $this->moduleParams['commentsPage'] = $this->moduleParamsStruct['commentsPage']['default'];
        } else {
            if ($this->moduleParams['commentsPage'] < $this->config->pageMin 
            || $this->moduleParams['commentsPage'] > $this->config->pageMax) {
                $this->moduleParams['commentsPage'] = $this->moduleParamsStruct['commentsPage']['default'];
            }
        }
    }
    
    /**
     * Установка значений параметров переданных в пути.
     */
    protected function setPathParams()
    {
        if (!is_array($this->params->sectionParams)) {
            return;
        }
        foreach ($this->params->sectionParams as $param) {
            if (!$this->setParam($param)) {
                $this->core->riseError(404, 'Module parameter unknown'); //exit('404-module-param-unknown'); //throw new Exception
            }
        }
    }
    
    /**
     * Установка значения параметра при его наличии в переданном пути.
     * @param string $param
     * @return bool
     */
    protected function setParam($param)
    {
        foreach ($this->moduleParamsStruct as $paramName => $paramSet) {
            if ('path' != $paramSet['from']) {
                continue;
            }
            if (in_array($paramName, $this->moduleParamsAssigned)) {
                return false;
            }
            
            if ('' != $paramSet['prefix'] 
            && 0 === strpos($param, $paramSet['prefix'])) { //for named params
                //в случае если передано более одного определяющего параметра 
                //(например идентификатор элемента и дата) выборки, выдаем ошибку 404, 
                //поскольку иначе будет неоднозначность и дублирование страниц
                // /section/id123/dt2017 | /section/dt2017/id123
                if (!$this->moduleParamsStruct[$paramName]['additional'] 
                && in_array('all', $this->moduleParamsAssigned)) {
                    return false;
                }
                $val = substr($param, strlen($paramSet['prefix']));
                switch ($paramSet['type']) {
                    case 'int':
                        $this->moduleParamsStruct[$paramName]['value'] = (int) $val;
                        break;
                    case 'bool':
                        $this->moduleParamsStruct[$paramName]['value'] = true;
                        break;
                    default:
                        $this->moduleParamsStruct[$paramName]['value'] = $val;
                }
                if (!$this->moduleParamsStruct[$paramName]['additional']) {
                    $this->moduleParamsAssigned[] = 'all';
                } else {
                    $this->moduleParamsAssigned[] = $paramName;
                }
                return true;
            } else if ('itemId' == $paramName && ctype_digit($param)) { //for itemId
                if (in_array('all', $this->moduleParamsAssigned)) {
                    return false;
                }
                $this->moduleParamsStruct[$paramName]['value'] = (int) $param;
                $this->moduleParamsAssigned[] = 'all';
                return true;
            } else if ('date' == $paramName && 10 == strlen($param) && false !== strtotime($param)) { //for date
                if (in_array('all', $this->moduleParamsAssigned)) {
                    return false;
                }
                if (false !== $date = strtotime($param)) {
                    //BOOKMARK: DateTime format
                    $this->moduleParamsStruct[$paramName]['value'] = date('Y-m-d', $date);
                    $this->moduleParamsAssigned[] = 'all';
                    return true;
                } else {
                    return false;
                }
            } else if ('' == $paramSet['prefix'] && null === $paramSet['value']) { //for param without prefix
                $this->moduleParamsStruct[$paramName]['value'] = $param;
                return true;
            }
        }
        return false;
    }
    
    /**
     * Установка параметров переданных через GET.
     */
    protected function setGetParams()
    {
        foreach ($this->moduleParamsStruct as $paramName => $paramSet) {
            if ('get' != $paramSet['from']) {
                continue;
            }
            if (isset($_GET[$paramSet['prefix']])) {
                switch ($paramSet['type']) {
                    case 'int':
                        $this->moduleParamsStruct[$paramName]['value'] = (int) $_GET[$paramSet['prefix']];
                        break;
                    case 'bool':
                        $this->moduleParamsStruct[$paramName]['value'] = true;
                        break;
                    default:
                        $this->moduleParamsStruct[$paramName]['value'] = $_GET[$paramSet['prefix']];
                }
            }
        }
    }
    
    /**
     * Установка не заданным булевым параметрам значений по умолчанию.
     */
    protected function setUnidentifiedBoolParams()
    {
        foreach ($this->moduleParamsStruct as $paramName => $paramSet) {
            if ('bool' == $paramSet['type'] && null === $paramSet['value']) {
                $this->moduleParamsStruct[$paramName]['value'] = $paramSet['default'];
            } else if ('isRoot' != $paramName && null !== $paramSet['value']) {
                $this->moduleParamsStruct['isRoot']['value'] = false;
            }
        }
    }
    
    /**
     * Получение простого массива параметров модуля.
     * @return array
     */
    protected function getModuleParams()
    {
        $arr = [];
        foreach ($this->moduleParamsStruct as $k => $v) {
            $arr[$k] = $v['value'];
        }
        return $arr;
    }
    
    /**
     * Установка параметров приложения.
     * @param string $name
     * @param mixed $value
     * @param mixed $default = null
     */
    protected function setAppParam($name, $value, $default = null)
    {
        $this->params->$name = $value ?? $default;
    }
    
    /**
     * Вызов действий уровня приложения.
     */
    final protected function applicationAction()
    {
        if (null !== $this->moduleParamsStruct['commentsAdd']['value']) {
            $this->core->getComments()->add();
        }
        if (null !== $this->moduleParamsStruct['interaction']['value']) {
            switch ($this->moduleParamsStruct['interaction']['value']) {
                case 1:
                    $this->core->getInteractionManage()->addComment();
                    break;
                case 2:
                    $this->core->getInteractionManage()->addRate();
                    break;
                case 3:
                    $this->core->getInteractionManage()->addCommentRate();
                    break;
            }
        }
    }
    
    /**
     * @return \Ufocms\Modules\Model|null
     */
    protected function getModel()
    {
        if (isset($this->module['Model'])) {
            $class = $this->module['Model'];
            if (class_exists($class)) {
                $container = $this->core->getContainer([
                    'module'        => &$this->module, 
                    'params'        => &$this->params, 
                    'db'            => &$this->db, 
                    'core'          => &$this->core, 
                    'debug'         => &$this->debug, 
                    'config'        => &$this->config, 
                    'tools'         => &$this->tools, 
                    'moduleParams'  => &$this->moduleParams, 
                ]);
                return new $class($container);
            } else {
                return null;
            }
        } else {
            return null;
        }
    }
    
    /**
     * Осуществить действие модели, если передан параметро actionId.
     * @param \Ufocms\Modules\Model &$model
     */
    protected function modelAction(&$model)
    {
        if (null !== $this->params->action) {
            $method = $this->params->action;
            if (method_exists($model, $method)) {
                $model->$method();
            }
        }
    }
    
    /**
     * @param \Ufocms\Modules\ModelInterface &$model
     * @param array $context
     * @param string $layout
     * @return \Ufocms\Modules\View
     */
    protected function getView(ModelInterface &$model, array $context, string $layout)
    {
        $container = $this->core->getContainer([
            'module'        => &$this->module, 
            'params'        => &$this->params, 
            'db'            => &$this->db, 
            'core'          => &$this->core, 
            'debug'         => &$this->debug, 
            'config'        => &$this->config, 
            'tools'         => &$this->tools, 
            'moduleParams'  => &$this->moduleParams, 
            'model'         => &$model, 
            'context'       => $context, 
            'layout'        => $layout, 
        ]);
        if (isset($this->module['View'])) {
            $class = $this->module['View'];
            if (class_exists($class)) {
                return new $class($container);
            } else {
                return new View($container);
            }
        } else {
            return new View($container);
        }
    }
    
    /**
     * Получение контекста текущего модуля.
     * @param ModelInterface &$model
     * @return array
     */
    protected function getModuleContext(ModelInterface &$model)
    {
        if (null !== $this->params->actionId 
        || null !== $this->params->action) {
            return array(
                'settings'      => $model->getSettings(), 
                'item'          => null, 
                'items'         => null, 
                'itemsCount'    => null, 
                'actionResult'  => $model->getActionResult(), 
            );
        }
        if (0 == $this->params->itemId) {
            return array(
                'settings'      => $model->getSettings(), 
                'item'          => null, 
                'items'         => $model->getItems(), 
                'itemsCount'    => $model->getItemsCount(), 
            );
        } else {
            $item = $model->getItem();
            if (null === $item) {
                $this->core->riseError(404, 'Item not exists');
            }
            return array(
                'settings'      => $model->getSettings(), 
                'item'          => $item, 
                'items'         => null, 
                'itemsCount'    => $model->getItemsCount(), 
            );
        }
    }
    
    /**
     * Получение пути к макету страницы.
     * @return string
     */
    protected function getLayout()
    {
        if (!$this->moduleParams['isRss']) {
            return $this->config->templatesEntry;
        } else {
            return $this->config->templatesRssEntry;
        }
    }
}
