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
        $this->model = $this->getModel();
    }
    
    protected function getModel($withCore = false)
    {
        $params = new Params();
        $params->sectionId = -1;
        $db = new Db();
        $core = null;
        if ($withCore) {
            $core = $this->getCore($db);
        }
        $container = new Container([
            'db'        => &$db, 
            'core'      => &$core, 
            'params'    => &$params,
        ]);
        return new Model($container);
    }
    
    protected function getCore(&$db)
    {
        $config = $this->makeEmpty(new Config());
        $params = new Params();
        return new Core($config, $params, $db);
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
