<?php
require_once __DIR__  . DIRECTORY_SEPARATOR . '_presets.php';

use \Ufocms\Frontend\Config;
use \Ufocms\Frontend\Loader;

class FrontendLoaderTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    
    /**
     * @var Loader
     */
    protected $loader;
    
    protected function _before()
    {
        $this->loader = $this->getLoader();
    }

    protected function _after()
    {
    }
    
    protected function getLoader()
    {
        $config = new Config();
        $config->rootPath = '';
        $config->tmpDir = '';
        return new class($config) extends Loader {
            protected $url = 'php://memory';
            protected $cacheFile = 'php://memory';
            public function getCacheFile()
            {
                return $this->cacheFile;
            }
            public function getUrl()
            {
                return $this->url;
            }
            public function getSocketTimeout()
            {
                return $this->socketTimeout;
            }
            public function getCacheLifetime()
            {
                return $this->cacheLifetime;
            }
            public function callDataChecker($data)
            {
                if (null === $this->dataChecker) {
                    return true;
                }
                return call_user_func($this->dataChecker, $data);
            }
        };
    }

    // tests
    public function testSetUrl()
    {
        $url = '';
        $cf = '/~' . md5($url) . '.txt';
        $this->loader->setUrl($url);
        $this->assertTrue($url == $this->loader->getUrl());
        $this->assertTrue($cf == $this->loader->getCacheFile());
        
        $url = 'http://some-test-url';
        $cf = '/~' . md5($url) . '.txt';
        $this->loader->setUrl($url);
        $this->assertTrue($url == $this->loader->getUrl());
        $this->assertTrue($cf == $this->loader->getCacheFile());
    }
    
    public function testSetSocketTimeout()
    {
        $this->loader->setSocketTimeout(0);
        $this->assertTrue(0 == $this->loader->getSocketTimeout());
        
        $this->loader->setSocketTimeout(999);
        $this->assertTrue(999 == $this->loader->getSocketTimeout());
    }
    
    public function testSetCacheFile()
    {
        $this->loader->setCacheFile('');
        $this->assertTrue('/' == $this->loader->getCacheFile());
        
        $this->loader->setCacheFile('123.tmp');
        $this->assertTrue('/123.tmp' == $this->loader->getCacheFile());
    }
    
    public function testSetCacheLifetime()
    {
        $this->loader->setCacheLifetime(0);
        $this->assertTrue(0 == $this->loader->getCacheLifetime());
        
        $this->loader->setCacheLifetime(999);
        $this->assertTrue(999 == $this->loader->getCacheLifetime());
    }
    
    public function testSetDataChecker()
    {
        $dataChecker = function($data) {
            return 0 < strlen($data);
        };
        
        $data = '';
        $this->loader->setDataChecker($dataChecker);
        $r1 = call_user_func($dataChecker, $data);
        $r2 = $this->loader->callDataChecker($data);
        $this->assertFalse($r1 xor $r2);
        
        $data = '123';
        $this->loader->setDataChecker($dataChecker);
        $r1 = call_user_func($dataChecker, $data);
        $r2 = $this->loader->callDataChecker($data);
        $this->assertFalse($r1 xor $r2);
    }
    
    public function testGetData()
    {
        $this->assertTrue('' == $this->loader->getData());
    }
    
    public function testGetDataFile()
    {
        $this->assertTrue($this->loader->getCacheFile() == $this->loader->getDataFile());
    }
}
