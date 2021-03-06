<?php
/**
 * UFOCMS v2 Content Management System
 * 
 * @copyright   Copyright (C) 2005 - 2017 Enikeishik <enikeishik@gmail.com>. All rights reserved.
 * @author      Enikeishik <enikeishik@gmail.com>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Ufocms\Frontend;

/**
 * Класс конфигурации.
 */
class Config extends Struct
{
    /**
     * Путь к корню сайта.
     * @var string
     */
    public $rootPath = '';
    
    /**
     * Путь к корню сайта для URL, формируется из rootPath.
     * @var string
     */
    public $rootUrl = '';
    
    /**
     * Путь и префикс файла протокола ошибок.
     * @var string
     */
    public $logError = '/logs/er';
    
    /**
     * Путь и префикс файла протокола предупреждений.
     * @var string
     */
    public $logWarnings = '/logs/wr';
    
    /**
     * Путь и префикс файла протокола производительности.
     * @var string
     */
    public $logPerformance = '/logs/pr';
    
    /**
     * Путь и префикс файла протокола отладки.
     * @var string
     */
    public $logDebug = '/logs/dg';
    
    /**
     * Путь к папке временных файлов.
     * @var string
     */
    public $tmpDir = '/tmp';
    
    /**
     * Путь к папке кэша.
     * @var string
     */
    public $cacheDir = '/cache';
    
    /**
     * Путь к папке административной части сайта.
     * @var string
     */
    public $adminDir = '/admin';
    
    /**
     * Время жизни кэша.
     * @var int
     */
    public $cacheLifeTime = 3;
    
    /**
     * Время хранения кэша.
     * @var int
     */
    public $cacheSaveTime = 3600;
    
    /**
     * Флаг запрета кэширования, устанавливается модулем при необходимости запретить кэширование.
     * @var bool
     */
    public $cacheForbidden = false;
    
    /**
     * Папка статичного контента.
     * @var string
     */
    public $staticDir = '/files';
    
    /**
     * Папка статичного контента для URL, формируется из staticDir.
     * @var string
     */
    public $staticUrl = '';
    
    /**
     * Маска создаваемых папок для статичного контента.
     * @var int
     */
    public $staticDirMode = 0777;
    
    /**
     * Маска создаваемых файлов статичного контента.
     * @var int
     */
    public $staticFileMode = 0666;
    
    /**
     * Максимальный размер загружаемого (статичного) файла.
     * @var int|float
     */
    public $uploadFileMaxSize = 10000000;
    
    /**
     * Путь к файлу XmlSitemap.
     * @var string
     */
    public $xmlSitemapPath = '/sitemap.xml';
    
    /**
     * Служебные разделы сайта (карта сайта, поиск, пользователи и т.п.).
     * @var array(path => className)
     */
    public $systemSections = array(
        '/users'     => 'SysUsers', 
        '/sitemap'   => 'SysSitemap', 
        '/sendform'  => 'SysSendform', 
        '/search'    => 'SysSearch', 
        '/ulogin'    => 'SysULogin', 
        '/modules'   => 'SysModules', 
    );
    
    /**
     * Максимальный уровень вложенности в пути раздела (учитывая параметры передаваемые как часть пути).
     * @var int
     */
    public $pathNestingLimit = 10;
    
    /**
     * Количество символов на уровень вложенности при формировании маски раздела.
     * @var int
     */
    public $maskCharsPerLevel = 4;
    
    /**
     * Папка шаблонов (шаблоны сгруппированы по папкам тем)
     * @var string
     */
    public $templatesDir = '/templates';
    
    /**
     * Файл конфигурации сайта
     * @var string
     */
    public $siteConfig = '/config.php';
    
    /**
     * Папка темы по-умолчанию
     * @var string
     */
    public $themeDefault = '/default';
    
    /**
     * Файл конфигурации темы
     * @var string
     */
    public $themeConfig = '/config.php';
    
    /**
     * Имя параметра (GET, COOKIE) определяющего стиль темы
     * @var string
     */
    public $themeStyleParam = 'themestyle';
    
    /**
     * Допустимые значения стилей темы
     * @var array<string>
     */
    public $themeStylesAllowed = array(
        'red', 
        'green', 
        'blue', 
        'yellow', 
        'orange', 
        'brown', 
        'light', 
        'dark', 
        'night', 
        'special', 
    );
    
    /**
     * Время жизни выбранного стиля темы
     * @var int
     */
    public $themeStyleLifetime = 3600 * 24 * 30;
    
    /**
     * Папка шаблонов по-умолчанию
     * @var string
     */
    public $templateDefault = '/default';
    
    /**
     * Папка шаблонов ошибок
     * @var string
     */
    public $templatesErrors = '/syserrors';
    
    /**
     * Точка входа, основной макет страниц
     * @var string
     */
    public $templatesEntry = '/index.php';
    
    /**
     * Точка входа, макет страницы RSS потока
     * Без лидирующего слеша, каждый модуль должен сам предоставить этот макет
     * @var string
     */
    public $templatesRssEntry = 'rss.php';
    
    /**
     * Точка входа шаблона отладки
     * @var string
     */
    public $templatesDebugEntry = '/debug.php';
    
    /**
     * Точка входа шаблона для отображения меню
     * @var string
     */
    public $templatesMenuEntry = '/menu.php';
    
