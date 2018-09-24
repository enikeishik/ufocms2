<?php
require_once __DIR__  . DIRECTORY_SEPARATOR . '_presets.php';
require_once 'ModulesAbstractModelTest.php';

use \Ufocms\Frontend\Config;
use \Ufocms\Frontend\Container;
use \Ufocms\Frontend\Core;
use \Ufocms\Frontend\Db;
use \Ufocms\Frontend\Params;
use \Ufocms\Modules\Documents\Model;

class ModulesDocumentsModelTest extends ModulesAbstractModelTest
{
    /**
     * @var Params
     */
    protected $params;
    
    protected function _before()
    {
        $this->params = new Params();
        $this->model = $this->getModel();
    }
    
    protected function getModel($withCore = false)
    {
        $db = new Db();
        $core = null;
        if ($withCore) {
            $core = $this->getCore($db);
        }
        $container = new Container([
            'db'        => &$db, 
            'core'      => &$core, 
            'params'    => &$this->params,
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
        $this->assertNull($item);
    }
    
    public function testGetSections()
    {
        $this->params->sectionId = 0;
        $model = $this->getModel(true);
        $items = $model->getSections();
        $this->assertNotNull($items);
        $this->assertTrue(is_array($items));
        
        $this->tester->haveInDatabase(
            'sections', 
            [
                'id'        => 1001, 
                'topid'     => 1001, 
                'parentid'  => 0, 
                'moduleid'  => 1, 
                'path'      => '/test-documents-1/', 
                'indic'     => 'Test Documents Sections 1', 
                'isenabled' => 1, 
            ]
        );
        $this->tester->haveInDatabase(
            'sections', 
            [
                'id'        => 1002, 
                'topid'     => 1002, 
                'parentid'  => 0, 
                'moduleid'  => 1, 
                'path'      => '/test-documents-2/', 
                'indic'     => 'Test Documents Sections 2', 
                'isenabled' => 1, 
            ]
        );
        $this->tester->haveInDatabase(
            'sections', 
            [
                'id'        => 1003, 
                'topid'     => 1003, 
                'parentid'  => 0, 
                'moduleid'  => 1, 
                'path'      => '/test-documents-3/', 
                'indic'     => 'Test Documents Sections 3', 
                'isenabled' => 0, 
            ]
        );
        $this->tester->haveInDatabase(
            'sections', 
            [
                'id'        => 1004, 
                'topid'     => 1004, 
                'parentid'  => 0, 
                'moduleid'  => 0, 
                'path'      => '/test-documents-4/', 
                'indic'     => 'Test Documents Sections 4', 
                'isenabled' => 1, 
            ]
        );
        $this->params->sectionId = 1001;
        $model = $this->getModel(true);
        $items = $model->getSections();
        $this->assertNotNull($items);
        $this->assertTrue(is_array($items));
        $this->assertEquals(2, count($items));
        $this->assertTrue(array_key_exists('Value', $items[1]));
        $this->assertTrue(array_key_exists('Title', $items[1]));
        $this->assertEquals(1002, $items[1]['Value']);
        $this->assertEquals('Test Documents Sections 2', $items[1]['Title']);
    }
}
