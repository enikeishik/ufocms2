<?php
/**
 * UFOCMS v2 Content Management System
 * 
 * @copyright   Copyright (C) 2005 - 2017 Enikeishik <enikeishik@gmail.com>. All rights reserved.
 * @author      Enikeishik <enikeishik@gmail.com>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Ufocms\Backend;

/**
 * Audit users actions
 */
class Audit
{
    /**
     * Размер буфера.
     */
    const BUFFER_SIZE = 10000;
    
    /**
     * Ссылка на объект конфигурации.
     * @var Config
     */
    protected $config = null;
    
    /**
     * Идентификаторо текущего пользователя.
     * @var int
     */
    protected $userId = null;
    
    /**
     * Логин текущего пользователя.
     * @var string
     */
    protected $userLogin = null;
    
    /**
     * Внутренний буфер данных для записи.
     * @var string
     */
    protected $buffer = null;
    
    /**
     * @param Config &$config
     */
    public function __construct(&$config)
    {
        $this->config =& $config;
        $this->userId = 0;
        $this->userLogin = '';
        $this->buffer = '';
    }
    
    public function __destruct()
    {
        if (0 < strlen($this->buffer)) {
            $this->write();
        }
    }
    
    /**
     * @param int $userId
     * @param string $userLogin
     */
    public function setUser($userId, $userLogin)
    {
        $this->userId = $userId;
        $this->userLogin = $userLogin;
    }
    
    /**
     * @param string $data
     */
    public function record($data)
    {
        if (
            0 == $this->config->auditLevel
            && (0 === stripos($data, 'SELECT ') 
                || 0 === stripos($data, 'SET NAMES ') 
                || 0 === stripos($data, 'SHOW TABLES'))
        ) {
            return;
        }
        
        $timestamp = date('Y.m.d H:i:s') . "\t" . microtime() . "\t";
        $systemInfo =   $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . "\t" .
                        $_SERVER['REMOTE_ADDR'] . ':' . $_SERVER['REMOTE_PORT'] . "\t" .
                        (isset($_SERVER['HTTP_CONNECTION']) ? $_SERVER['HTTP_CONNECTION'] : '') . "\t" . 
                        $_SERVER['REQUEST_METHOD'] . "\t" . 
                        $_SERVER['PHP_SELF'] . "\t" . 
                        $_SERVER['SCRIPT_NAME'] . "\t" . 
                        (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '') . "\t" . 
                        $_SERVER['QUERY_STRING'] . "\t" . 
                        $_SERVER['HTTP_USER_AGENT'] . "\t" . 
                        $_SERVER['HTTP_ACCEPT_LANGUAGE'] . "\t" . 
                        (isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : '');
        
        $this->buffer .=    $timestamp . "info\tsystem\t\t" . $systemInfo . PHP_EOL . 
                            $timestamp . "info\tufocms\t\t" . $this->userId . "\t" . $this->userLogin . PHP_EOL . 
                            $timestamp . "info\tufocms\t\t" . str_replace(["\r", "\n"], ' ', $data) . PHP_EOL . 
                            PHP_EOL;
        
        if (self::BUFFER_SIZE < strlen($this->buffer)) {
            $this->write();
            $this->cleanBuffer();
        }
    }
    
    /**
     * Write buffer to file.
     * @return int|false
     */
    protected function write()
    {
        if($fhnd = fopen($this->config->rootPath . $this->config->logAudit . date('ymd') . '.log', 'a')) {
            return @fwrite($fhnd, $this->buffer);
        }
        return false;
    }
    
    /**
     * Clean buffer.
     */
    protected function cleanBuffer()
    {
        $this->buffer = '';
    }
}
