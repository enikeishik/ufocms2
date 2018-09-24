<?php
require_once __DIR__  . DIRECTORY_SEPARATOR . '_presets.php';

use \Ufocms\Frontend\Container;
use \Ufocms\Backend\Audit;
use \Ufocms\Backend\Config;
use \Ufocms\Backend\Core;
use \Ufocms\Backend\Db;
use \Ufocms\Backend\Params;
use \Ufocms\AdminModules\Schema;

class AdminModulesAbstractSchemaTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    
    /**
     * @var Config
     */
    protected $config;
    
    /**
     * @var Params
     */
    protected $params;
    
    /**
     * @var Schema
     */
    protected $schema;
    
    protected function _before()
    {
        $this->config = new Config();
        $this->params = new Params();
        $this->schema  = $this->getSchema($this->getContainer());
    }

    protected function _after()
    {
    }
    
    protected function getContainer(array $moduleParams = [])
    {
        $audit = new class($this->config) extends Audit {
            public function record($data)
            {
            }
        };
        $db = new Db($audit);
        $core = new Core($this->config, $this->params, $db);
        return new Container([
            'config'        => &$this->config, 
            'params'        => &$this->params, 
            'db'            => &$db, 
            'core'          => &$core, 
            'module'        => &$this->module, 
            'moduleParams'  => $moduleParams, 
        ]);
    }
    
    /**
     * Must be redefined in child class to work with its own schema.
     * @param Container $container
     * @return Schema
     */
    protected function getSchema($container)
    {
        return new class($container) extends Schema {
            protected function unpackContainer()
            {
            }
            public function setFields(array $fields = null)
            {
                parent::setFields();
                if (null !== $fields) {
                    $this->fields = array_merge($this->fields, $fields);
                }
            }
        };
    }
    
    // tests
    public function testGetFields()
    {
        $item = $this->schema->getFields();
        $this->assertTrue(is_array($item));
        $this->assertEquals(0, count($item));
        
        $this->schema->setFields([
            ['Type' => 'TestFieldType', 'Name' => 'TestFieldName', 'Value' => 'TestFieldValue'], 
        ]);
        $item = $this->schema->getFields();
        $this->assertTrue(is_array($item));
        $this->assertEquals(1, count($item));
    }
    
    public function testGetFieldRef()
    {
        $this->schema->setFields([
            ['Type' => 'TestFieldType', 'Name' => 'TestFieldName', 'Value' => 'TestFieldValue'], 
        ]);
        
        $item = $this->schema->getFieldRef('NonExistenceField');
        $this->assertNull($item);
        
        $item =& $this->schema->getFieldRef('TestFieldName');
        $this->assertTrue(is_array($item));
        $this->assertTrue(array_key_exists('Type', $item));
        $this->assertTrue(array_key_exists('Name', $item));
        $this->assertTrue(array_key_exists('Value', $item));
        $this->assertEquals('TestFieldType', $item['Type']);
        $this->assertEquals('TestFieldName', $item['Name']);
        $this->assertEquals('TestFieldValue', $item['Value']);
        
        $item['Value'] = 'NewTestFieldValue';
        $item = $this->schema->getFieldRef('TestFieldName');
        $this->assertEquals('NewTestFieldValue', $item['Value']);
    }
    
    public function testGetField()
    {
        $this->schema->setFields([
            ['Type' => 'TestFieldType', 'Name' => 'TestFieldName', 'Value' => 'TestFieldValue'], 
        ]);
        
        $item = $this->schema->getField('NonExistenceField');
        $this->assertNull($item);
        
        $item = $this->schema->getField('TestFieldName');
        $this->assertTrue(is_array($item));
        $this->assertTrue(array_key_exists('Type', $item));
        $this->assertTrue(array_key_exists('Name', $item));
        $this->assertTrue(array_key_exists('Value', $item));
        $this->assertEquals('TestFieldType', $item['Type']);
        $this->assertEquals('TestFieldName', $item['Name']);
        $this->assertEquals('TestFieldValue', $item['Value']);
        
        $item['Value'] = 'NewTestFieldValue';
        $item = $this->schema->getField('TestFieldName');
        $this->assertEquals('TestFieldValue', $item['Value']);
    }    
}
