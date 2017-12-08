<?php
/**
 * @copyright
 */

namespace Ufocms\Frontend;

/**
 * Класс для загрузки данных извне и кэшировании.
 */
class Loader
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
     * @var string
     */
    protected $url = '';
    
    /**
     * @var int
     */
    protected $socketTimeout = 1;
    
    /**
     * @var string
     */
    protected $cacheFile = '/~cache.txt';
    
    /**
     * @var int
     */
    protected $cacheLifetime = 3600;
    
    /**
     * @var callable
     */
    protected $dataChecker = null;
    
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
        $this->cacheFile = $this->config->rootPath . $this->config->tmpDir . $this->cacheFile;
    }
    
    /**
     * Установка параметра url.
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
        $this->cacheFile = $this->config->rootPath . $this->config->tmpDir . '/~' . md5($url) . '.txt';
    }
    
    /**
     * Установка параметра socketTimeout.
     * @param string $socketTimeout
     */
    public function setSocketTimeout($socketTimeout)
    {
        $this->socketTimeout = $socketTimeout;
    }
    
    /**
     * Установка параметра cacheFile.
     * @param string $cacheFileName
     */
    public function setCacheFile($cacheFileName)
    {
        $this->cacheFile = $this->config->rootPath . $this->config->tmpDir . '/' . $cacheFileName;
    }
    
    /**
     * Установка параметра cacheLifetime.
     * @param string $cacheLifetime
     */
    public function setCacheLifetime($cacheLifetime)
    {
        $this->cacheLifetime = $cacheLifetime;
    }
    
    /**
     * Установка параметра dataChecker.
     * @param callable $dataChecker
     */
    public function setDataChecker(callable $dataChecker)
    {
        $this->dataChecker = $dataChecker;
    }
    
    /**
     * Получение данных.
     * @return string
     */
    public function getData()
    {
        $this->prepareData();
        return @file_get_contents($this->cacheFile);
    }
    
    /**
     * Получение пути к файлу с данными (напр. для xml->load).
     * @return string
     */
    public function getDataFile()
    {
        $this->prepareData();
        return $this->cacheFile;
    }
    
    /**
     * Подготовка данных: загрузка и кэширование.
     */
    protected function prepareData()
    {
        if ($this->cacheExpired()) {
            $data = $this->loadData();
            if (false !== $data) {
                $this->cache($data);
            } else {
                //set filetime to now, to prevent DDOS
                touch($this->cacheFile);
            }
        }
    }
    
    /**
     * Проверка устаревания кэша.
     * @return bool
     */
    protected function cacheExpired()
    {
        clearstatcache();
        if (!file_exists($this->cacheFile)) {
            return true;
        }
        /* TODO: check on *nix systems!
        echo time() - filemtime($this->cacheFile);
        echo '<br>';
        echo time() - filectime($this->cacheFile);
        */
        return $this->cacheLifetime < (time() - filemtime($this->cacheFile));
    }
    
    /**
     * Загрузка данных.
     * @return string|false
     */
    protected function loadData()
    {
        $context = stream_context_create(array('http' => array('header' => 'Connection: close', 'timeout' => $this->socketTimeout)));
        return @file_get_contents($this->url, false, $context);
    }
    
    /**
     * Кэширование загруженных данных.
     * @param string $data
     * @return bool
     */
    protected function cache($data)
    {
        //если данных нет, не обновляем файл кэша
        if ('' == $data) {
            return false;
        }
        if (null !== $this->dataChecker && !@call_user_func($this->dataChecker, $data)) {
            return false;
        }
        if (false === $handle = @fopen($this->cacheFile, 'w')) {
            return false;
        }
        $ret = @fwrite($handle, $data);
        @fclose($handle);
        return $ret !== false;
    }
}
