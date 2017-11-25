<?php
/**
 * @copyright
 */

namespace Ufocms\Frontend;

/**
 * Класс для работы со стеком.
 * Формат хранения данных:
 * timestamp TAB[ key TAB] data
 */
class Stack
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
     * Файл стека, хранит временные ключи картинок.
     * @var string
     */
    protected $stackFile = null;
    
    /**
     * Время жизни ключей в стеке.
     * @var string
     */
    protected $stackLifetime = null;
    
    /**
     * Конструктор.
     * @param Config &$config = null
     * @param Debug &$debug = null
     */
    public function __construct(&$config = null, &$debug = null)
    {
        $this->debug =& $debug;
        if (null === $config) {
            $this->config = new Config();
        } else {
            $this->config =& $config;
        }
        $this->stackFile = $this->config->rootPath . $this->config->tmpDir . '/~stack.txt';
        $this->stackLifetime = 600;
    }
    
    /**
     * Установка параметров конфигурации.
     * @param string $fileName
     * @param int $lifeTime
     */
    public function set($fileName, $lifeTime)
    {
        $this->stackFile = $this->config->rootPath . $this->config->tmpDir . '/' . $fileName;
        $this->stackLifetime = $lifeTime;
    }
    
    /**
     * Получение данных стека.
     * @return array|false
     */
    public function getData()
    {
        if (!is_readable($this->stackFile)) {
            return false;
        }
        $data = file_get_contents($this->stackFile);
        if (!$data) {
            return false;
        }
        return explode("\n", $data);
    }
    
    /**
     * Проверка наличия данных в стеке.
     * @param string $data
     * @return bool
     */
    public function dataExists($data)
    {
        if (false === $arr = $this->getData()) {
            return false;
        }
        for ($i = 0, $cnt = count($arr); $i < $cnt; $i++) {
            $item = explode("\t", $arr[$i]);
            if (1 < count($item)) {
                if ($data == $item[1]) {
                    return true;
                }
            }
        }
        return false;
    }
    
    /**
     * Получение данных по ключу.
     * @param string $key
     * @return string|false
     */
    public function getDataByKey($key)
    {
        if ('' == $key) {
            return false;
        }
        if (false === $arr = $this->getData()) {
            return false;
        }
        for ($i = 0, $cnt = count($arr); $i < $cnt; $i++) {
            $item = explode("\t", $arr[$i]);
            if (3 == count($item)) {
                if ($key == $item[1]) {
                    return $item[2];
                }
            }
        }
        return false;
    }
    
    /**
     * Добавление данных в стек.
     * @param string $data
     * @param string $key = null
     * @return bool
     */
    public function push($data, $key = null)
    {
        if (!$handle = fopen($this->stackFile, 'a')) {
            return false;
        }
        $written = false;
        if (flock($handle, LOCK_EX)) {
            if (null === $key) {
                fwrite($handle, time() . "\t" . $data . "\n");
            } else {
                fwrite($handle, time() . "\t" . $key . "\t" . $data . "\n");
            }
            fflush($handle);
            flock($handle, LOCK_UN);
            $written = true;
        }
        fclose($handle);
        return $written;
    }
    
    /**
     * Удаление данных из стека по ключу.
     * @param string $key
     * @return bool
     */
    public function remove($key)
    {
        if (false === $arr = $this->getData()) {
            return false;
        }
        
        $arr_ = array();
        for ($i = 0, $cnt = count($arr); $i < $cnt; $i++) {
            $item = explode("\t", $arr[$i]);
            if (3 == count($item)) {
                if ($item[1] != $key) {
                    $arr_[] = $arr[$i] . "\n";
                }
            }
        }
        
        if (!$handle = fopen($this->stackFile, 'w')) {
            return false;
        }
        $written = false;
        if (flock($handle, LOCK_EX)) {
            fwrite($handle, implode('', $arr_));
            fflush($handle);
            flock($handle, LOCK_UN);
            $written = true;
        }
        fclose($handle);
        return $written;
    }
    
    /**
     * Удаление данных из стека с устаревшими метками времени.
     * @return bool
     */
    public function clearOld()
    {
        clearstatcache();
        if (!file_exists($this->stackFile)) {
            return false;
        }
        /* TODO: check on *nix systems!
        echo time() - filemtime($this->stackFile);
        echo '<br>';
        echo time() - filectime($this->stackFile);
        */
        if ($this->stackLifetime > (time() - filemtime($this->stackFile))) {
            return false;
        }
        $oldTimestamp = time() - $this->stackLifetime;
        if (false === $arr = $this->getData()) {
            return false;
        }
        $arr_ = array();
        for ($i = 0, $cnt = count($arr); $i < $cnt; $i++) {
            $item = explode("\t", $arr[$i]);
            if (1 < count($item)) {
                if (((int) $item[0]) > $oldTimestamp) {
                    $arr_[] = $arr[$i] . "\n";
                }
            }
        }
        
        if (!$handle = fopen($this->stackFile, 'w')) {
            return false;
        }
        $written = false;
        if (flock($handle, LOCK_EX | LOCK_NB)) {
            fwrite($handle, implode('', $arr_));
            fflush($handle);
            flock($handle, LOCK_UN);
            $written = true;
        }
        fclose($handle);
        return $written;
    }
}
