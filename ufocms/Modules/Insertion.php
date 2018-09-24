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

/**
 * Module level Insertion base class
 * @deprecated
 */
abstract class Insertion extends DIObject implements InsertionInterface
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
     * Относительный путь (от корня сайта) к папке текущего шаблона.
     * @var string
     */
    protected $templateUrl = null;
    
    /**
     * Абсолютрый путь к папке текущего шаблона.
     * @var string
     */
    protected $templatePath = null;
    
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
        $this->templatePath = $this->config->rootPath . $this->templateUrl;
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
     * Возвращает путь к теме по-умолчанию.
     * @return string
     */
    protected function getThemeDefaultPath()
    {
        return  $this->config->rootPath . 
                $this->config->templatesDir . 
                $this->config->themeDefault;
    }
    
    /**
     * Поиск требуемого шаблона. Возвращает существующий путь или пустую строку.
     * @return string
     */
    protected function findTemplate()
    {
        if (null !== $this->templateUrl) {
            // /templates/mytemplate/mymodule/entry
            $template = $this->templatePath . 
                        '/' . strtolower($this->moduleName) . 
                        $this->config->templatesInsertionEntry;
            if (file_exists($template)) {
                return $template;
            }
            
            // /templates/mytemplate/default/entry
            $template = $this->templatePath . 
                        $this->config->templateDefault . 
                        $this->config->templatesInsertionEntry;
            if (file_exists($template)) {
                return $template;
            }
        }
        
        // /templates/default/mymodule/entry
        $template = $this->getThemeDefaultPath() . 
                    '/' . strtolower($this->moduleName) . 
                    $this->config->templatesInsertionEntry;
        if (file_exists($template)) {
            return $template;
        }
        
        if (null !== $this->debug) {
            // /templates/default/default/entry
            $template = $this->getThemeDefaultPath() . 
                        $this->config->templateDefault . 
                        $this->config->templatesInsertionEntry;
            if (file_exists($template)) {
                return $template;
            }
            return '';
        } else {
            return '';
        }
    }
    
    /**
     * Генерация вывода.
     */
    public function render()
    {
        if ('' != $template = $this->findTemplate()) {
            $this->setContext();
            extract($this->context);
            include $template;
        }
    }
}
