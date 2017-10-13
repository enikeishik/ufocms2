<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules;

use Ufocms\Frontend\DIObject;

/**
 * Base Widget class
 */
class Widget extends Schema
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
     * @var int
     */
    protected $widgetId = null;
    
    /**
     * @var int
     */
    protected $typeId = null;
    
    /**
     * @var bool
     */
    protected $useContent = null;
    
    /**
     * @var bool
     */
    protected $singleSource = null;
    
    /**
     * @var bool
     */
    protected $sourceDepends = null;
    
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
        $this->moduleParams =& $this->container->getRef('moduleParams');
        $this->tools =& $this->container->getRef('tools');
        $this->widgetId = $this->container->get('WidgetId');
        $this->typeId = $this->container->get('TypeId');
    }
    
    /**
     * Инициализация объекта. Переобпределяется в потомках по необходимости.
     */
    protected function init()
    {
        parent::init();
        $this->useContent = false;
        $this->singleSource = false;
        $this->sourceDepends = false;
    }
    
    /**
     * Get field items by demand
     * @param string|array $field
     * @return array|null
     */
    public function getFieldItems($field)
    {
        return $this->getFieldMethodStoredResult($field, 'Items');
    }
    
    /**
     * Возвращает флаг использования контентного поля в БД.
     * @return bool
     */
    public function getUseContent()
    {
        return $this->useContent;
    }
    
    /**
     * Возвращает флаг использования единственного источника (а не нескольких).
     * @return bool
     */
    public function getSingleSource()
    {
        return $this->singleSource;
    }
    
    /**
     * Возвращает флаг зависимости параметров от источника.
     * @return bool
     */
    public function getSourceDepends()
    {
        return $this->sourceDepends;
    }
}
