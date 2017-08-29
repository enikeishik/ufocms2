<?php
/**
 * @copyright
 */

namespace Ufocms\Modules;

use Ufocms\Frontend\DIObject;

/**
 * Widget base class
 */
abstract class Widget extends DIObject
{
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
     * Availabe module level parameters
     * @var array
     */
    protected $moduleParams = null;
    
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
     * @var string (integers with comma delimited)
     */
    protected $srcSections = null;
    
    /**
     * @var string (integers with comma delimited)
     */
    protected $srcItems = null;
    
    /**
     * @var bool
     */
    protected $showTitle = null;
    
    /**
     * @var text
     */
    protected $title = null;
    
    /**
     * @var string
     */
    protected $content = null;
    
    /**
     * @var array
     */
    protected $params = null;
    
    /**
     * Распаковка контейнера.
     */
    protected function unpackContainer()
    {
        $this->debug        =& $this->container->getRef('debug');
        $this->db           =& $this->container->getRef('db');
        $this->core         =& $this->container->getRef('core');
        $this->config       =& $this->container->getRef('config');
        $this->tools        =& $this->container->getRef('tools');
        $this->moduleParams =& $this->container->getRef('moduleParams');
        $this->data         =& $this->container->getRef('data');
        $this->options      =& $this->container->getRef('options');
        $this->templateUrl  = $this->container->get('templateUrl');
    }
    
    /**
     * Инициализация объекта. Переопределяется в потомках по необходимости.
     */
    protected function init()
    {
        $this->srcSections  = $this->data['SrcSections'];
        $this->srcItems     = $this->data['SrcItems'];
        $this->showTitle    = $this->data['ShowTitle'];
        $this->title        = $this->data['Title'];
        $this->content      = $this->data['Content'];
        $this->params       = json_decode($this->data['Params'], true);
    }
    
    /**
     * Установка текущего контекста.
     */
    protected function setContext()
    {
        $this->context = array(
            'debug'         => &$this->debug, 
            'tools'         => &$this->tools, 
            'moduleParams'  => &$this->moduleParams, 
            'showTitle'     => $this->showTitle, 
            'title'         => $this->title, 
            'content'       => $this->content, 
        );
    }
    
    /**
     * Поиск требуемого шаблона. Возвращает существующий путь или пустую строку.
     * @return string
     */
    protected function findTemplate()
    {
        if (0 != $this->data['ModuleId']) {
            $widget =   '/' . strtolower(substr($this->data['madmin'], 4)) . 
                        '/widget' . strtolower($this->data['Name']) . '.php';
        } else {
            $widget =   '/widgets/' . strtolower($this->data['Name']) . '.php';
        }
        
        if (null !== $this->templateUrl) {
            $template = $this->config->rootPath . 
                        $this->templateUrl . 
                        $widget;
            if (file_exists($template)) {
                return $template;
            }
        }
        $template = $this->config->rootPath . 
                    $this->config->templatesDir . $this->config->themeDefault . 
                    $widget;
        if (file_exists($template)) {
            return $template;
        }
        return '';
    }
    
    /**
     * Генерация виджета.
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
