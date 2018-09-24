<?php
require_once __DIR__  . DIRECTORY_SEPARATOR . '_presets.php';
require_once 'AdminModulesAbstractSchemaTest.php';
require_once 'AdminModulesModelTrait.php';
 
use \Ufocms\Frontend\Container;
use \Ufocms\Frontend\Tools;
use \Ufocms\Backend\Audit;
use \Ufocms\Backend\Config;
use \Ufocms\Backend\Core;
use \Ufocms\Backend\Db;
use \Ufocms\Backend\Params;
use \Ufocms\AdminModules\Model;

class AdminModulesAbstractModelTest extends AdminModulesAbstractSchemaTest
{
    /**
     * @var Model
     */
    protected $model;
    
    /**
     * @var array
     */
    protected $module = [
        'ModuleId'      => 0, 
        'Module'        => 'Abstract', 
        'Controller'    => '\Ufocms\AdminModules\Controller', 
        'Model'         => '\Ufocms\AdminModules\Model', 
        'View'          => '\Ufocms\AdminModules\View', 
    ];
    
    protected function _before()
    {
        parent::_before();
        $this->params->page = $this->config->pageDefault;
        $this->params->pageSize = $this->config->pageSizeDefault;
        $this->params->sectionId = 0;
        $this->params->itemId = 0;
        $this->model  = $this->getModel($this->getContainer());
    }

    protected function _after()
    {
    }
    
    protected function getContainer(array $moduleParams = [])
    {
        $core = new Core($this->config, $this->params, $this->db);
        $tools = new Tools($this->config, $this->params, $this->db);
        return new Container([
            'config'        => &$this->config, 
            'params'        => &$this->params, 
            'db'            => &$this->db, 
            'core'          => &$core, 
            'tools'         => &$tools, 
            'module'        => &$this->module, 
            'moduleParams'  => $moduleParams, 
        ]);
    }
    
    /**
     * Must be redefined in child class to work with its own model.
     * @param Container $container
     * @return Model
     */
    protected function getModel($container)
    {
        return new class($container) extends Model {
            use AdminModulesModelTrait;
        };
    }
    
    // tests
    public function testGetModule()
    {
        $item = $this->model->getModule();
        $this->assertTrue(is_array($item));
        $this->assertEquals(5, count($item));
        $this->assertTrue(array_key_exists('Module', $item));
        $this->assertEquals($this->module['Module'], $item['Module']);
    }
    
    public function testGetMaster()
    {
        $item = $this->model->getMaster();
        $this->assertNull($item);
    }
    
    public function testGetItemsTable()
    {
        $item = $this->model->getItemsTable();
        $this->assertNotNull($item);
        $this->assertEquals(strtolower($this->module['Module']), $item);
    }
    
    public function testGetItemIdField()
    {
        $item = $this->model->getItemIdField();
        $this->assertNotNull($item);
        $this->assertEquals('Id', $item);
    }
    
    public function testGetItemDisabledField()
    {
        $item = $this->model->getItemDisabledField();
        $this->assertNotNull($item);
        $this->assertEquals('IsDisabled', $item);
    }
    
    public function testGetFieldModel()
    {
        //separate models, getFieldModel caching data
        $model = $this->getModel($this->getContainer());
        $item = $model->getFieldModel('NotExistentField');
        $this->assertNull($item);
        
        $model = $this->getModel($this->getContainer());
        $model->setFields([
            ['Type' => 'int', 'Name' => 'TestField', 'Value' => 0, 'Title' => 'test field', 'Filter' => false, 'Show' => true, 'Sort' => true, 'Edit' => false],
        ]);
        $item = $model->getFieldModel('TestField');
        $this->assertNull($item);
        
        $model = $this->getModel($this->getContainer());
        $model->setFields([
            ['Type' => 'subform', 'Name' => 'TestModelField', 'Value' => '', 'Title' => 'test model field', 'Filter' => false, 'Show' => true, 'Sort' => true, 'Edit' => false, 'Model' => 'getTestModel'],
        ]);
        $item = $model->getFieldModel('TestModelField');
        $this->assertTrue(is_array($item));
        $this->assertTrue(array_key_exists('Result', $item));
        $this->assertEquals('getTestModel', $item['Result']);
        
        $model = $this->getModel($this->getContainer());
        $model->setFields([
            ['Type' => 'subform', 'Name' => 'TestModelField', 'Value' => '', 'Title' => 'test model field', 'Filter' => false, 'Show' => true, 'Sort' => true, 'Edit' => false, 'Model' => 'getTestModel'],
        ]);
        $item = $model->getFieldModel('TestModelField', 'varValue');
        $this->assertTrue(is_array($item));
        $this->assertTrue(array_key_exists('var', $item));
        $this->assertEquals('varValue', $item['var']);
    }
    
