<?php
/**
 * @copyright
 */

namespace Ufocms\Modules;

use Ufocms\Frontend\DIObject;
use Ufocms\Frontend\Container;

/**
 * Module level model base class
 */
abstract class Model extends DIObject
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
     * Availabe module level parameters
     * @var array
     */
    protected $moduleParams = null;
    
    /**
     * @var array
     */
    protected $item = null;
    
    /**
     * @var array
     */
    protected $items = null;
    
    /**
     * @var int
     */
    protected $itemsCount = null;
    
    /**
     * @var array
     */
    protected $settings = null;
    
    /**
     * @var mixed
     */
    protected $actionResult = null;
    
    /**
     * Распаковка контейнера.
     */
    protected function unpackContainer()
    {
        $this->module =& $this->container->getRef('module');
        $this->debug =& $this->container->getRef('debug');
        $this->params =& $this->container->getRef('params');
        $this->db =& $this->container->getRef('db');
        $this->core =& $this->container->getRef('core');
        $this->config =& $this->container->getRef('config');
        $this->tools =& $this->container->getRef('tools');
        $this->moduleParams =& $this->container->getRef('moduleParams');
    }
    
    /**
     * Инициализация объекта. Переопределяется в потомках по необходимости.
     */
    protected function init()
    {
        
    }
    
    /**
     * Получение установок модуля.
     * @return array
     */
    public function getSettings()
    {
        if (null !== $this->settings) {
            return $this->settings;
        }
        $this->settings = array();
        return $this->settings;
    }
    
    /**
     * Получение списка элементов.
     * @return array
     */
    public function getItems()
    {
        if (null !== $this->items) {
            return $this->items;
        }
        $this->items = array();
        $this->itemsCount = count($this->items);
        return $this->items;
    }
    
    /**
     * Получение общего количества элементов. Значение должно устанавливаться в getItems или аналогичных методах.
     * @return int
     */
    public function getItemsCount()
    {
        return $this->itemsCount;
    }
    
    /**
     * Получение данных текущего элемента.
     * @return array|null
     */
    public function getItem()
    {
        if (null !== $this->item) {
            return $this->item;
        }
        if (0 != $this->params->itemId) {
            $this->item = array();
        } else {
            $this->item = null;
        }
        return $this->item;
    }
    
    /**
     * Получение списка разделов использующих данный модуль.
     * @param bool $nc = false
     * @return array
     */
    public function getSections($nc = false)
    {
        if (!$nc && !is_null($this->sections)) {
            return $this->sections;
        }
        $section = $this->core->getSection($this->params->sectionId, 'moduleid');
        $items = $this->core->getModuleSections($section['moduleid']);
        foreach ($items as &$item) {
            $item = array('Value' => $item['id'], 'Title' => $item['indic']);
        }
        $this->sections = $items;
        return $this->sections;
    }
    
    /**
     * @return mixed
     */
    public function getActionResult()
    {
        return $this->actionResult;
    }
}
