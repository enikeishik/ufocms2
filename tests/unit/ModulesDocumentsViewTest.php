<?php
require_once __DIR__  . DIRECTORY_SEPARATOR . '_presets.php';
require_once 'ModulesAbstractViewTest.php';

use \Ufocms\Modules\Documents\Model;
use \Ufocms\Modules\Documents\View;

class ModulesDocumentsViewTest extends ModulesAbstractViewTest
{
    protected function getModel($container)
    {
        return new Model($container);
    }
    
    protected function getView($container)
    {
        $view = new class($container) extends View {
            protected $tmpfname;
            protected $testContent;
            protected function init()
            {
                parent::init();
                $this->tmpfname = tempnam(sys_get_temp_dir(), 'tmp');
            }
            public function __destruct()
            {
                unlink($this->tmpfname);
            }
            public function setTestContent($content)
            {
                $this->testContent = $content;
            }
            protected function getLayout()
            {
                file_put_contents($this->tmpfname, $this->testContent);
                return $this->tmpfname;
            }
        };
        $view->setTestContent($this->getTemplateContent());
        return $view;
    }
    
    // tests
    public function testGetModuleContext()
    {
        $this->params->sectionId = 0;
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
        $this->assertTrue(is_array($context['item']));
        $this->assertTrue(array_key_exists('Body', $context['item']));
        $this->assertEquals('Module Document Test 2', $context['item']['Body']);
    }
    
    public function testRender()
    {
        $this->params->sectionId = 0;
        $content = $this->getRenderContent();
        $this->assertTrue(false !== strpos($content, 'test view template begin'));
        $this->assertTrue(false !== stripos($content, '$item NULL'));
        $this->assertTrue(false !== stripos($content, '$items NULL'));
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
        $content = $this->getRenderContent();
        $this->assertTrue(false !== strpos($content, 'test view template begin'));
        $this->assertTrue(false !== strpos($content, '$item array(4)'));
        $this->assertTrue(false !== strpos($content, '1014'));
        $this->assertTrue(false !== strpos($content, 'Module Document Test 4'));
        $this->assertTrue(false !== strpos($content, '$items NULL'));
        $this->assertTrue(false !== strpos($content, 'test view template end'));
    }
}
