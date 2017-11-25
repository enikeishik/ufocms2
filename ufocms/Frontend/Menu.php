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
     * @var Tools
     */
    protected $tools = null;
    
    /**
     * @var string
     */
    protected $templateUrl = null;
    
    /**
     * Filter sections with inmenu (false) or inlinks (true) flag.
     * @var bool
     */
    protected $filterLinks = false;
    
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
        $this->templateUrl = $this->container->get('templateUrl');
    }
    
    /**
     * Инициализация объекта. Переобпределяется в потомках по необходимости.
     */
    protected function init()
    {
        
    }
    
    /**
     * Change filter sections to inlinks flag.
     */
    public function filterLinks()
    {
        $this->filterLinks = true;
    }
    
    /**
     * Change filter sections to inmenu flag.
     */
    public function filterMenu()
    {
        $this->filterLinks = false;
    }
    
    /**
     * @return string
     */
    protected function getFilter()
    {
        return $this->filterLinks ? 'inlinks!=0' : 'inmenu!=0';
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
     * @return array
     */
    public function getTopSections()
    {
        return $this->core->getSections('id, path, indic', $this->getFilter() . ' AND parentid=0');
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
                    'items' => $this->getTopSections()
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
     * @return array
     */
    public function getChildren()
    {
        return $this->core->getSections(
            'id, path, indic', 
            $this->getFilter() . ' AND parentid=' . (null === $this->params->sectionId || -1 == $this->params->sectionId ? 0 : $this->params->sectionId)
        );
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
                    'items' => $this->getChildren()
                )
            )
        );
        include $this->getTemplatePath() . $this->getEntryPoint($entry);
    }
    
    /**
     * @param int $itemId
     * @return array
     */
    public function getItemChildren($itemId)
    {
        return $this->core->getSections(
            'id, path, indic', 
            $this->getFilter() . ' AND parentid=' . $itemId
        );
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
                    'items' => $this->getItemChildren($itemId)
                )
            )
        );
        include $this->getTemplatePath() . $this->getEntryPoint($entry);
    }
    
    /**
     * @return array
     * @param int $itemId
     */
    public function getItemParent($itemId)
    {
        $parents = $this->core->getSections('parentid', 'id=' . $itemId);
        if (null === $parents || 0 == count($parents)) {
            return array();
        }
        return $this->core->getSections(
            'id, path, indic', 
            $this->getFilter() . ' AND id=' . $parents[0]['parentid']
        );
    }
    
    /**
     * @return array
     */
    public function getSiblings()
    {
        $section = $this->core->getCurrentSection();
        return $this->core->getSections(
            'id, path, indic', 
            $this->getFilter() . ' AND parentid=' . (null === $section ? 0 : $section['parentid'])
        );
    }
    
    /**
     * @param string $entry = null
     */
    public function siblings($entry = null)
    {
        extract(
            array_merge(
                $this->getApplicationContext(), 
                array(
                    'items' => $this->getSiblings()
                )
            )
        );
        include $this->getTemplatePath() . $this->getEntryPoint($entry);
    }
    
    /**
     * @return array
     */
    public function getBreadcrumbs()
    {
        return $this->core->getSectionParents('id, path, indic');
    }
    
    /**
     * @param string $entry = null
     */
    public function breadcrumbs($entry = null)
    {
        extract(
            array_merge(
                $this->getApplicationContext(), 
                array('items' => $this->getBreadcrumbs())
            )
        );
        include $this->getTemplatePath() . $this->getEntryPoint($entry);
    }
}
