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
use \Ufocms\AdminModules\Documents\Model;

class AdminModulesDocumentsModelTest extends AdminModulesAbstractModelTest
{
    protected $module = [
        'ModuleId'      => 1, 
        'Module'        => 'Documents', 
        'Controller'    => '\Ufocms\AdminModules\Documents\Controller', 
        'Model'         => '\Ufocms\AdminModules\Documents\Model', 
        'View'          => '\Ufocms\AdminModules\Documents\View', 
    ];
    
    protected function getModel($container)
    {
        return new class($container) extends Model {
            use AdminModulesModelTrait;
        };
    }
    
    // tests
    public function testGetItemDisabledField()
    {
        $item = $this->model->getItemDisabledField();
        $this->assertNotNull($item);
        $this->assertEquals('', $item);
    }
    
    public function testGetItems()
    {
        $this->tester->haveInDatabase(
            'documents', 
            [
                'Id'        => 10001, 
                'SectionId' => 100001, 
                'Body'      => 'Test Documents Body 10001', 
            ]
        );
        
        $items = $this->model->getItems();
        $this->assertTrue(is_array($items));
        $this->assertTrue(1 <= count($items));
        $this->assertTrue(array_key_exists('Id', $items[0]));
        foreach ($items as $item) {
            if (10001 == $item['Id']) {
                $this->assertEquals(10001, $item['Id']);
                break;
            }
            $this->assertTrue(false);
        }
    }
    
    public function testGetItemsCount()
    {
        $this->tester->haveInDatabase(
            'documents', 
            [
                'Id'        => 10002, 
                'SectionId' => 100002, 
                'Body'      => 'Test Documents Body 10002', 
            ]
        );
        
        $item = $this->model->getItemsCount();
        $this->assertTrue(1 <= $item);
    }
    
    public function testGetItem()
    {
        $this->tester->haveInDatabase(
            'documents', 
            [
                'Id'        => 10003, 
                'SectionId' => 100003, 
                'Body'      => 'Test Documents Body 10003', 
            ]
        );
        
        $this->params->sectionId = 100003;
        $model = $this->getModel($this->getContainer());
        $item = $model->getItem();
        $this->assertTrue(is_array($item));
        $this->assertTrue(array_key_exists('Id', $item));
        $this->assertEquals(10003, $item['Id']);
    }
    
    public function testGetSections()
    {
        $this->tester->haveInDatabase(
            'sections', 
            [
                'id'        => 100004, 
                'topid'     => 100004, 
                'parentid'  => 0, 
                'moduleid'  => 1, 
                'path'      => '/test-documents-path-10004/', 
                'indic'     => 'Test Documents 10004', 
                'isenabled' => 1, 
            ]
        );
        $this->tester->haveInDatabase(
            'documents', 
            [
                'Id'        => 10004, 
                'SectionId' => 100004, 
                'Body'      => 'Test Documents Body 10004', 
            ]
        );
        
        $this->params->sectionId = 100004;
        $model = $this->getModel($this->getContainer());
        $item = $model->getSections();
        $this->assertTrue(is_array($item));
        $this->assertTrue(1 <= count($item));
    }
    
    public function testUpdate()
    {
        $this->tester->haveInDatabase(
            'documents', 
            [
                'Id'        => 10005, 
                'SectionId' => 100005, 
                'Body'      => 'Test Documents Body 10005', 
            ]
        );
        
        $this->params->sectionId = 100005;
        $_POST['Body'] = 'New Test Documents Body 10005';
        $model = $this->getModel($this->getContainer());
        $item = $model->update();
        $this->assertTrue($item);
        
        $item = $model->getItem();
        $this->assertTrue(is_array($item));
        $this->assertTrue(array_key_exists('Body', $item));
        $this->assertEquals('New Test Documents Body 10005', $item['Body']);
    }
    
    public function testCreate()
    {
        $this->params->sectionId = 100006;
        $_POST['Body'] = 'New Test Documents Body 10006';
        $model = $this->getModel($this->getContainer());
        $this->params->itemId = 0;
        $item = $model->update();
        $this->assertFalse($item);
        
        $result = $model->getResult();
        $this->assertEquals('Action not supported', $result);
    }
    
    public function testDelete()
    {
        $this->tester->haveInDatabase(
            'documents', 
            [
                'Id'        => 10007, 
                'SectionId' => 100007, 
                'Body'      => 'Test Documents Body 10007', 
            ]
        );
        
        $this->params->sectionId = 100007;
        $model = $this->getModel($this->getContainer());
        $item = $model->delete();
        $this->assertFalse($item);
        
        $result = $model->getResult();
        $this->assertEquals('Action not supported', $result);
    }
    
    public function testDisable()
    {
        $this->tester->haveInDatabase(
            'documents', 
            [
                'Id'        => 10008, 
                'SectionId' => 100008, 
                'Body'      => 'Test Documents Body 10008', 
            ]
        );
        
        $this->params->sectionId = 100008;
        $model = $this->getModel($this->getContainer());
        $item = $model->disable();
        $this->assertFalse($item);
        
        $result = $model->getResult();
        $this->assertEquals('Action not supported', $result);
    }
    
    public function testEnable()
    {
        $this->tester->haveInDatabase(
            'documents', 
            [
                'Id'        => 10009, 
                'SectionId' => 100009, 
                'Body'      => 'Test Documents Body 10009', 
            ]
        );
        
        $this->params->sectionId = 100009;
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
