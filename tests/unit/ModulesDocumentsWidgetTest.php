<?php
require_once __DIR__  . DIRECTORY_SEPARATOR . '_presets.php';
require_once 'ModulesAbstractWidgetTest.php';
require_once 'ModulesWidgetTrait.php';

use \Ufocms\Modules\Documents\Widget;

class ModulesDocumentsWidgetTest extends ModulesAbstractWidgetTest
{
    protected function getWidget($container)
    {
        $widget = new class($container) extends Widget {
            use ModulesWidgetTrait;
        };
        $widget->setTestContent($this->getTemplateContent());
        return $widget;
    }
    
    protected function getData($srcSections = null, array $params = null)
    {
        return [
            'SrcSections'   => $srcSections ?? '0', 
            'SrcItems'      => '', 
            'ShowTitle'     => true, 
            'Title'         => 'test widget title', 
            'Content'       => 'test widget content', 
            'Params'        => json_encode($params ?? ['WordsCount' => 0, 'StartMark' => '', 'StopMark' => '']), 
            'ModuleId'      => 0, 
            'madmin'        => 'mod_', 
            'Name'          => '', 
        ];
    }
    
    // tests
    public function testRender()
    {
        $content = $this->getRenderContent();
        $this->assertTrue(false !== strpos($content, 'test widget template begin'));
        $this->assertTrue(false !== strpos($content, '$showTitle bool(true)'));
        $this->assertTrue(false !== strpos($content, '$title string(17) "test widget title"'));
        $this->assertTrue(false !== strpos($content, '$content string(19) "test widget content"'));
        $this->assertTrue(false !== strpos($content, '$items array(0)'));
        $this->assertTrue(false !== strpos($content, 'test widget template end'));
    }
}
