<?php
require_once __DIR__  . DIRECTORY_SEPARATOR . '_presets.php';

use \Ufocms\Frontend\Config;
use \Ufocms\Frontend\Db;
use \Ufocms\Frontend\XmlSitemap;

class FrontendXmlsitemapTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    
    /**
     * @var XmlSitemap
     */
    protected $xmlSitemap;
    
    protected function _before()
    {
        $this->xmlSitemap = $this->getXmlSitemap();
    }
    
    protected function getXmlSitemap()
    {
        $_SERVER['SERVER_NAME'] = 'test';
        $_SERVER['HTTP_REFERER'] = 'test';
        $_SERVER['HTTP_HOST'] = 'test';
        $config = new Config();
        $db = new Db();
        $config->rootPath = '';
        $config->xmlSitemapPath = 'php://memory';
        return new class($config, $db) extends XmlSitemap {
            public function getBuffer()
            {
                return $this->buffer;
            }
            public function getItemsCount()
            {
                return $this->itemsCounter;
            }
        };
    }

    protected function _after()
    {
    }

    // tests
    public function testCheck()
    {
        $this->assertTrue($this->xmlSitemap->check());
    }
    
    public function testGenerate()
    {
        ob_start();
        $this->xmlSitemap->generate();
        $out = ob_get_clean();
        $this->assertNotFalse(strpos($out, 'Building complete'));
    }
    
    public function testOutput()
    {
        $bl = strlen($this->xmlSitemap->getBuffer());
        $this->xmlSitemap->output('123');
        $this->assertTrue(3 == (strlen($this->xmlSitemap->getBuffer()) - $bl));
    }
    
    public function testXmlItem()
    {
        $path = '/123-some-path-to-check-test-456'; //remove back slash!
        $xml = $this->xmlSitemap->xmlItem(['path' => $path]);
        $this->assertNotFalse(strpos($xml, '<url><loc>http://' . $_SERVER['SERVER_NAME'] . $path . '</loc>'));
    }
    
    public function testXmlItems()
    {
        $path = '/123-some-path-to-check-test-456'; //remove back slash!
        $items = [
            ['path' => '/'], 
            ['path' => '/test'], 
            ['path' => $path], 
        ];
        $params = [
            'lastmod'       => 'lastmod-test-value', 
            'changefreq'    => 'changefreq-test-value', 
            'priority'      => 'priority-test-value', 
        ];
        $xml = $this->xmlSitemap->xmlItems($params, $items);
        $this->assertNotFalse(strpos($xml, '<url><loc>http://' . $_SERVER['SERVER_NAME'] . $path . '</loc>'));
        $this->assertNotFalse(strpos($xml, '<lastmod>lastmod-test-value</lastmod>'));
        $this->assertNotFalse(strpos($xml, '<changefreq>changefreq-test-value</changefreq>'));
        $this->assertNotFalse(strpos($xml, '<priority>priority-test-value</priority>'));
    }
    
    public function testIncItemsCounter()
    {
        $ic = $this->xmlSitemap->getItemsCount();
        $this->xmlSitemap->incItemsCounter(3);
        $this->assertTrue(3 == ($this->xmlSitemap->getItemsCount() - $ic));
    }
}
