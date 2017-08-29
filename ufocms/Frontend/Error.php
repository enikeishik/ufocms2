<?php
/**
 * @copyright
 */

namespace Ufocms\Frontend;

/**
 * Error generation methods
 */
class Error extends DIObject
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
     * Распаковка контейнера.
     */
    protected function unpackContainer()
    {
        $this->debug =& $this->container->getRef('debug');
        $this->config =& $this->container->getRef('config');
        $this->params =& $this->container->getRef('params');
        $this->db =& $this->container->getRef('db');
        $this->core =& $this->container->getRef('core');
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
            'config'        => &$this->config, 
            'params'        => &$this->params, 
            'site'          => (null !== $this->core ? $this->core->getSite() : null), 
        );
    }
    
    /**
     * @param string $templateName
     * @return string|null
     */
    protected function getTemplate($templateName)
    {
        $templateDefault =  $this->config->rootPath . 
                            $this->config->templatesDir . $this->config->themeDefault . 
                            $this->config->templatesErrors . 
                            '/' . $templateName . '.php';
        if (defined('C_THEME') && '' != C_THEME) {
            $template = $this->config->rootPath . 
                        $this->config->templatesDir . '/' . C_THEME . 
                        $this->config->templatesErrors . 
                        '/' . $templateName . '.php';
            if (file_exists($template)) {
                return $template;
            } else if (file_exists($templateDefault)) {
                return $templateDefault;
            } else {
                return null;
            }
        } else {
            if (file_exists($templateDefault)) {
                return $templateDefault;
            } else {
                return null;
            }
        }
    }
    
    /**
     * @param int $errNum
     * @param string $errMsg = null
     * @param mixed $options = null
     */
    public function rise($errNum, $errMsg = null, $options = null)
    {
        //prepare context
        $context = array('errNum' => $errNum, 'errMsg' => $errMsg);
        if (301 == $errNum && is_string($options)) {
            $context['location'] = $options;
        }
        extract(array_merge(
            $this->getApplicationContext(), 
            $context
        ));
        
        //close and clear
        if (null !== $this->db) {
            @$this->db->close();
        }
        @ob_end_clean();
        
        //ouput
        $template = $this->getTemplate($errNum);
        if (null !== $template) {
            include $template;
            exit();
        } else if (null !== $errMsg) {
            exit($errMsg);
        } else {
            exit();
        }
    }
}
