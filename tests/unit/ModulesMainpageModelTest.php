<?php
require_once __DIR__  . DIRECTORY_SEPARATOR . '_presets.php';
require_once 'ModulesAbstractModelTest.php';

use \Ufocms\Frontend\Config;
use \Ufocms\Frontend\Container;
use \Ufocms\Frontend\Core;
use \Ufocms\Frontend\Db;
use \Ufocms\Frontend\Params;
use \Ufocms\Modules\Mainpage\Model;

class ModulesMainpageModelTest extends ModulesAbstractModelTest
{
    protected function _before()
    {
        $this->db = new Db();
        $this->model = $this->getModel();
    }
    
    protected function _after()
    {
        if (null !== $this->db) {
            $this->db->close();
            $this->db = null;
        }
    }
    
    protected function getModel($withCore = false)
    {
        $params = new Params();
        $params->sectionId = -1;
        $core = null;
        if ($withCore) {
            $core = $this->getCore();
        }
        $container = new Container([
            'db'        => &$this->db, 
            'core'      => &$core, 
            'params'    => &$params,
        ]);
        return new Model($container);
    }
    
    protected function getCore()
    {
        $config = $this->makeEmpty(new Config());
        $params = new Params();
        return new Core($config, $params, $this->db);
    }
    
    // tests
    public function testGetItem()
    {
        $item = $this->model->getItem();
        $this->assertNotNull($item);
        $this->assertTrue(is_array($item));
        $this->assertTrue(array_key_exists('id', $item));
        $this->assertTrue(array_key_exists('body', $item));
        $this->assertTrue(1 == $item['id']);
    }
    
    public function testGetSections()
    {
        $model = $this->getModel(true);
        $items = $model->getSections();
        $this->assertNotNull($items);
        $this->assertTrue(is_array($items));
        $this->assertTrue(1 == count($items));
    }
}
