<?php
define('ROOT', '../../');
require ROOT . 'ufocms/Frontend/Struct.php';
require ROOT . 'ufocms/Frontend/Config.php';
require ROOT . 'ufocms/Frontend/Cache.php';

use PHPUnit\Framework\TestCase;
use Ufocms\Frontend\Config;
use Ufocms\Frontend\Cache;
 
class CacheTests extends TestCase
{
    protected $cache = null;
    
    protected function setUp()
    {
        $config = new Config();
        $hash = '';
        $this->cache = new Cache($config, $hash);
    }
    
    protected function tearDown()
    {
        $this->cache = null;
    }
    
    public function testLoad()
    {
        $result = $this->cache->load();
        $this->assertEquals(false, $result);
    }
    
    public function testExists()
    {
        $result = $this->cache->exists();
        $this->assertEquals(false, $result);
    }
    
    public function testExpired()
    {
        $result = $this->cache->expired();
        $this->assertEquals(true, $result);
    }
}
