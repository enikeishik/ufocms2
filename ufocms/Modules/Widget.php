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
     * @var string
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
        $this->templatePath = $this->config->rootPath . $this->templateUrl;
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
        if (0 != $this->data['ModuleId']) {
            $widget =   '/' . strtolower(substr($this->data['madmin'], 4)) . 
                        '/widget' . strtolower($this->data['Name']) . '.php';
        } else {
            $widget =   '/widgets/' . strtolower($this->data['Name']) . '.php';
        }
        
        // /templates/mytemplate/mymodule|widgets/entry
        if (null !== $this->templateUrl) {
            $template = $this->templatePath . 
                        $widget;
            if (file_exists($template)) {
                return $template;
            }
        }
        
        // /templates/default/mymodule|widgets/entry
        $template = $this->getThemeDefaultPath() . 
                    $widget;
        if (file_exists($template)) {
            return $template;
        }
        
        if (null !== $this->debug) {
            // /templates/default/default/entry
            return  $this->getThemeDefaultPath() . 
                    $this->config->templateDefault . 
                    '/widget.php';
        } else {
            return '';
        }
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
