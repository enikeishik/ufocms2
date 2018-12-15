<?php
require_once __DIR__  . DIRECTORY_SEPARATOR . '_presets.php';
require_once 'ModulesAbstractControllerTest.php';
require_once 'ModulesControllerTrait.php';

use \Ufocms\Modules\Mainpage\Controller;

class ModulesMainpageControllerTest extends ModulesAbstractControllerTest
{
    protected function getModuleClasses()
    {
        return [
            'Model' => '\Ufocms\Modules\Mainpage\Model', 
        ];
    }
    
    protected function getController($container)
    {
        return new class($container) extends Controller {
            use ModulesControllerTrait;
        };
    }
    
    // tests
    public function testGetModuleContext()
    {
        $controller = $this->getController($this->getContainer());
        $context = $controller->getGetModuleContext();
        $this->assertNotNull($context);
        $this->assertTrue(is_array($context));
        $this->assertTrue(array_key_exists('item', $context));
        $this->assertNotNull($context['item']);
        $this->assertTrue(is_array($context['item']));
        $this->assertTrue(array_key_exists('id', $context['item']));
        $this->assertTrue(array_key_exists('body', $context['item']));
        $this->assertTrue(array_key_exists('items', $context));
        $this->assertNull($context['items']);
    }
    
    public function testDispatch()
    {
        $this->expectedException('render');
    }
}
