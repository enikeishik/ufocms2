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
     * @todo replace exit with exception
     */
    public function rise($errNum, $errMsg = null, $options = null)
    {
        //prepare context
        $context = array('errNum' => $errNum, 'errMsg' => $errMsg);
        if ((301 == $errNum || 302 == $errNum) && is_string($options)) {
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
        
        //log
        $this->writeLog($errNum, $errMsg, $options);
        
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
    
    /**
     * Write log.
     * @param int $errNum
     * @param string $errMsg
     * @param mixed $options
     */
    protected function writeLog($errNum, $errMsg, $options)
    {
        $timestamp = date('Y.m.d H:i:s') . "\t" . microtime() . "\t";
        $systemInfo =   $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . "\t" .
                        $_SERVER['REMOTE_ADDR'] . ':' . $_SERVER['REMOTE_PORT'] . "\t" .
                        (isset($_SERVER['HTTP_CONNECTION']) ? $_SERVER['HTTP_CONNECTION'] : '') . "\t" . 
                        $_SERVER['REQUEST_METHOD'] . "\t" . 
                        $_SERVER['REQUEST_URI'] . "\t" . 
                        (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '') . "\t" . 
                        $_SERVER['PHP_SELF'] . "\t" . 
                        $_SERVER['SCRIPT_NAME'] . "\t" . 
                        $_SERVER['QUERY_STRING'] . "\t" . 
                        (isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '') . "\t" . 
                        (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : '') . "\t" . 
                        (isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : '');
        
        if (null === $options) {
            $options = '';
        }
        $data = $timestamp . "info\tsystem\t\t" . $systemInfo . "\r\n" . 
                $timestamp . "info\tufocms\t\t" . $errNum . "\t" . $errMsg . "\t" . (is_scalar($options) ? $options : 'options structured') . "\r\n";
        
        if (500 > $errNum) {
            $logType = $this->config->logWarnings;
        } else {
            $logType = $this->config->logError;
        }
        if ('' == $logType) {
            return;
        }
        
        if($fhnd = @fopen($this->config->rootPath . $logType . date('ymd') . '.log', 'a')) {
            @fwrite($fhnd, $data);
        }
    }
}
