<?php
/**
 * @copyright
 */

namespace Ufocms\Modules;

use Ufocms\Frontend\DIObject;

/**
 * Module level Insertion base class
 */
abstract class Insertion extends DIObject
{
    /**
     * @var string
     */
    protected $moduleName = null;
    
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
     * @var array
     */
    protected $data = null;
    
    /**
     * @var array
     */
    protected $options = null;
    
    /**
     * @var string
     */
    protected $templateUrl = null;
    
    /**
     * @var array
     */
    protected $context = null;
    
    /**
     * Распаковка контейнера.
     */
    protected function unpackContainer()
    {
        $this->debug =& $this->container->getRef('debug');
        $this->db =& $this->container->getRef('db');
        $this->core =& $this->container->getRef('core');
        $this->config =& $this->container->getRef('config');
        $this->tools =& $this->container->getRef('tools');
        $this->data =& $this->container->getRef('data');
        $this->options =& $this->container->getRef('options');
        $this->templateUrl = $this->container->get('templateUrl');
        $this->setModuleName();
    }
    
    /**
     * Инициализация объекта. Переопределяется в потомках по необходимости.
     */
    protected function init()
    {
        
    }
    
    /**
     * Установка имени текущего модуля.
     */
    protected function setModuleName()
    {
        $class = explode('\\', get_class($this));
        $this->moduleName = $class[count($class) - 2];
    }
    
    /**
     * Получение данных вставки (виджета).
     * @return array|null
     */
    abstract protected function getItems();
    
    /**
     * Установка текущего контекста.
     * @param array $context = null
     */
    protected function setContext(array $context = null)
    {
        if (null === $context && null === $this->context) {
            $items = $this->getItems();
            $itemsCount = count($items);
            if (0 < $itemsCount) {
                $this->context = array(
                    'section'   => $this->core->getSection((int) $this->data['SourceId']), 
                    'item'      => $items[0], 
                    'items'     => $items, 
                    'tools'     => &$this->tools, 
                    'debug'     => &$this->debug, 
                );
            } else {
                $this->context = array(
                    'section'   => $this->core->getSection((int) $this->data['SourceId']), 
                    'item'      => null, 
                    'items'     => null, 
                    'tools'     => &$this->tools, 
                    'debug'     => &$this->debug, 
                );
            }
        } else {
            $this->context = $context;
        }
    }
    
    /**
     * 
     */
    public function render()
    {
        $this->setContext();
        extract(
            $this->context, 
            EXTR_PREFIX_SAME, 'insertion'
        );
        
        if (null === $this->templateUrl) {
            $template = $this->config->rootPath . 
                        $this->config->templatesDir . $this->config->themeDefault . 
                        '/' . strtolower($this->moduleName) . 
                        $this->config->templatesInsertionEntry;
        } else {
            $template = $this->config->rootPath . 
                        $this->templateUrl . 
                        '/' . strtolower($this->moduleName) . 
                        $this->config->templatesInsertionEntry;
        }
        if (file_exists($template)) {
            include $template;
        } else {
            if (null === $this->templateUrl) {
                $template = $this->config->rootPath . 
                            $this->config->templatesDir . $this->config->themeDefault . 
                            $this->config->templateDefault . 
                            $this->config->templatesInsertionEntry;
            } else {
                $template = $this->config->rootPath . 
                            $this->templateUrl . 
                            $this->config->templateDefault . 
                            $this->config->templatesInsertionEntry;
            }
            if (file_exists($template)) {
                include $template;
            } else {
                $template = $this->config->rootPath . 
                            $this->config->templatesDir . $this->config->themeDefault . 
                            $this->config->templateDefault . 
                            $this->config->templatesInsertionEntry;
                include $template;
            }
        }
    }
}
