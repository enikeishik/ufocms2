<?php
require_once __DIR__  . DIRECTORY_SEPARATOR . '_presets.php';
require_once 'ModulesAbstractViewTest.php';
require_once 'ModulesViewTrait.php';

use \Ufocms\Modules\Documents\Model;
use \Ufocms\Modules\View;

class ModulesDocumentsViewTest extends ModulesAbstractViewTest
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
        $this->params->sectionId = 0;
        //remove travis-ci VM id from var_dump
        $content = preg_replace('/ \/tmp\/tmp.+\n/', ' ', $this->getRenderContent());
        $this->assertTrue(false !== strpos($content, 'test view template begin'));
        $this->assertTrue(false !== strpos($content, '$item NULL'));
        $this->assertTrue(false !== strpos($content, '$items NULL'));
        $this->assertTrue(false !== strpos($content, 'test view template end'));
        
        $this->tester->haveInDatabase(
            'documents', 
            [
                'Id'        => 1013, 
                'SectionId' => 1013, 
                'Body'      => 'Module Document Test 3'
            ]
        );
        $this->tester->haveInDatabase(
            'documents', 
            [
                'Id'        => 1014, 
                'SectionId' => 1014, 
                'Body'      => 'Module Document Test 4'
            ]
        );
        $this->params->sectionId = 1014;
        //remove travis-ci VM id from var_dump
        $content = preg_replace('/ \/tmp\/tmp.+\n/', ' ', $this->getRenderContent());
        $this->assertTrue(false !== strpos($content, 'test view template begin'));
        $this->assertTrue(false !== strpos($content, '$item array(4)'));
        $this->assertTrue(false !== strpos($content, '1014'));
        $this->assertTrue(false !== strpos($content, 'Module Document Test 4'));
        $this->assertTrue(false !== strpos($content, '$items NULL'));
        $this->assertTrue(false !== strpos($content, 'test view template end'));
    }
}
