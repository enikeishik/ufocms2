<?php
require_once __DIR__  . DIRECTORY_SEPARATOR . '_presets.php';
require_once 'AdminModulesAbstractModelTest.php';
require_once 'AdminModulesModelTrait.php';

use \Ufocms\Frontend\Container;
use \Ufocms\Backend\Audit;
use \Ufocms\Backend\Config;
use \Ufocms\Backend\Core;
use \Ufocms\Backend\Db;
use \Ufocms\Backend\Params;
use \Ufocms\AdminModules\Mainpage\Model;

class AdminModulesMainpageModelTest extends AdminModulesAbstractModelTest
{
    protected $module = [
        'ModuleId'      => -1, 
        'Module'        => 'Mainpage', 
        'Controller'    => '\Ufocms\AdminModules\Mainpage\Controller', 
        'Model'         => '\Ufocms\AdminModules\Mainpage\Model', 
        'View'          => '\Ufocms\AdminModules\Mainpage\View', 
    ];
    
    protected function _before()
    {
        parent::_before();
        $this->params->sectionId = -1;
    }
    
    protected function getModel($container)
    {
        return new class($container) extends Model {
            use AdminModulesModelTrait;
        };
    }
    
    // tests
    public function testGetItemIdField()
    {
        $item = $this->model->getItemIdField();
        $this->assertNotNull($item);
        $this->assertEquals('id', $item);
    }
    
    public function testGetItemDisabledField()
    {
        $item = $this->model->getItemDisabledField();
        $this->assertNotNull($item);
        $this->assertEquals('', $item);
    }
    
    public function testGetItems()
    {
        $items = $this->model->getItems();
        $this->assertTrue(is_array($items));
        $this->assertEquals(1, count($items));
        $this->assertTrue(array_key_exists('id', $items[0]));
        $this->assertEquals(1, $items[0]['id']);
    }
    
    public function testGetItemsCount()
    {
        $item = $this->model->getItemsCount();
        $this->assertEquals(1, $item);
    }
    
    public function testGetItem()
    {
        $item = $this->model->getItem();
        $this->assertTrue(is_array($item));
        $this->assertTrue(array_key_exists('id', $item));
        $this->assertEquals(1, $item['id']);
    }
    
    public function testGetSections()
    {
        $item = $this->model->getSections();
        $this->assertTrue(is_array($item));
        $this->assertEquals(1, count($item));
    }
    
    public function testUpdate()
    {
        $model = $this->getModel($this->getContainer());
        $_POST['body'] = 'New Test Mainpage Content';
        $item = $model->update();
        $this->assertTrue($item);
        
        $item = $model->getItem();
        $this->assertTrue(is_array($item));
        $this->assertTrue(array_key_exists('body', $item));
        $this->assertEquals('New Test Mainpage Content', $item['body']);
    }
    
    public function testCreate()
    {
        $model = $this->getModel($this->getContainer());
        $this->params->itemId = 0;
        $_POST['body'] = 'New Test Mainpage Content';
        $item = $model->update();
        $this->assertFalse($item);
        
        $result = $model->getResult();
        $this->assertEquals('Action not supported', $result);
    }
    
    public function testDelete()
    {
        $model = $this->getModel($this->getContainer());
        $item = $model->delete();
        $this->assertFalse($item);
        
        $result = $model->getResult();
        $this->assertEquals('Action not supported', $result);
    }
    
    public function testDisable()
    {
        $model = $this->getModel($this->getContainer());
        $item = $model->disable();
        $this->assertFalse($item);
        
        $result = $model->getResult();
        $this->assertEquals('Action not supported', $result);
    }
    
    public function testEnable()
    {
        $this->params->itemId = 1;
        $model = $this->getModel($this->getContainer());
        $item = $model->enable();
        $this->assertFalse($item);
        
        $result = $model->getResult();
        $this->assertEquals('Action not supported', $result);
    }
    
    public function testIsCanCreateItems()
    {
        $item = $this->model->isCanCreateItems();
        $this->assertFalse($item);
    }
    
    public function testIsCanDeleteItems()
    {
        $item = $this->model->isCanDeleteItems();
        $this->assertFalse($item);
    }
}
