<?php
/**
 * @copyright
 */

namespace Ufocms\Frontend;

/**
 * Menu generation methods
 */
class Menu extends DIObject
{
    
    /**
     * @var Debug
     */
    protected $debug = null;
    
    /**
     * @var Config
     */
    protected $config = null;
    
    /**
     * @var Params
     */
    protected $params = null;
    
    /**
     * @var Db
     */
    protected $db = null;
    
    /**
     * @var Core
     */
    protected $core = null;
    
    /**
     * @var string
     */
    protected $templateUrl = null;
    
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
        $this->templateUrl = $this->container->get('templateUrl');
    }
    
    /**
     * Инициализация объекта. Переобпределяется в потомках по необходимости.
     */
    protected function init()
    {
        
    }
    
    /**
     * Установка контекста приложения.
     * @return array
     */
    protected function getApplicationContext()
    {
        return array(
            'debug'         => &$this->debug, 
            'tools'         => &$this->tools, 
            'params'        => &$this->params, 
            'site'          => $this->core->getSite(), 
            'section'       => $this->core->getCurrentSection(), 
        );
    }
    
    protected function getTemplatePath()
    {
        if (null === $this->templateUrl) {
            return  $this->config->rootPath . 
                    $this->config->templatesDir . $this->config->themeDefault;
        } else {
            return  $this->config->rootPath . 
                    $this->templateUrl;
        }
    }
    
    protected function getEntryPoint($entry = null)
    {
        if (null === $entry) {
            return $this->config->templatesMenuEntry;
        } else {
            return '/' . $entry . '.php';
        }
    }
    
    /**
     * @param string $entry = null
     */
    public function topSections($entry = null)
    {
        extract(
            array_merge(
                $this->getApplicationContext(), 
                array(
                    'items' => $this->core->getSections('id, path, indic', 'inmenu!=0 AND parentid=0')
                )
            )
        );
        include $this->getTemplatePath() . $this->getEntryPoint($entry);
    }
    
    /**
     * @param array $items
     * @param string $entry = null
     */
    public function custom(array $items, $entry = null)
    {
        extract($this->getApplicationContext());
        include $this->getTemplatePath() . $this->getEntryPoint($entry);
    }
    
    /**
     * @param string $entry = null
     */
    public function children($entry = null)
    {
        extract(
            array_merge(
                $this->getApplicationContext(), 
                array(
                    'items' => $this->core->getSections(
                        'id, path, indic', 
                        'inmenu!=0 AND parentid=' . (null === $this->params->sectionId || -1 == $this->params->sectionId ? 0 : $this->params->sectionId)
                    )
                )
            )
        );
        include $this->getTemplatePath() . $this->getEntryPoint($entry);
    }
    
    /**
     * @param int $itemId
     * @param string $entry = null
     */
    public function itemChildren($itemId, $entry = null)
    {
        extract(
            array_merge(
                $this->getApplicationContext(), 
                array(
                    'items' => $this->core->getSections(
                        'id, path, indic', 
                        'inmenu!=0 AND parentid=' . $itemId
                    )
                )
            )
        );
        include $this->getTemplatePath() . $this->getEntryPoint($entry);
    }
    
    /**
     * @param string $entry = null
     */
    public function siblings($entry = null)
    {
        $section = $this->core->getCurrentSection();
        extract(
            array_merge(
                $this->getApplicationContext(), 
                array(
                    'items' => $this->core->getSections(
                        'id, path, indic', 
                        'inmenu!=0 AND parentid=' . (null === $section ? 0 : $section['parentid'])
                    )
                )
            )
        );
        include $this->getTemplatePath() . $this->getEntryPoint($entry);
    }
    
    /**
     * @param string $entry = null
     */
    public function breadcrumbs($entry = null)
    {
        extract(
            array_merge(
                $this->getApplicationContext(), 
                array('items' => $this->core->getSectionParents('id, path, indic'))
            )
        );
        include $this->getTemplatePath() . $this->getEntryPoint($entry);
    }
}
