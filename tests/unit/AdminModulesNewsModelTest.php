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
use \Ufocms\AdminModules\News\Model;

class AdminModulesNewsModelTest extends AdminModulesAbstractModelTest
{
    protected $module = [
        'ModuleId'      => 2, 
        'Module'        => 'News', 
        'Controller'    => '\Ufocms\AdminModules\News\Controller', 
        'Model'         => '\Ufocms\AdminModules\News\Model', 
        'View'          => '\Ufocms\AdminModules\News\View', 
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
        $this->assertEquals('IsHidden', $item);
    }
    
    public function testGetItems()
    {
        $this->tester->haveInDatabase(
            'news', 
            [
                'Id'        => 20001, 
                'SectionId' => 200001, 
                'Title'     => 'Test News Title 20001', 
                'Body'      => 'Test News Body 20001', 
            ]
        );
        
        $items = $this->model->getItems();
        $this->assertTrue(is_array($items));
        $this->assertTrue(1 <= count($items));
        $this->assertTrue(array_key_exists('Id', $items[0]));
        foreach ($items as $item) {
            if (20001 == $item['Id']) {
                $this->assertEquals('Test News Title 20001', $item['Title']);
                break;
            }
            $this->assertTrue(false);
        }
    }
    
    public function testGetItemsCount()
    {
        $this->tester->haveInDatabase(
            'news', 
            [
                'Id'        => 20002, 
                'SectionId' => 200002, 
                'Title'     => 'Test News Title 20002', 
                'Body'      => 'Test News Body 20002', 
            ]
        );
        
        $item = $this->model->getItemsCount();
        $this->assertTrue(1 <= $item);
    }
    
    public function testGetItem()
    {
        $this->tester->haveInDatabase(
            'news', 
            [
                'Id'        => 20003, 
                'SectionId' => 200003, 
                'Title'     => 'Test News Title 20003', 
                'Body'      => 'Test News Body 20003', 
            ]
        );
        
        $this->params->itemId = 20003;
        $model = $this->getModel($this->getContainer());
        $item = $model->getItem();
        $this->assertTrue(is_array($item));
        $this->assertTrue(array_key_exists('Id', $item));
        $this->assertEquals(20003, $item['Id']);
        $this->assertEquals('Test News Title 20003', $item['Title']);
    }
    
    public function testGetSections()
    {
        $this->tester->haveInDatabase(
            'sections', 
            [
                'id'        => 200004, 
                'topid'     => 200004, 
                'parentid'  => 0, 
                'moduleid'  => 2, 
                'path'      => '/test-news-path-20004/', 
                'indic'     => 'Test News 20004', 
                'isenabled' => 1, 
            ]
        );
        $this->tester->haveInDatabase(
            'news', 
            [
                'Id'        => 20004, 
                'SectionId' => 200004, 
                'Title'     => 'Test News Title 20004', 
                'Body'      => 'Test News Body 20004', 
            ]
        );
        
        $this->params->sectionId = 200004;
        $model = $this->getModel($this->getContainer());
        $item = $model->getSections();
        $this->assertTrue(is_array($item));
        $this->assertTrue(1 <= count($item));
    }
    
    public function testUpdate()
    {
        $this->tester->haveInDatabase(
            'news', 
            [
                'Id'        => 20005, 
                'SectionId' => 200005, 
                'Title'     => 'Test News Title 20005', 
                'Body'      => 'Test News Body 20005', 
            ]
        );
        
        $this->params->itemId = 20005;
        $this->params->sectionId = 200005;
        $_POST['SectionId'] = '200005';
        $_POST['DateCreate'] = date('Y-m-d H:i:s');
        $_POST['Author'] = '';
        $_POST['Icon'] = '';
        $_POST['Title'] = 'New Test News Title 20005';
        $_POST['Announce'] = '';
        $_POST['Body'] = 'New Test News Body 20005';
        $model = $this->getModel($this->getContainer());
        $item = $model->update();
        $this->assertTrue($item);
        
        $item = $model->getItem();
        $this->assertTrue(is_array($item));
        $this->assertTrue(array_key_exists('Body', $item));
        $this->assertEquals('New Test News Body 20005', $item['Body']);
    }
    
    public function testCreate()
    {
        $this->params->itemId = 0;
        $this->params->sectionId = 200006;
        $_POST['SectionId'] = '200006';
        $_POST['DateCreate'] = date('Y-m-d H:i:s');
        $_POST['Author'] = '';
        $_POST['Icon'] = '';
        $_POST['Title'] = 'New Test News Title 20006';
        $_POST['Announce'] = '';
        $_POST['Body'] = 'New Test News Body 20006';
        $model = $this->getModel($this->getContainer());
        $item = $model->update();
        $this->assertTrue($item);
        
        $this->params->itemId = $model->getLastInsertedId();
        $item = $model->getItem();
        $this->assertTrue(is_array($item));
        $this->assertTrue(array_key_exists('Body', $item));
        $this->assertEquals('New Test News Body 20006', $item['Body']);
    }
    
    public function testDelete()
    {
        $this->tester->haveInDatabase(
            'news', 
            [
                'Id'        => 10007, 
                'SectionId' => 200007, 
                'Title'     => 'Test News Title 20007', 
                'Body'      => 'Test News Body 20007', 
            ]
        );
        
        $this->params->itemId = 20007;
        $model = $this->getModel($this->getContainer());
        $item = $model->delete();
        $this->assertTrue($item);
        
        $item = $model->getItem();
        $this->assertEquals($model->getEmptyItem(), $item);
    }
    
    public function testDisable()
    {
        $this->tester->haveInDatabase(
            'news', 
            [
                'Id'        => 20008, 
                'SectionId' => 200008, 
                'Title'     => 'Test News Title 20008', 
                'Body'      => 'Test News Body 20008', 
            ]
        );
        
        $this->params->itemId = 20008;
        $model = $this->getModel($this->getContainer());
        $item = $model->disable();
        $this->assertTrue($item);
        
        $item = $model->getItem();
        $this->assertTrue(is_array($item));
        $this->assertTrue(array_key_exists('IsHidden', $item));
        $this->assertEquals('1', $item['IsHidden']);
    }
    
    public function testEnable()
    {
        $this->tester->haveInDatabase(
            'news', 
            [
                'Id'        => 20009, 
                'SectionId' => 200009, 
                'Title'     => 'Test News Title 20009', 
                'Body'      => 'Test News Body 20009', 
            ]
        );
        
        $this->params->itemId = 20009;
        $model = $this->getModel($this->getContainer());
        $item = $model->enable();
        $this->assertTrue($item);
        
        $item = $model->getItem();
        $this->assertTrue(is_array($item));
        $this->assertTrue(array_key_exists('IsHidden', $item));
        $this->assertEquals('0', $item['IsHidden']);
    }
}
