<?php
require_once __DIR__  . DIRECTORY_SEPARATOR . '_presets.php';

use \Ufocms\Frontend\Config;
use \Ufocms\Frontend\Cache;
 
class FrontendCacheTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    
    /**
     * @var string
     */
    protected $tmpfname;
    
    /**
     * @var Cache
     */
    protected $cache;
    
    protected function _before()
    {
        $config = new Config();
        $config->rootPath = '';
        $config->cacheDir = sys_get_temp_dir();
        $hash = 'tmp' . time();
        $this->tmpfname = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $hash;
        $this->cache = new Cache($config, $hash);
    }
    
    protected function _after()
    {
        $this->cache = null;
        if (file_exists($this->tmpfname)) {
            unlink($this->tmpfname);
        }
    }
    
    // tests
    
    public function testLoad()
    {
        $result = $this->cache->load();
        $this->assertFalse($result);
        
        $result = $this->cache->save('test cache data');
        $this->assertTrue($result);
        $result = $this->cache->exists();
        $this->assertTrue($result);
        $result = $this->cache->expired();
        $this->assertFalse($result);
        $result = $this->cache->load();
        $this->assertEquals('test cache data', $result);
    }
    
    public function testSave()
    {
        $result = $this->cache->save('test cache data');
        $this->assertTrue($result);
    }
    
    public function testExists()
    {
        $result = $this->cache->exists();
        $this->assertFalse($result);
        
        $result = $this->cache->save('test cache data');
        $this->assertTrue($result);
        $result = $this->cache->exists();
        $this->assertTrue($result);
    }
    
    public function testExpired()
    {
        $result = $this->cache->expired();
        $this->assertTrue($result);
        
        $result = $this->cache->save('test cache data');
        $this->assertTrue($result);
        $result = $this->cache->exists();
        $this->assertTrue($result);
        $result = $this->cache->expired();
        $this->assertFalse($result);
    }
}
