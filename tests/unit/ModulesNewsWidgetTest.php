<?php
require_once __DIR__  . DIRECTORY_SEPARATOR . '_presets.php';
require_once 'ModulesAbstractWidgetTest.php';

use \Ufocms\Modules\News\Widget;

class ModulesNewsWidgetTest extends ModulesAbstractWidgetTest
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
            'Params'        => json_encode($params ?? ['ItemsCount' => 0, 'ItemsStart' => 0, 'DaysLimit' => 0, 'Author' => '', 'SortOrder' => 0]), 
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
        
        $this->tester->haveInDatabase(
            'sections', 
            [
                'id'        => 20011, 
                'topid'     => 20011, 
                'parentid'  => 0, 
                'moduleid'  => 2, 
                'path'      => '/test-news-widgets-path-11/', 
                'indic'     => 'Test News Widgets Section 11', 
                'isenabled' => 1, 
            ]
        );
        $this->tester->haveInDatabase(
            'news', 
            [
                'Id'            => 20011, 
                'SectionId'     => 20011, 
                'DateCreate'    => '2000-01-01 00:00:00', 
                'Title'         => 'Test News Title 11', 
                'Author'        => 'Test News Author 11', 
                'Icon'          => '', 
                'Announce'      => '', 
                'Body'          => '', 
                'ViewedCnt'     => 0, 
                'IsHidden'      => 0, 
                'IsRss'         => 1, 
            ]
        );
        $this->tester->haveInDatabase(
            'news', 
            [
                'Id'            => 20012, 
                'SectionId'     => 20011, 
                'DateCreate'    => '2000-01-01 00:00:00', 
                'Title'         => 'Test News Title 12', 
                'Author'        => 'Test News Author 12', 
                'Icon'          => '', 
                'Announce'      => '', 
                'Body'          => '', 
                'ViewedCnt'     => 0, 
                'IsHidden'      => 0, 
                'IsRss'         => 1, 
            ]
        );
        $this->tester->haveInDatabase(
            'news', 
            [
                'Id'            => 20013, 
                'SectionId'     => 20011, 
                'DateCreate'    => '2000-01-01 00:00:00', 
                'Title'         => 'Test News Title 13', 
                'Author'        => 'Test News Author 13', 
                'Icon'          => '', 
                'Announce'      => '', 
                'Body'          => '', 
                'ViewedCnt'     => 0, 
                'IsHidden'      => 1, 
                'IsRss'         => 1, 
            ]
        );
        $this->tester->haveInDatabase(
            'news', 
            [
                'Id'            => 20014, 
                'SectionId'     => 20011, 
                'DateCreate'    => '2222-01-01 00:00:00', 
                'Title'         => 'Test News Title 14', 
                'Author'        => 'Test News Author 14', 
                'Icon'          => '', 
                'Announce'      => '', 
                'Body'          => '', 
                'ViewedCnt'     => 0, 
                'IsHidden'      => 0, 
                'IsRss'         => 1, 
            ]
        );
        $this->tester->haveInDatabase(
            'news', 
            [
                'Id'            => 20015, 
                'SectionId'     => 20012, 
                'DateCreate'    => '2000-01-01 00:00:00', 
                'Title'         => 'Test News Title 15', 
                'Author'        => 'Test News Author 15', 
                'Icon'          => '', 
                'Announce'      => '', 
                'Body'          => '', 
                'ViewedCnt'     => 0, 
                'IsHidden'      => 0, 
                'IsRss'         => 1, 
            ]
        );
        $this->tester->haveInDatabase(
            'news', 
            [
                'Id'            => 20016, 
                'SectionId'     => 20011, 
                'DateCreate'    => '2000-01-01 00:00:00', 
                'Title'         => 'Test News Title 16', 
                'Author'        => 'Test News Author 16', 
                'Icon'          => '', 
                'Announce'      => '', 
                'Body'          => '', 
                'ViewedCnt'     => 0, 
                'IsHidden'      => 0, 
                'IsRss'         => 1, 
            ]
        );
        $this->tester->haveInDatabase(
            'news', 
            [
                'Id'            => 20017, 
                'SectionId'     => 20011, 
                'DateCreate'    => '2000-01-01 00:00:00', 
                'Title'         => 'Test News Title 17', 
                'Author'        => 'Test News Author 17', 
                'Icon'          => '', 
                'Announce'      => '', 
                'Body'          => '', 
                'ViewedCnt'     => 0, 
                'IsHidden'      => 0, 
                'IsRss'         => 1, 
            ]
        );
        $this->data = $this->getData(20011, ['ItemsCount' => 2, 'ItemsStart' => 1, 'DaysLimit' => 0, 'Author' => '', 'SortOrder' => 0]);
        $content = $this->getRenderContent();
        $this->assertTrue(false !== strpos($content, 'test widget template begin'));
        $this->assertTrue(false !== strpos($content, '$showTitle bool(true)'));
        $this->assertTrue(false !== strpos($content, '$title string(17) "test widget title"'));
        $this->assertTrue(false !== strpos($content, '$content string(19) "test widget content"'));
        $this->assertTrue(false !== strpos($content, '$items array(2)'));
        $this->assertTrue(false !== strpos($content, '20016'));
        $this->assertTrue(false !== strpos($content, '20012'));
        $this->assertTrue(false !== strpos($content, 'test widget template end'));
    }
}
