<?php
/**
 * @copyright
 */

namespace Ufocms\Frontend;

/**
 * Main application controller
 */
class Main //implements IController
{
    use ToolsPath;
    
    /**
     * @var Debug
     */
    protected $debug = null;
    
    /**
     * Ссылка на объект конфигурации.
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
     * @var array
     */
    protected $module = null;
    
    /**
     * @param Debug &$debug = null
     */
    public function __construct(&$debug = null)
    {
        $this->debug =& $debug;
        $this->config = new Config();
        $this->params = new Params();
    }
    
    /**
     * Controller dispatcher, make calls for model and view
     */
    public function dispatch()
    {
        if ('' != $this->config->rootPath) {
            ob_start(
                function ($buffer) { 
                    return preg_replace(
                        '/(href=|src=|bachground=)(["\']*)(\/)/i', 
                        '$1$2' . $this->config->rootUrl . '$3', 
                        $buffer
                    ); 
                }
            );
        }
        
        $this->setPathRaw();
        
        $cache = null;
        if (C_CACHE && $this->canUseCache()) {
            $cache = new Cache($this->config, $this->params->pathRaw);
            if (!$cache->expired()) {
                $this->useCache($cache);
            }
        }
        
        try {
            $this->db = new Db($this->debug);
        } catch (\Exception $e) {
            if ($this->canUseCache()) {
                $cache = new Cache($this->config, $this->params->pathRaw);
                $this->useCache($cache);
            }
            $this->riseError(500, 'DB connection failed');
        }
        
        $this->core = new Core($this->config, $this->params, $this->db, $this->debug);
        
        $this->setCurrentSection();
        if (0 == $this->core->getCurrentSection()['isenabled']) {
            $this->core->riseError(403, 'Section disabled'); //exit('403-section-disabled'); //throw new Exception
        }
        if ($this->module['Disabled']) {
            $this->core->riseError(403, 'Module disabled'); //exit('403-module-disabled'); //throw new Exception
        }
        
        //set sectionParams before create controller, it used in controller::init
        if (trim($this->params->sectionPath, '/') != trim($this->params->pathRaw, '/')) {
            $this->params->sectionParams = explode('/', trim(substr($this->params->pathRaw, strlen($this->params->sectionPath)), '/'));
        }
        if (null !== $controller = $this->getController()) {
            $controller->dispatch();
            if (null !== $cache && $this->canUseCache()/* && null === this->debug*/) {
                $cache->save(ob_get_contents());
            }
        } else {
            $this->core->riseError(404, 'Controller not exists'); //exit('404-controller'); //throw new Exception
        }
    }
    
    /**
     * Определяем можно ли использовать кэш.
     * @return bool
     */
    protected function canUseCache()
    {
        return  !$this->config->cacheForbidden 
                && !strpos($this->params->pathRaw, 'action');
    }
    
    /**
     * Загрузка и вывод кэша если есть, выход из скрипта после вывода кэша.
     * @param Cache &$cache
     */
    protected function useCache(Cache &$cache)
    {
        if (false !== $content = $cache->load()) {
            echo $content;
            if (null !== $this->debug) {
                include $this->config->rootPath . 
                        $this->config->templatesDir . $this->config->themeDefault . 
                        $this->config->templatesDebugEntry;
            }
            exit();
        }
    }
    
    /**
     * Разбор пути.
     */
    protected function setPathRaw()
    {
        //mod_rewrite перекидывает реальный путь в строку запроса
        //иначе посетители попадут на 404 ошибку, так что проверять настройку
        //mod_rewrite в .htaccess в корневой папке сайта
        if (isset($_GET['path']) && '' != $_GET['path']) {
            $this->params->pathRaw = $_GET['path'];
            if ($this->isPath($this->params->pathRaw, false)) {
                //BOOKMARK: close slash
                if ('/' == $this->params->pathRaw[strlen($this->params->pathRaw) - 1]) {
                    if (false === strpos($this->params->pathRaw, '.')) {
                        $this->params->pathRaw = substr($this->params->pathRaw, 0, -1);
                    } else {
                        $this->riseError(404, 'File not exists'); //exit('404-is-file'); //throw new Exception
                    }
                }
            } else {
                $this->riseError(404, 'Path not correct'); //exit('404-bad-path'); //throw new Exception
            }
        //ErrorDocument в .htaccess
        } else if (isset($_GET['error'])) {
            $this->riseError((int) $_GET['error'], 'External (non CMS) error'); //exit((int) $_GET['error']); //$this->generateError((int) $_GET['error'], 'External error');
        //иначе это главная страница
        } else {
            $this->params->pathRaw = '/';
        }
    }
    
