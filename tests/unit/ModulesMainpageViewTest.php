<?php
require_once __DIR__  . DIRECTORY_SEPARATOR . '_presets.php';
require_once 'ModulesAbstractViewTest.php';
require_once 'ModulesViewTrait.php';

use \Ufocms\Modules\Mainpage\Model;
use \Ufocms\Modules\Mainpage\View;

class ModulesMainpageViewTest extends ModulesAbstractViewTest
{
    protected function getModel($container)
    {
        return new Model($container);
    }
    
    protected function getView($container)
    {
        $view = new class($container) extends View {
            use ModulesViewTrait;
        };
        $view->setTestContent($this->getTemplateContent());
        return $view;
    }
    
    // tests
    public function testGetModuleContext()
    {
        $container = $this->getContainer();
        $view = new class($container) extends View {
            public function getGetModuleContext()
            {
                return $this->getModuleContext();
            }
        };
        $context = $view->getGetModuleContext();
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
    
    public function testRender()
    {
        //remove travis-ci VM id from var_dump
        $content = preg_replace('/ \/tmp\/tmp.+\n/', ' ', $this->getRenderContent());
        var_dump($content); exit();
        $this->assertTrue(false !== strpos($content, 'test view template begin'));
        $this->assertTrue(false !== strpos($content, '$item array(2)'));
        $this->assertTrue(false !== strpos($content, '["id"]=>'));
        $this->assertTrue(false !== strpos($content, '["body"]=>'));
        $this->assertTrue(false !== strpos($content, '$items NULL'));
        $this->assertTrue(false !== strpos($content, 'test view template end'));
    }
}
