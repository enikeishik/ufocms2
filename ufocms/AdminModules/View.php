<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules;

use Ufocms\Frontend\DIObject;

/**
 * Base view class
 */
class View extends DIObject
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
     * @var \Ufocms\Backend\Db
     */
    protected $db = null;
    
    /**
     * @var \Ufocms\Backend\Core
     */
    protected $core = null;
    
    /**
     * @var array
     */
    protected $module = null;
    
    /**
     * Availabe module level parameters
     * @var array
     */
    protected $moduleParams = null;
    
    /**
     * @var \Ufocms\Frontend\Tools
     */
    protected $tools = null;
    
    /**
     * @var Model
     */
    protected $model  = null;
    
    /**
     * @var UI
     */
    protected $ui = null;
    
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
        $this->tools =& $this->container->getRef('tools');
        $this->module =& $this->container->getRef('module');
        $this->moduleParams =& $this->container->getRef('moduleParams');
        $this->model =& $this->container->getRef('model');
    }
    
    /**
     * Инициализация объекта. Переобпределяется в потомках по необходимости.
     */
    protected function init()
    {
        
    }
    
    /**
     * Получение полного имени класса пользовательского интерфейса (UI).
     * @param string $class = null
     * @return string
     */
    protected function getUIClass($class = null)
    {
        if (null === $class || '' == $class) {
            $class = '\\Ufocms\\AdminModules\\' . $this->module['Module'] . '\\UI';
        }
        if (false === strpos($class, '\\')) {
            $class = '\\Ufocms\\AdminModules\\' . $this->module['Module'] . '\\' . $class;
        }
        if (!class_exists($class)) {
            $class = '\\Ufocms\\AdminModules\\UI';
        }
        return $class;
    }
    
    /**
     * Получение базовых параметров пользовательского интерфейса (UI) по-умолчанию.
     * @return string
     */
    protected function getUIParamsDefault()
    {
        $uiParams = '';
        if (null !== $this->params->sectionId) {
            $uiParams = '?' . $this->config->paramsNames['sectionId'] . '=' . $this->params->sectionId;
        }
        if (null !== $this->params->coreModule) {
            if ('' == $uiParams) {
                $uiParams = '?' . $this->config->paramsNames['coreModule'] .  '=' . $this->params->coreModule;
            } else {
                $uiParams .= '&' . $this->config->paramsNames['coreModule'] .  '=' . $this->params->coreModule;
            }
        }
        return $uiParams;
    }
    
    /**
     * Получение базовых параметров пользовательского интерфейса (UI).
     * @param string $uiParams = null
     * @param bool $uiParamsAppend = false
     * @return string
     */
    protected function getUIParams($uiParams = null, $uiParamsAppend = false)
    {
        if (null === $uiParams || '' == $uiParams) {
            $uiParams = $this->getUIParamsDefault();
        } else if ($uiParamsAppend) {
            $uiParams = $this->getUIParamsDefault() . $uiParams;
        }
        return $uiParams;
    }
    
    /**
     * Получение объекта пользовательского интерфейса (UI).
     * @param string $ui = null
     * @param string $uiParams = null
     * @param bool $uiParamsAppend = false
     * @return UI
     */
    protected function getUI($ui = null, $uiParams = null, $uiParamsAppend = false)
    {
        $uiClass = $this->getUIClass($ui);
        $container = new \Ufocms\Frontend\Container([
            'debug'         => &$this->debug, 
            'config'        => &$this->config, 
            'params'        => &$this->params, 
            'core'          => &$this->core, 
            'tools'         => &$this->tools, 
            'module'        => &$this->module, 
            'moduleParams'  => &$this->moduleParams, 
            'model'         => &$this->model, 
            'basePath'      => $this->getUIParams($uiParams, $uiParamsAppend), 
        ]);
        return new $uiClass($container);
    }
    
    /**
     * @return string
     */
    protected function getItemsLayout()
    {
        return 'templates/list.php';
    }
    
    /**
     * @return string
     */
    protected function getItemLayout()
    {
        return 'templates/form.php';
    }
    
    /**
     * @param string $layout = null
     * @return string
     */
    protected function getLayout($layout = null)
    {
        //TODO: 'templates' -> $config
        if (null === $layout || '' == $layout) {
            if (in_array($this->params->action, $this->config->actionsForm)) {
                return $this->getItemLayout();
            } else {
                return $this->getItemsLayout();
            }
        } else {
            if (false === strpos($layout, '.php')) {
                $layoutPath = 'templates/' . $layout . '.php';
            } else {
                $layoutPath = $layout;
            }
            if (file_exists($layoutPath)) {
                return $layoutPath;
            } else {
                return 'templates/empty.php';
            }
        }
    }
    
    /**
     * Отрисовка представления.
     * @param string $layout = null
     * @param string $ui = null
     * @param string $uiParams = null
     * @param bool $uiParamsAppend = false
     */
    public function render($layout = null, $ui = null, $uiParams = null, $uiParamsAppend = false)
    {
        if (null !== $this->debug) {
            $idx = $this->debug->trace('Render preparation');
        }
        //UI
        $this->ui = $this->getUI($ui, $uiParams, $uiParamsAppend);
        
        //Layout
        $layout = $this->getLayout($layout);
        if (null !== $this->debug) {
            $this->debug->trace($idx);
            $idx = $this->debug->trace('Render');
        }
        require_once $layout;
    }
    
    /**
     * Получение объекта виджета.
     * @param string $module
     * @return AdminWidget
     */
    public function adminWidget($module)
    {
        if ('' == $module) {
            $moduleClass = '\\Ufocms\\AdminModules\\AdminWidget';
        }
        if (false === strpos($module, '\\')) {
            $moduleClass = '\\Ufocms\\AdminModules\\' . $module . '\\AdminWidget';
        }
        return new $moduleClass($this->config, $this->db, $this->core, $this->debug);
    }
}