    /**
     * Точка входа шаблона для отображения ссылок текущего раздела
     * @var string
     */
    public $templatesLinksEntry = '/links.php';
    
    /**
     * Точка входа шаблона для отображения постраничного вывода
     * @var string
     */
    public $templatesPaginationEntry = '/pagination.php';
    
    /**
     * Точка входа шаблона для отображения комментариев
     * @var string
     */
    public $templatesCommentsEntry = '/comments.php';
    
    /**
     * Точка входа шаблона для отображения добавления комментария
     * @var string
     */
    public $templatesCommentsAddEntry = '/commentsadd.php';
    
    /**
     * Точка входа шаблона для отображения добавления комментария
     * @var string
     */
    public $templatesCommentsPaginationEntry = '/commentspg.php';
    
    /**
     * Точка входа шаблона для отображения комментариев
     * @var string
     */
    public $templatesInteractionEntry = '/interaction/index.php';
    
    /**
     * Точка входа шаблона для отображения добавления комментария
     * @var string
     */
    public $templatesInteractionAddEntry = '/interaction/add.php';
    
    /**
     * Точка входа шаблона для отображения добавления комментария
     * @var string
     */
    public $templatesInteractionPaginationEntry = '/interaction/pagination.php';
    
    /**
     * Точка входа шаблона для отображения кода в области HEAD
     * @var string
     */
    public $templatesHeadEntry = '/head.php';
    
    /**
     * Точка входа шаблона для отображения конкретного элемента
     * @var string
     */
    public $templatesItemEntry = '/item.php';
    
    /**
     * Точка входа шаблона для отображения результата действия
     * @var string
     */
    public $templatesResultEntry = '/result.php';
    
    /**
     * Точка входа шаблона для отображения списка вставок
     * @var string
     * @deprecated
     */
    public $templatesInsertionsEntry = '/insertions.php';
    
    /**
     * Точка входа шаблона для отображения вставки
     * @var string
     * @deprecated
     */
    public $templatesInsertionEntry = '/insertion.php';
    
    /**
     * Точка входа шаблона для отображения списка виджетов
     * @var string
     */
    public $templatesWidgetsEntry = '/widgets.php';
    
    /**
     * Точка входа шаблона для отображения цитат
     * @var string
     */
    public $templatesQuotesEntry = '/quotes.php';
    
    /**
     * Точка входа шаблона для отображения CAPTCHA
     * @var string
     */
    public $templatesCaptchaEntry = '/captcha.php';
    
    /**
     * Минимальное значение параметра $page
     * @var int
     */
    public $pageMin = 1;
    
    /**
     * Максимальное значение параметра $page
     * @var int
     */
    public $pageMax = 1000;
    
    /**
     * Значение параметра $page по-умолчанию
     * @var int
     */
    public $pageDefault = 1;
    
    /**
     * Минимальное значение параметра $pageSize
     * @var int
     */
    public $pageSizeMin = 1;
    
    /**
     * Максимальное значение параметра $pageSize
     * @var int
     */
    public $pageSizeMax = 1000;
    
    /**
     * Значение параметра $pageSize по-умолчанию
     * @var int
     */
    public $pageSizeDefault = 10;
    
    /**
     * Конструктор. Инициализирует вспомогательные объекты-структуры для хранения связанных параметров.
     * Поскольку в конструкторе инициализируются объекты, которые могут не содержать возможности для изменения своих полей, это единственная возможность для их переопределения.
     * 
     * @see parent
     */
    public function __construct($vars = null, $cast = true)
    {
        $this->rootUrl = $this->rootPath;
        $this->rootPath = $_SERVER['DOCUMENT_ROOT'] . $this->rootPath;
        $this->staticUrl = $this->staticDir;
        $this->staticDir = $_SERVER['DOCUMENT_ROOT'] . $this->staticDir;
        parent::__construct($vars, $cast);
        $this->load($this->rootPath . $this->templatesDir . $this->siteConfig);
    }
    
    /**
     * Загрузка дополнительной конфигурации из файла конфигурации.
     * @param string $configPath
     * @param bool $overwrite = false
     */
    public function load($configPath, $overwrite = false)
    {
        if (!file_exists($configPath)) {
            return;
        }
        $cfg = include_once $configPath;
        if (!is_array($cfg) && !is_object($cfg)) {
            return;
        }
        if (is_object($cfg)) {
            $cfg = get_object_vars($cfg);
        }
        $this->loadArray($cfg, $overwrite);
    }
    
    /**
     * Загрузка дополнительной конфигурации из файла конфигурации по-умолчанию и файла целевой конфигурации.
     * @param string $defaultConfigPath
     * @param string $configPath
     */
    public function loadWithDefault($defaultConfigPath, $configPath)
    {
        if (!file_exists($configPath)) {
            return;
        }
        $cfgDefault = include_once $defaultConfigPath;
        $cfg = include_once $configPath;
        if (!is_array($cfgDefault) && !is_array($cfg)) {
            return;
        }
        $this->loadArray(array_merge($cfgDefault, $cfg));
    }
    
    /**
     * Загрузка дополнительной конфигурации из массива.
     * @param array $cfg
     * @param bool $overwrite = false
     */
    public function loadArray(array $cfg, $overwrite = false)
    {
        foreach ($cfg as $name => $value) {
            if (!$overwrite && property_exists($this, $name)) {
                continue;
            }
            $this->$name = $value;
        }
    }
}
