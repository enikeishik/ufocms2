<?php
/**
 * UFOCMS v2 Content Management System
 * 
 * @copyright   Copyright (C) 2005 - 2017 Enikeishik <enikeishik@gmail.com>. All rights reserved.
 * @author      Enikeishik <enikeishik@gmail.com>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Ufocms\AdminModules;

use Ufocms\Frontend\DIObject;
use Ufocms\Frontend\Tools;

/**
 * Module level controller base class
 */
abstract class Controller extends DIObject //implements IController
{
    /**
     * @var \Ufocms\Frontend\Debug
     */
    protected $debug = null;
    
    /**
     * Ссылка на объект конфигурации.
     * @var \Ufocms\Backend\Config
     */
    protected $config = null;
    
    /**
     * @var \Ufocms\Backend\Params
     */
    protected $params = null;
    
    /**
     * @var \Ufocms\Frontend\Db
     */
    protected $db = null;
    
    /**
     * @var \Ufocms\Backend\Core
     */
    protected $core = null;
    
    /**
     * @var \Ufocms\Frontend\Tools
     */
    protected $tools = null;
    
    /**
     * @var array
     */
    protected $module = null;
    
    /**
     * Availabe module level parameters
     * @var array<string paramName => array<string Type, string Name, mixed Value, mixed Default> paramSet>
     */
    protected $moduleParamsStruct = null;
    
    /**
     * Availabe module level parameters
     * @var array<string paramName => mixed paramValue>
     */
    protected $moduleParams = null;
    
    /**
     * @var Model
     */
    protected $model = null;
    
    /**
     * @var View
     */
    protected $view = null;
    
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
     * Инициализация объекта. Переобпределяется в потомках по необходимости.
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
        $this->setModel();
        $this->modelAction();
        $this->setView();
        $this->renderView();
    }
    
    /**
     * Параметры уровня модуля (не только данные модели, а общие для MVC).
     */
    protected function setModuleParamsStruct()
    {
        $this->moduleParamsStruct = array(
            
        );
    }
    
    /**
     * Установка параметров.
     */
    protected function setParams()
    {
        foreach ($this->moduleParamsStruct as $paramName => $paramSet) {
            $param = null;
            if (isset($_POST[$paramSet['Name']])) {
                $param = $_POST[$paramSet['Name']];
            } else if (isset($_GET[$paramSet['Name']])) {
                $param = $_GET[$paramSet['Name']];
            }
            if (null === $param) {
                continue;
            }
            switch ($paramSet['Type']) {
                case 'int':
                    $this->moduleParamsStruct[$paramName]['Value'] = (int) $param;
                    break;
                case 'bool':
                    $this->moduleParamsStruct[$paramName]['Value'] = true;
                    break;
                case 'arrint':
                    if (is_array($param)) {
                        $this->moduleParamsStruct[$paramName]['Value'] = $this->tools->getArrayOfIntegers($param);
                    } else {
                        $this->moduleParamsStruct[$paramName]['Value'] = (int) $param;
                    }
                    break;
                default:
                    $this->moduleParamsStruct[$paramName]['Value'] = $param;
            }
        }
        
        //set unidentified bool-type params to its default values
        foreach ($this->moduleParamsStruct as $paramName => $paramSet) {
            if ('bool' == $paramSet['Type'] && null === $paramSet['Value']) {
                $this->moduleParamsStruct[$paramName]['Value'] = $paramSet['Default'];
            }
        }
        
        //get plain array from array of structs
        $this->moduleParams = $this->getModuleParams();
    }
    
    /**
     * Получение простого массива параметров модуля.
     * @return array
     */
    protected function getModuleParams()
    {
        $arr = [];
        foreach ($this->moduleParamsStruct as $param) {
            $arr[$param['Name']] = $param['Value'];
        }
        return $arr;
    }
    
    /**
     * Формирует полне имя класса.
     * @param string<'Controller'|'Model'|'View'> $type
     * @param string $class = ''
     * @return string
     */
    protected function getClass($type, $class = '')
    {
        if ('' == $class) {
            $class = '\\Ufocms\\AdminModules\\' . $this->module['Module'] . '\\' . $type;
        }
        if (false === strpos($class, '\\')) {
            $class = '\\Ufocms\\AdminModules\\' . $this->module['Module'] . '\\' . $class;
        }
        if (!class_exists($class)) {
            $class = '\\Ufocms\\AdminModules\\' . $type;
        }
        return $class;
    }
    
    /**
     * Создание объекта модели.
     * @param string $model = ''
     */
    protected function setModel($model = '')
    {
        $class = $this->getClass('Model', $model);
        $container = $this->core->getContainer([
            'debug'         => &$this->debug, 
            'config'        => &$this->config, 
            'params'        => &$this->params, 
            'db'            => &$this->db, 
            'core'          => &$this->core, 
            'module'        => &$this->module, 
            'tools'         => &$this->tools, 
            'moduleParams'  => &$this->moduleParams, 
        ]);
        $this->model = new $class($container);
    }
    
    /**
     * Осуществление действия модели.
     */
    protected function modelAction()
    {
        $action = $this->params->action;
        if (in_array($action, $this->config->actionsMake)) {
            $this->model->$action();
        }
    }
    
    /**
     * Создание объекта представления.
     * @param string $view = ''
     */
    protected function setView($view = '')
    {
        $class = $this->getClass('View', $view);
        $container = $this->core->getContainer([
            'debug'         => &$this->debug, 
            'config'        => &$this->config, 
            'params'        => &$this->params, 
            'db'            => &$this->db, 
            'core'          => &$this->core, 
            'module'        => &$this->module, 
            'tools'         => &$this->tools, 
            'model'         => &$this->model, 
            'moduleParams'  => &$this->moduleParams, 
        ]);
        $this->view = new $class($container);
    }
    
    /**
     * Осуществить вывод отображения представления.
     * @param string $layout = null
     * @param string $ui = null
     * @param string $uiParams = null
     * @param bool $uiParamsAppend = false
     */
    protected function renderView($layout = null, $ui = null, $uiParams = null, $uiParamsAppend = false)
    {
        $this->view->render($layout, $ui, $uiParams, $uiParamsAppend);
    }
    
    /**
     * Возвращает новый объект [подчиненного] контроллера.
     * @param string $controller
     * @return Controller
     */
    protected function getController($controller)
    {
        $class = $this->getClass('Controller', $controller);
        $container = $this->core->getContainer([
            'debug'         => &$this->debug, 
            'config'        => &$this->config, 
            'params'        => &$this->params, 
            'db'            => &$this->db, 
            'core'          => &$this->core, 
            'module'        => &$this->module, 
            'tools'         => &$this->tools, 
            'moduleParams'  => &$this->moduleParams, 
        ]);
        return new $class($container);
    }
}