    public function testGetFieldSchema()
    {
        $model = $this->getModel($this->getContainer());
        $item = $model->getFieldSchema('NotExistentField');
        $this->assertNull($item);
        
        $model = $this->getModel($this->getContainer());
        $model->setFields([
            ['Type' => 'int', 'Name' => 'TestField', 'Value' => 0, 'Title' => 'test field', 'Filter' => false, 'Show' => true, 'Sort' => true, 'Edit' => false],
        ]);
        $item = $model->getFieldSchema('TestField');
        $this->assertNull($item);
        
        $model = $this->getModel($this->getContainer());
        $model->setFields([
            ['Type' => 'subform', 'Name' => 'TestSchemaField', 'Value' => '', 'Title' => 'test schema field', 'Filter' => false, 'Show' => true, 'Sort' => true, 'Edit' => false, 'Schema' => 'getTestSchema'],
        ]);
        $item = $model->getFieldSchema('TestSchemaField');
        $this->assertTrue(is_array($item));
        $this->assertTrue(array_key_exists('Result', $item));
        $this->assertEquals('getTestSchema', $item['Result']);
    }
    
    public function testGetFieldItems()
    {
        $model = $this->getModel($this->getContainer());
        $item = $model->getFieldItems('NotExistentField');
        $this->assertNull($item);
        
        $model = $this->getModel($this->getContainer());
        $model->setFields([
            ['Type' => 'int', 'Name' => 'TestField', 'Value' => 0, 'Title' => 'test field', 'Filter' => false, 'Show' => true, 'Sort' => true, 'Edit' => false],
        ]);
        $item = $model->getFieldItems('TestField');
        $this->assertNull($item);
        
        $model = $this->getModel($this->getContainer());
        $model->setFields([
            ['Type' => 'subform', 'Name' => 'TestItemsField', 'Value' => '', 'Title' => 'test items field', 'Filter' => false, 'Show' => true, 'Sort' => true, 'Edit' => false, 'Items' => 'getTestItems'],
        ]);
        $item = $model->getFieldItems('TestItemsField');
        $this->assertTrue(is_array($item));
        $this->assertTrue(array_key_exists('Result', $item));
        $this->assertEquals('getTestItems', $item['Result']);
    }
    
    public function testGetItemExternalFieldValue()
    {
        //one model, getItemExternalFieldValue not caching data
        $model = $this->getModel($this->getContainer());
        
        $item = $model->getItemExternalFieldValue('NotExistentField');
        $this->assertNull($item);
        
        $model->setFields([
            ['Type' => 'int', 'Name' => 'TestField', 'Value' => 0, 'Title' => 'test field', 'Filter' => false, 'Show' => true, 'Sort' => true, 'Edit' => false],
        ]);
        $item = $model->getItemExternalFieldValue('TestField');
        $this->assertNull($item);
        
        $this->params->itemId = null;
        $model->setFields([
            ['Type' => 'subform', 'Name' => 'TestMethodField', 'Value' => 'getTestMethod', 'Title' => 'test model field', 'Filter' => false, 'Show' => true, 'Sort' => true, 'Edit' => false],
        ]);
        $item = $model->getItemExternalFieldValue('TestMethodField');
        $this->assertTrue(is_array($item));
        $this->assertTrue(array_key_exists('Result', $item));
        $this->assertEquals('getTestMethod', $item['Result']);
        
        $this->params->itemId = 101;
        $model->setFields([
            ['Type' => 'subform', 'Name' => 'TestMethodField', 'Value' => 'getTestMethod', 'Title' => 'test model field', 'Filter' => false, 'Show' => true, 'Sort' => true, 'Edit' => false],
        ]);
        $item = $model->getItemExternalFieldValue('TestMethodField');
        $this->assertTrue(is_array($item));
        $this->assertTrue(array_key_exists('var', $item));
        $this->assertEquals(101, $item['var']);
    }
    
    public function testGetItems()
    {
        if (0 == $this->module['ModuleId']) {
            return;
        }
        $this->assertTrue(false);
    }
    
    public function testGetItemsCount()
    {
        if (0 == $this->module['ModuleId']) {
            return;
        }
        $this->assertTrue(false);
    }
    
    public function testGetItem()
    {
        if (0 == $this->module['ModuleId']) {
            return;
        }
        $this->assertTrue(false);
    }
    
    public function testGetSections()
    {
        $item = $this->model->getSections();
        $this->assertTrue(is_array($item));
        $this->assertEquals(0, count($item));
    }
    
    public function testUpdate()
    {
        if (0 == $this->module['ModuleId']) {
            return;
        }
        $this->assertTrue(false);
    }
    
    public function testDelete()
    {
        if (0 == $this->module['ModuleId']) {
            return;
        }
        $this->assertTrue(false);
    }
    
    public function testDisable()
    {
        if (0 == $this->module['ModuleId']) {
            return;
        }
        $this->assertTrue(false);
    }
    
    public function testEnable()
    {
        if (0 == $this->module['ModuleId']) {
            return;
        }
        $this->assertTrue(false);
    }
    
    public function testGetResult()
    {
        $item = $this->model->getResult();
        $this->assertNull($item);
    }
    
    public function testIsCanCreateItems()
    {
        $item = $this->model->isCanCreateItems();
        $this->assertTrue($item);
    }
    
    public function testIsCanUpdateItems()
    {
        $item = $this->model->isCanUpdateItems();
        $this->assertTrue($item);
    }
    
    public function testIsCanDeleteItems()
    {
        $item = $this->model->isCanDeleteItems();
        $this->assertTrue($item);
    }
    
    public function testGetLastInsertedId()
    {
        $item = $this->model->getLastInsertedId();
        $this->assertNull($item);
    }
}
