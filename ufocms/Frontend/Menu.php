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
        $this->templatePath = $this->config->rootPath . $this->templateUrl;
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
     * Выбор ссылок для основного или дополнительного меню.
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
     * Получение входной точки шаблона.
     * @param string $entry = null
     * @return string
     */
    protected function getEntryPoint($entry = null)
    {
        if (null !== $entry) {
            return '/' . $entry . '.php';
        }
        return $this->config->templatesMenuEntry;
    }
    
    /**
     * Поиск требуемого шаблона. Возвращаемый путь может не существовать.
     * @param string $entry = null
     * @return string
     */
    protected function getTemplatePath($entry = null)
    {
        if (null !== $this->templateUrl) {
            $template = $this->templatePath . 
                        $this->getEntryPoint($entry);
            if (file_exists($template)) {
                return  $template;
            }
        }
        return  $this->getThemeDefaultPath() . 
                $this->getEntryPoint($entry);
    }
    
    /**
     * Получение разделов верхнего уровня.
     * @return array
     */
    public function getTopSections()
    {
        return $this->core->getSections('id, path, indic', $this->getFilter() . ' AND parentid=0');
    }
    
    /**
     * Получение разделов верхнего уровня и их непосредственных подразделов.
     * @return array
     */
    public function getTwoLevelsSections()
    {
        $sections = $this->core->getSections('id, path, indic', $this->getFilter() . ' AND parentid=0');
        $ids = [];
        foreach ($sections as $section) {
            $ids[] = $section['id'];
        }
        $children = $this->core->getSections('id, parentid, path, indic', $this->getFilter() . ' AND parentid IN(' . implode(',', $ids) . ')');
        foreach ($sections as &$section) {
            $section['children'] = [];
            foreach ($children as $child) {
                if ($section['id'] == $child['parentid']) {
                    $section['children'][] = $child;
                }
            }
        }
        unset($section);
        return $sections;
    }
    
    /**
     * Вывод разделов верхнего уровня.
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
        include $this->getTemplatePath($entry);
    }
    
    /**
     * Вывод разделов верхнего уровня и их непрсредственных подразделов.
     * @param string $entry = null
     */
    public function twoLevelsSections($entry = null)
    {
        extract(
            array_merge(
                $this->getApplicationContext(), 
                array(
                    'items' => $this->getTwoLevelsSections()
                )
            )
        );
        include $this->getTemplatePath($entry);
    }
    
    /**
     * Вывод пользовательского меню.
     * @param array $items
     * @param string $entry = null
     */
    public function custom(array $items, $entry = null)
    {
        extract($this->getApplicationContext());
        include $this->getTemplatePath($entry);
    }
    
    /**
     * Получение дочерних разделов текущего раздела.
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
     * Вывод дочерних разделов текущего раздела.
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
        include $this->getTemplatePath($entry);
    }
    
    /**
     * Получение дочерних разделов указанного раздела.
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
     * Вывод дочерних разделов указанного раздела.
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
        include $this->getTemplatePath($entry);
    }
    
    /**
     * Получение родительского раздела указанного раздела.
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
     * Получение смежных разделов текущего раздела.
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
     * Вывод смежных разделов текущего раздела.
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
        include $this->getTemplatePath($entry);
    }
    
    /**
     * Получение цепочки родительских разделов.
     * @return array
     */
    public function getBreadcrumbs()
    {
        return $this->core->getSectionParents('id, path, indic');
    }
    
    /**
     * Вывод цепочки родительских разделов.
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
        include $this->getTemplatePath($entry);
    }
}
