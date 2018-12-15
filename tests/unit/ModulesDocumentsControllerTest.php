<?php
require_once __DIR__  . DIRECTORY_SEPARATOR . '_presets.php';
require_once 'ModulesAbstractControllerTest.php';
require_once 'ModulesControllerTrait.php';

use \Ufocms\Modules\Documents\Controller;

class ModulesDocumentsControllerTest extends ModulesAbstractControllerTest
{
    protected function getModuleClasses()
    {
        return [
            'Model' => '\Ufocms\Modules\Documents\Model', 
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
        $this->params->sectionId = 0;
        $controller = $this->getController($this->getContainer());
        $context = $controller->getGetModuleContext();
        $this->assertNotNull($context);
        $this->assertTrue(is_array($context));
        $this->assertTrue(array_key_exists('item', $context));
        $this->assertNull($context['item']);
        
        $this->tester->haveInDatabase(
            'documents', 
            [
                'Id'        => 1011, 
                'SectionId' => 1011, 
                'Body'      => 'Module Document Test 1'
            ]
        );
        $this->tester->haveInDatabase(
            'documents', 
            [
                'Id'        => 1012, 
                'SectionId' => 1012, 
                'Body'      => 'Module Document Test 2'
            ]
        );
        $this->params->sectionId = 1012;
        $context = $controller->getGetModuleContext();
        $this->assertNotNull($context);
        $this->assertTrue(is_array($context));
        $this->assertTrue(array_key_exists('item', $context));
        $this->assertTrue(is_array($context['item']));
        $this->assertTrue(array_key_exists('Body', $context['item']));
        $this->assertEquals('Module Document Test 2', $context['item']['Body']);
    }
    
    public function testDispatch()
    {
        $this->expectedException('render');
    }
}
