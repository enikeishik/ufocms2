<?php
require_once __DIR__  . DIRECTORY_SEPARATOR . '_presets.php';
require_once 'ModulesAbstractViewTest.php';
require_once 'ModulesViewTrait.php';

use \Ufocms\Modules\Mainpage\Model;
use \Ufocms\Modules\View;

class ModulesMainpageViewTest extends ModulesAbstractViewTest
{
    protected function getModel($container)
    {
        return new Model($container);
    }
    
    protected function getModuleContext()
    {
        $model = $this->getModel($this->getContainerForModel());
        return [
            'item' => $model->getItem(), 
            'items' => null, 
            'itemsCount' => null, 
        ];
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
    public function testRender()
    {
        //remove travis-ci VM id from var_dump
        $content = preg_replace('/ \/tmp\/tmp.+\n/', ' ', $this->getRenderContent());
        $this->assertTrue(false !== strpos($content, 'test view template begin'));
        $this->assertTrue(false !== strpos($content, '$item array(2)'));
        $this->assertTrue(false !== strpos($content, 'id'));
        $this->assertTrue(false !== strpos($content, 'body'));
        $this->assertTrue(false !== strpos($content, '$items NULL'));
        $this->assertTrue(false !== strpos($content, 'test view template end'));
    }
}
