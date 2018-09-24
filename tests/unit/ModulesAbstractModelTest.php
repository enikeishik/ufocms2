<?php
require_once __DIR__  . DIRECTORY_SEPARATOR . '_presets.php';

use \Ufocms\Frontend\Container;
use \Ufocms\Frontend\Params;
use \Ufocms\Modules\Model;

class ModulesAbstractModelTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    
    /**
     * @var Model
     */
    protected $model;
    
    protected function _before()
    {
        $params = new Params();
        $core = new class() {
            public function getSection($section = null, $fields = null)
            {
                return ['moduleid' => 0];
            }
            public function getModuleSections($moduleId)
            {
                return [];
            }
        };
        $container = new Container([
            'params' => &$params, 
            'core'   => &$core, 
        ]);
        $this->model = new class($container) extends Model {
            
        };
    }

    protected function _after()
    {
    }
    
    // tests
    public function testGetSettings()
    {
        $items = $this->model->getSettings();
        $this->assertNotNull($items);
        $this->assertTrue(is_array($items));
        $this->assertTrue(0 == count($items));
    }
    
    public function testGetItems()
    {
        $items = $this->model->getItems();
        $this->assertNotNull($items);
        $this->assertTrue(is_array($items));
        $this->assertTrue(0 == count($items));
    }
    
    public function testGetItemsCount()
    {
        $itemsCount = $this->model->getItemsCount();
        $this->assertNull($itemsCount);
        
        $items = $this->model->getItems();
        $itemsCount = $this->model->getItemsCount();
        $this->assertNotNull($itemsCount);
        $this->assertTrue(0 == $itemsCount);
    }
    
    public function testGetItem()
    {
        $item = $this->model->getItem();
        $this->assertNull($item);
    }
    
    public function testGetSections()
    {
        $items = $this->model->getSections();
        $this->assertNotNull($items);
        $this->assertTrue(is_array($items));
        $this->assertTrue(0 == count($items));
    }
    
    public function testGetActionResult()
    {
        $result = $this->model->getActionResult();
        $this->assertNull($result);
    }
}