    /**
     * Проверка является ли текущий раздел служебным.
     * @param string $pathRaw
     * @return array<string $path, string $class>|null
     */
    protected function getSystemModule($pathRaw)
    {
        foreach ($this->config->systemSections as $path => $class) {
            if (0 === strpos($pathRaw, $path)) {
                return array($path, $class);
            }
        }
        return null;
    }
    
    /**
     * Set current section module data by $this->params->sectionId
     */
    protected function setCurrentSection()
    {
        $module = $this->getSystemModule($this->params->pathRaw);
        if (null === $module) {
            $this->params->systemPath = false;
            if ('/' == $this->params->pathRaw) {
                $this->params->sectionPath = '/';
            } else {
                $this->parsePath();
            }
            $this->core->setCurrentSection();
            $module = $this->core->getModule('madmin,isenabled');
            if (null === $module) {
                $this->core->riseError(500, 'Module not present'); //exit('500-module-request'); //throw new Exception
            }
            $moduleDisabled = 0 == $module['isenabled'];
            if (4 > strlen($module['madmin'])) {
                $this->core->riseError(500, 'Module name wrong'); //exit('500-module-name'); //throw new Exception
            }
            $moduleName = ucfirst(substr($module['madmin'], 4));
            $this->params->moduleName = $moduleName;
        } else {
            $this->params->systemPath = true;
            $this->params->sectionPath = $module[0];
            $moduleDisabled = false;
            $moduleName = $module[1];
            $this->params->moduleName = $moduleName;
            $this->core->setCurrentSection();
        }
        $this->module = array(
                            'Disabled'      => $moduleDisabled, 
                            'Name'          => $moduleName, 
                            'Controller'    => '\\Ufocms\\Modules\\' . $moduleName . '\\Controller',  
                            'Model'         => '\\Ufocms\\Modules\\' . $moduleName . '\\Model', 
                            'View'          => '\\Ufocms\\Modules\\' . $moduleName . '\\View', 
                            );
    }
    
    /**
     * Разбор необработанного пути раздела на путь раздела и параметры.
     */
    protected function parsePath()
    {
        //BOOKMARK: close slash
        $path = $this->params->pathRaw . '/';
        
        //определяем присутствует ли путь в БД
        if ($this->core->isPathExists($path)) {
            $this->params->sectionPath = $path;
        //если нет, разбиваем путь по слэшам, чтобы вычленить параметры в пути
        } else {
            //массив частей пути
            $pathParts = explode('/', $path);
            //убираем крайние слэши, чтобы не было лишних элементов в массиве
            array_shift($pathParts);
            //BOOKMARK: close slash
            array_pop($pathParts);
            $pathPartsCount = count($pathParts);
        
            //если вложенность больше допустимой, выходим
            if ($this->config->pathNestingLimit < $pathPartsCount) {
                //вызываем ошибку 404
                $this->core->riseError(404, 'Path too nested'); //exit('404-too-deep'); //throw new Exception
            }
            
            //собираем массив вложенных путей
            //BOOKMARK: close slash
            $paths = array('/');
            for ($i = 0; $i < $pathPartsCount; $i++) {
                $paths[$i + 1] = $paths[$i] . $pathParts[$i] . '/';
            }
            //убираем первый элемент
            array_shift($paths);
            
            if (!$this->params->sectionPath = $this->core->getMaxExistingPath($paths)) {
                $this->core->riseError(404, 'Section not exists'); //exit('404-section-not-exists'); //throw new Exception
            }
        }
    }
    
    /**
     * @return \Ufocms\Modules\Controller|null
     */
    protected function getController()
    {
        $container = new Container([
            'debug'     => &$this->debug, 
            'config'    => &$this->config, 
            'params'    => &$this->params, 
            'db'        => &$this->db, 
            'core'      => &$this->core, 
            'module'    => &$this->module, 
        ]);
        $classBase = '\\Ufocms\\Modules\\Controller';
        if (isset($this->module['Controller'])) {
            $class = $this->module['Controller'];
            if (!class_exists($class)) {
                $class = $classBase;
            }
        } else {
            $class = $classBase;
        }
        return new $class($container);
    }
    
    /**
     * @param int $errNum
     * @param string $errMsg = null
     * @param mixed $options = null
     */
    protected function riseError($errNum, $errMsg = null, $options = null)
    {
        $container = new Container([
            'debug'         => &$this->debug, 
            'config'        => &$this->config, 
            'params'        => &$this->params, 
            'db'            => null, 
            'core'          => null, 
        ]);
        $error = new Error($container);
        $error->rise($errNum, $errMsg, $options);
    }
}
