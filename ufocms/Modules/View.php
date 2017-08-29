<?php
/**
 * @copyright
 */

namespace Ufocms\Modules;

use Ufocms\Frontend\DIObject;
use Ufocms\Frontend\Menu;

/**
 * Module level view base class
 */
class View extends DIObject
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
     * @var Model
     */
    protected $model = null;
    
    /**
     * Абсолютрый путь к папке текущего шаблона.
     * @var string
     */
    protected $templatePath = null;
    
    /**
     * Относительный путь (от корня сайта) к папке текущего шаблона.
     * @var string
     */
    protected $templateUrl = null;
    
    /**
     * Доп. стиль текущей темы (напр. цветовая гамма).
     * @var string
     */
    protected $themeStyle = null;
    
    /**
     * Текущий контекст.
     * @var array
     */
    protected $context = null;
    
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
        $this->model =& $this->container->getRef('model');
    }
    
    /**
     * Инициализация объекта. Переопределяется в потомках по необходимости.
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
            'config'        => &$this->config, 
            'core'          => &$this->core, 
            'tools'         => &$this->tools, 
            'site'          => $this->core->getSite(), 
            'section'       => $this->core->getCurrentSection(), 
        );
    }
    
    /**
     * Установка контекста текущего модуля.
     * @return array
     */
    protected function getModuleContext()
    {
        if (null !== $this->params->actionId 
        || null !== $this->params->action) {
            return array(
                'settings'      => $this->model->getSettings(), 
                'item'          => null, 
                'items'         => null, 
                'itemsCount'    => null, 
                'actionResult'  => $this->model->getActionResult(), 
            );
        }
        if (0 == $this->params->itemId) {
            return array(
                'settings'      => $this->model->getSettings(), 
                'item'          => null, 
                'items'         => $this->model->getItems(), 
                'itemsCount'    => $this->model->getItemsCount(), 
            );
        } else {
            $item = $this->model->getItem();
            if (null === $item) {
                $this->core->riseError(404, 'Item not exists');
            }
            return array(
                'settings'      => $this->model->getSettings(), 
                'item'          => $item, 
                'items'         => null, 
                'itemsCount'    => $this->model->getItemsCount(), 
            );
        }
    }
    
    /**
     * Установка текущего контекста.
     * @param array $context = null
     */
    protected function setContext(array $context = null)
    {
        if (null === $context && null === $this->context) {
            $this->context = array_merge(
                $this->getApplicationContext(), 
                $this->getModuleContext()
            );
        } else {
            $this->context = $context;
        }
    }
    
    /**
     * Установка текущего набора шаблонов.
     * @param string $theme = null
     */
    public function setTheme($theme = null)
    {
        if (null === $theme && null === $this->templateUrl) {
            $this->templateUrl = $this->config->templatesDir . $this->config->themeDefault;
        } else if (null !== $theme) {
            $this->templateUrl = $this->config->templatesDir . '/' . $theme;
        }
        $this->templatePath = $this->config->rootPath . $this->templateUrl;
        $this->setThemeStyle();
    }
    
    /**
     * Установка доп. стиля текущей темы (напр. цветовая гамма).
     */
    protected function setThemeStyle()
    {
        if (isset($_GET[$this->config->themeStyleParam])) {
            $style = $_GET[$this->config->themeStyleParam];
            if (in_array($style, $this->config->themeStylesAllowed)) {
                setcookie($this->config->themeStyleParam, $style, time() + $this->config->themeStyleLifetime);
                $this->themeStyle = $style;
            } else {
                setcookie($this->config->themeStyleParam, '', time() - $this->config->themeStyleLifetime);
            }
        } else if (isset($_COOKIE[$this->config->themeStyleParam])) {
            $style = $_COOKIE[$this->config->themeStyleParam];
            if (in_array($style, $this->config->themeStylesAllowed)) {
                $this->themeStyle = $style;
            }
        }
    }
    
    /**
     * Получение пути к макету страницы.
     * @return string
     */
    protected function getLayout()
    {
        return $this->templatePath . $this->config->templatesEntry;
    }
    
    /**
     * Поиск требуемого шаблона. Возвращаемый путь может не существовать.
     * @param string $theme
     * @param string $module
     * @param string $entry
     * @return string
     */
    protected function findTemplate($theme, $module, $entry)
    {
        if (null !== $module) {
            // /templates/mytemplate/mymodule/entry
            $template = $theme . 
                        '/' . strtolower($module) . 
                        $entry;
            if (file_exists($template)) {
                return $template;
            } else {
                // /templates/mytemplate/default/entry
                $template = $theme . 
                            $this->config->templateDefault . 
                            $entry;
                if (file_exists($template)) {
                    return $template;
                } else {
                    // /templates/mytemplate/entry (!NOT /templates/mytemplate/index.php)
                    $template = $theme . 
                                $entry;
                    if ($this->config->templatesEntry != $entry && file_exists($template)) {
                        return $template;
                    } else {
                        // /templates/default/mymodule/entry
                        $template = $this->config->rootPath . 
                                    $this->config->templatesDir . $this->config->themeDefault . 
                                    '/' . strtolower($module) . 
                                    $entry;
                        if (file_exists($template)) {
                            return $template;
                        } else {
                            // /templates/default/default/entry
                            $template = $this->config->rootPath . 
                                        $this->config->templatesDir . $this->config->themeDefault . 
                                        $this->config->templateDefault . 
                                        $entry;
                            if (file_exists($template)) {
                                return $template;
                            } else {
                                // /templates/default/entry
                                $template = $this->config->rootPath . 
                                            $this->config->templatesDir . $this->config->themeDefault . 
                                            $entry;
                                return $template;
                            }
                        }
                    }
                }
            }
        } else {
            // /templates/mytemplate/entry
            $template = $theme . 
                        $entry;
            if (file_exists($template)) {
                return $template;
            } else {
                // /templates/default/entry
                $template = $this->config->rootPath . 
                            $this->config->templatesDir . $this->config->themeDefault . 
                            $entry;
                return $template;
            }
        }
    }
    
    /**
     * Генерация страницы.
     */
    public function render()
    {
        if (null !== $this->debug) {
            $idx = $this->debug->trace('Render preparation');
        }
        $this->setTheme();
        $this->setContext();
        $container = $this->core->getContainer([
            'debug'         => &$this->debug, 
            'config'        => &$this->config, 
            'params'        => &$this->params, 
            'db'            => &$this->db, 
            'core'          => &$this->core, 
            'templateUrl'   => $this->templateUrl, 
        ]);
        extract(
            array_merge(
                array('menu' => new Menu($container)), 
                $this->context, 
                array(
                    'headTitle'     => $this->getHeadTitle(), 
                    'metaDesc'      => $this->getMetaDesc(), 
                    'metaKeys'      => $this->getMetaKeys(), 
                )
            ), 
            EXTR_PREFIX_SAME, 'model'
        );
        if (null !== $this->debug) {
            $this->debug->trace($idx);
            $idx = $this->debug->trace('Render');
        }
        require_once $this->getLayout();
    }
    
    /**
     * Gets entry point of module template.
     * @return string
     */
    protected function getModuleTemplateEntry()
    {
        if (null !== $this->params->actionId 
        || null !== $this->params->action) {
            return $this->config->templatesResultEntry;
        }
        if (0 == $this->params->itemId) {
            return $this->config->templatesEntry;
        } else {
            return $this->config->templatesItemEntry;
        }
    }
    
    /**
     * Генерация вывода текущего модуля.
     */
    protected function renderModule()
    {
        extract(
            $this->context, 
            EXTR_PREFIX_SAME, 'model'
        );
        $template = $this->findTemplate(
            $this->templatePath, 
            '/' . strtolower($this->module['Name']), 
            $this->getModuleTemplateEntry()
        );
        require_once $template;
    }
    
    /**
     * Установка контекста для (ссылок) постраничного вывода.
     * @return array
     */
    protected function getPaginationContext()
    {
        return array('path' => $this->params->sectionPath);
    }
    
    /**
     * Генерация постраничного вывода.
     */
    protected function renderPagination()
    {
        extract(
            array_merge(
                $this->context, 
                $this->getPaginationContext()
            ), 
            EXTR_PREFIX_SAME, 'model'
        );
        $template = $this->findTemplate(
            $this->templatePath, 
            '/' . strtolower($this->module['Name']), 
            $this->config->templatesPaginationEntry
        );
        include $template;
    }
    
    /**
     * Генерация вставок.
     * @param array $options = null
     */
    protected function renderInsertions(array $options = null)
    {
        $insertions = $this->getInsertions($options);
        extract(
            array(
                'items' => $insertions, 
                'tools' => &$this->tools, 
            ), 
            EXTR_PREFIX_SAME, 'insertions'
        );
        $template = $this->findTemplate(
            $this->templatePath, 
            null, 
            $this->config->templatesInsertionsEntry
        );
        include $template;
    }
    
    /**
     * Получение объектов вставок.
     * @param array $options = null
     * @return array<\Ufocms\Modules\Insertion>
     */
    protected function getInsertions(array $options = null)
    {
        $targetId = $this->params->sectionId;
        $placeId = 0;
        $offset = 0;
        $limit = 0;
        if (is_array($options)) {
            if (array_key_exists('PlaceId', $options)) {
                $placeId = (int) $options['PlaceId'];
            } else {
                $options['PlaceId'] = 0;
            }
            if (array_key_exists('Offset', $options)) {
                $offset = (int) $options['Offset'];
                if ($offset < 1) {
                    $offset = 0;
                } else {
                    $options['Offset'] = 0;
                }
            } else {
                $options['Offset'] = 0;
            }
            if (array_key_exists('Limit', $options)) {
                $limit = (int) $options['Limit'];
                if ($limit < 1) {
                    $limit = 0;
                } else {
                    $options['Limit'] = 0;
                }
            } else {
                $options['Limit'] = 0;
            }
        }
        
        $insertions = array();
        $items = $this->core->getInsertionsData($targetId, $placeId, $offset, $limit);
        foreach ($items as $item) {
            $class = '\\Ufocms\\Modules\\' . ucfirst(substr($item['madmin'], 4)) . '\\Insertion';
            $container = $this->core->getContainer([
                'db'            => &$this->db, 
                'core'          => &$this->core, 
                'config'        => &$this->config, 
                'tools'         => &$this->tools, 
                'debug'         => &$this->debug, 
                'data'          => $item, 
                'options'       => $options, 
                'templateUrl'   => $this->templateUrl, 
            ]);
            $insertions[] = new $class($container);
        }
        return $insertions;
    }
    
    /**
     * Генерация виджетов.
     * @param array $options = null
     */
    protected function renderWidgets(array $options = null)
    {
        $widgets = $this->getWidgets($options);
        extract(
            array(
                'items' => $widgets, 
                'tools' => &$this->tools, 
            ), 
            EXTR_PREFIX_SAME, 'widgets'
        );
        $template = $this->findTemplate(
            $this->templatePath, 
            null, 
            $this->config->templatesWidgetsEntry
        );
        include $template;
    }
    
    /**
     * Получение объектов виджетов.
     * @param array $options = null
     * @return array<\Ufocms\Modules\Widget>
     */
    protected function getWidgets(array $options = null)
    {
        $targetId = $this->params->sectionId;
        $placeId = 0;
        $offset = 0;
        $limit = 0;
        if (is_array($options)) {
            if (array_key_exists('PlaceId', $options)) {
                $placeId = (int) $options['PlaceId'];
            } else {
                $options['PlaceId'] = 0;
            }
            if (array_key_exists('Offset', $options)) {
                $offset = (int) $options['Offset'];
                if ($offset < 1) {
                    $offset = 0;
                } else {
                    $options['Offset'] = 0;
                }
            } else {
                $options['Offset'] = 0;
            }
            if (array_key_exists('Limit', $options)) {
                $limit = (int) $options['Limit'];
                if ($limit < 1) {
                    $limit = 0;
                } else {
                    $options['Limit'] = 0;
                }
            } else {
                $options['Limit'] = 0;
            }
        }
        
        $widgets = array();
        $items = $this->core->getWidgetsData($targetId, $placeId, $offset, $limit);
        foreach ($items as $item) {
            if (0 != $item['ModuleId']) {
                $class = '\\Ufocms\\Modules\\' . ucfirst(substr($item['madmin'], 4)) . '\\Widget' . $item['Name'];
            } else {
                $class = '\\Ufocms\\Modules\\Widgets\\' . $item['Name'];
            }
            if (class_exists($class)) {
                $container = $this->core->getContainer([
                    'debug'         => &$this->debug, 
                    'config'        => &$this->config, 
                    'db'            => &$this->db, 
                    'core'          => &$this->core, 
                    'tools'         => &$this->tools, 
                    'moduleParams'  => &$this->moduleParams, 
                    'data'          => $item, 
                    'options'       => $options, 
                    'templateUrl'   => $this->templateUrl, 
                ]);
                $widgets[] = new $class($container);
            }
        }
        return $widgets;
    }
    
    /**
     * Генерация цитат.
     * @param array $options = null
     */
    protected function renderQuotes(array $options = null)
    {
        if (!is_array($options)) {
            return;
        }
        if (!array_key_exists('GroupId', $options)) {
            return;
        }
        extract(
            array(
                'quote' => $this->core->getQuotes()->get((int) $options['GroupId']), 
                'tools' => &$this->tools, 
            ), 
            EXTR_PREFIX_SAME, 'quotes'
        );
        $template = $this->findTemplate(
            $this->templatePath, 
            null, 
            $this->config->templatesQuotesEntry
        );
        include $template;
    }
    
    /**
     * Генерация HTML кода для заголовочной части (HEAD).
     */
    protected function renderHead()
    {
        extract(
            $this->context, 
            EXTR_PREFIX_SAME, 'model'
        );
        $template = $this->findTemplate(
            $this->templatePath, 
            '/' . strtolower($this->module['Name']), 
            $this->config->templatesHeadEntry
        );
        if (file_exists($template)) {
            include $template;
        }
    }
    
    /**
     * @return string
     */
    protected function getHeadTitle()
    {
        $title = $this->context['section']['title'];
        if (null !== $this->context['item']) {
            if (array_key_exists('Title', $this->context['item'])) {
                $title = $this->context['item']['Title'];
            } else if (array_key_exists('title', $this->context['item'])) {
                $title = $this->context['item']['title'];
            }
        }
        return htmlspecialchars($title . ' - ' . $this->context['site']['SiteTitle']);
    }
    
    /**
     * @return string
     */
    protected function getMetaDesc()
    {
        return htmlspecialchars($this->context['section']['metadesc'] . ' ' . $this->context['site']['SiteMetaDescription']);
    }
    
    /**
     * @return string
     */
    protected function getMetaKeys()
    {
        return htmlspecialchars($this->context['section']['metakeys'] . ' ' . $this->context['site']['SiteMetaKeywords']);
    }
    
    /**
     * Установка контекста для вывода комментариев.
     * @param array $options = null
     * @return array
     */
    protected function getCommentsContext(array $options = null)
    {
        $comments = $this->core->getComments();
        if (null === $this->moduleParams['commentsAdd']) {
            return array(
                'items'         => $comments->getItems(
                    $this->moduleParams['commentsPage'], 
                    $this->config->pageSizeDefault, 
                    null !== $options && array_key_exists('sortDesc', $options) ? $options['sortDesc'] : false
                ), 
                'itemsCount'    => $comments->getItemsCount(), 
                'rating'        => $comments->getRating(), 
                'path'          => $comments->getUrl(), 
                'options'       => $options, 
            );
        } else {
            return array(
                'actionResult'  => $comments->getActionResult(), 
                'error'         => $comments->getError(), 
                'path'          => $comments->getUrl(), 
                'options'       => $options, 
            );
        }
    }
    
    /**
     * Генерация комментариев.
     * @param array $options = null
     */
    protected function renderComments(array $options = null)
    {
        extract(
            array_merge(
                $this->getApplicationContext(), 
                $this->getCommentsContext()
            )
        );
        if (null === $this->moduleParams['commentsAdd']) {
            $entry = $this->config->templatesCommentsEntry;
        } else {
            $entry = $this->config->templatesCommentsAddEntry;
        }
        $template = $this->findTemplate(
            $this->templatePath, 
            '/' . strtolower($this->module['Name']), 
            $entry
        );
        include $template;
    }
    
    /**
     * Установка контекста для (ссылок) постраничного вывода комментариев.
     * @return array
     */
    protected function getCommentsPaginationContext()
    {
        return array(
            'page'          => $this->moduleParams['commentsPage'], 
            'pageSize'      => $this->config->pageSizeDefault, 
            'itemsCount'    => $this->core->getComments()->getItemsCount(), 
            'path'          => $this->core->getComments()->getUrl(), 
        );
    }
    
    /**
     * Генерация постраничного вывода комментариев.
     */
    protected function renderCommentsPagination()
    {
        extract(
            array_merge(
                $this->getApplicationContext(), 
                $this->getCommentsPaginationContext()
            )
        );
        $template = $this->findTemplate(
            $this->templatePath, 
            '/' . strtolower($this->module['Name']), 
            $this->config->templatesCommentsPaginationEntry
        );
        include $template;
    }
    
    /**
     * Установка контекста для вывода комментариев.
     * @param array $options = null
     * @return array
     */
    protected function getInteractionContext(array $options = null)
    {
        if (null === $this->moduleParams['commentsAdd']) {
            $interaction = $this->core->getInteraction();
            return array(
                'items'         => $interaction->getComments(
                    $this->moduleParams['commentsPage'], 
                    $this->config->pageSizeDefault, 
                    null !== $options && array_key_exists('sortDesc', $options) ? $options['sortDesc'] : false
                ), 
                'itemsCount'    => $interaction->getCommentsCount(), 
                'rating'        => $interaction->getRating(), 
                'path'          => $this->params->sectionPath, 
                'options'       => $options, 
            );
        } else {
            $interaction = $this->core->getInteractionManage();
            return array(
                'actionResult'  => $interaction->getActionResult(), 
                'error'         => $interaction->getError(), 
                'path'          => $this->params->sectionPath, 
                'options'       => $options, 
            );
        }
    }
    
    /**
     * Генерация комментариев.
     * @param array $options = null
     */
    protected function renderInteraction(array $options = null)
    {
        extract(
            array_merge(
                $this->getApplicationContext(), 
                $this->getInteractionContext()
            )
        );
        if (null === $this->moduleParams['commentsAdd']) {
            $entry = $this->config->templatesInteractionEntry;
        } else {
            $entry = $this->config->templatesInteractionAddEntry;
        }
        $template = $this->findTemplate(
            $this->templatePath, 
            '/' . strtolower($this->module['Name']), 
            $entry
        );
        include $template;
    }
    
    /**
     * Установка контекста для (ссылок) постраничного вывода комментариев.
     * @return array
     */
    protected function getInteractionPaginationContext()
    {
        return array(
            'page'          => $this->moduleParams['commentsPage'], 
            'pageSize'      => $this->config->pageSizeDefault, 
            'itemsCount'    => $this->core->getInteraction()->getCommentsCount(), 
            'path'          => $this->core->getInteraction()->getUrl(), 
        );
    }
    
    /**
     * Генерация постраничного вывода комментариев.
     */
    protected function renderInteractionPagination()
    {
        extract(
            array_merge(
                $this->getApplicationContext(), 
                $this->getInteractionPaginationContext()
            )
        );
        $template = $this->findTemplate(
            $this->templatePath, 
            '/' . strtolower($this->module['Name']), 
            $this->config->templatesInteractionPaginationEntry
        );
        include $template;
    }
    
    /**
     * Генерация формы входа/выхода и т.п. зарегистрированных пользователей.
     */
    protected function renderUsersForm()
    {
        $users = $this->core->getUsers();
        if (null === $users->getCurrent()) {
            $template = $this->findTemplate($this->templatePath, 'sysusers', '/formlogin.php');
        } else {
            $template = $this->findTemplate($this->templatePath, 'sysusers', '/formlogout.php');
        }
        
        extract(
            array_merge(
                $this->getApplicationContext(), 
                array('path'  => $this->params->pathRaw)
            )
        );
        include $template;
    }
    
    /**
     * Генерация отладочной информации.
     */
    protected function renderDebug()
    {
        if (null === $this->debug) {
            return;
        }
        $template = $this->findTemplate($this->templatePath, '', $this->config->templatesDebugEntry);
        include $template;
    }
}