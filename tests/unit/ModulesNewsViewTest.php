<?php
require_once __DIR__  . DIRECTORY_SEPARATOR . '_presets.php';
require_once 'ModulesAbstractViewTest.php';
require_once 'ModulesViewTrait.php';

use \Ufocms\Frontend\Config;
use \Ufocms\Frontend\Container;
use \Ufocms\Frontend\Core;
use \Ufocms\Frontend\Db;
use \Ufocms\Frontend\Params;
use \Ufocms\Modules\News\Model;
use \Ufocms\Modules\News\View;

class ModulesNewsViewTest extends ModulesAbstractViewTest
{
    protected function _before()
    {
        parent::_before();
        $this->db = new Db();
    }
    
    protected function getModel($container)
    {
        return new Model($container);
    }
    
    protected function getModuleContext()
    {
        $model = $this->getModel($this->getContainerForModel());
        return [
            'item' => $model->getItem(), 
            'items' => $model->getItems(), 
            'itemsCount' => $model->getItemsCount(), 
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
    
    protected function getViewByParams(array $moduleParams)
    {
        $container = new Container(array_merge(
            [
                'config'    => &$this->config, 
                'params'    => &$this->params, 
                'core'      => &$this->core, 
                'context'   => $this->getModuleContext(), 
                'layout'    => '', 
            ], 
            $moduleParams
        ));
        
        return new class($container) extends View {
            protected $context = [];
            protected function findTemplate($theme, $module, $entry)
            {
                return $entry;
            }
            public function renderHead($entry = null)
            {
                parent::renderHead();
            }
        };
    }
    
    protected function expectedExceptionContains(callable $call, string $exceptionContains)
    {
        try {
            $call();
        } catch (\Exception $e) {
            $this->assertTrue(false !== strpos($e->getMessage(), $exceptionContains));
        }
    }
    
    // tests
    public function testRenderHead()
    {
$tpl = <<<EOD
namespace Ufocms\Modules;
function file_exists(\$file)
{
    return true;
}
EOD;
        eval($tpl);
        
        $this->params->itemId = 0;
        $view = $this->getViewByParams(['config' => $this->config, 'params' => $this->params]);
        $this->expectedExceptionContains(
            function() use($view) { $view->renderHead(); }, 
            'include(/head.php): failed'
        );
        
        $this->params->itemId = 1;
        $view = $this->getViewByParams(['config' => $this->config, 'params' => $this->params]);
        $this->expectedExceptionContains(
            function() use($view) { $view->renderHead(); }, 
            'include(/itemhead.php): failed'
        );
    }
    
    public function testRender()
    {
        //remove travis-ci VM id from var_dump
        $content = preg_replace('/ \/tmp\/tmp.+\n/', ' ', $this->getRenderContent());
        $this->assertTrue(false !== strpos($content, 'test view template begin'));
        $this->assertTrue(false !== strpos($content, '$item NULL'));
        $this->assertTrue(false !== strpos($content, '$items array(0)'));
        $this->assertTrue(false !== strpos($content, 'test view template end'));
        
        $this->tester->haveInDatabase(
            'news_sections', 
            [
                'Id'            => 2091, 
                'SectionId'     => 2091, 
                'BodyHead'      => 'Test News Sections Header 91', 
                'BodyFoot'      => 'Test News Sections Footer 91', 
                'PageLength'    => 15, 
            ]
        );
        $this->tester->haveInDatabase(
            'news', 
            [
                'Id'            => 2091, 
                'SectionId'     => 2091, 
                'DateCreate'    => '2000-01-01 00:00:00', 
                'Title'         => 'Test News Title 91', 
                'Author'        => 'Test News Author 91', 
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
                'Id'            => 2092, 
                'SectionId'     => 2091, 
                'DateCreate'    => '2000-01-01 00:00:00', 
                'Title'         => 'Test News Title 92', 
                'Author'        => 'Test News Author 92', 
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
                'Id'            => 2093, 
                'SectionId'     => 2092, 
                'DateCreate'    => '2000-01-01 00:00:00', 
                'Title'         => 'Test News Title 93', 
                'Author'        => 'Test News Author 93', 
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
                'Id'            => 2094, 
                'SectionId'     => 2091, 
                'DateCreate'    => '2222-01-01 00:00:00', 
                'Title'         => 'Test News Title 94', 
                'Author'        => 'Test News Author 94', 
                'Icon'          => '', 
                'Announce'      => '', 
                'Body'          => '', 
                'ViewedCnt'     => 0, 
                'IsHidden'      => 0, 
                'IsRss'         => 1, 
            ]
        );
        $this->params->page = 1;
        $this->params->pageSize = 10;
        $this->params->sectionId = 2091;
        //remove travis-ci VM id from var_dump
        $content = preg_replace('/ \/tmp\/tmp.+\n/', ' ', $this->getRenderContent());
        $this->assertTrue(false !== strpos($content, 'test view template begin'));
        $this->assertTrue(false !== strpos($content, '$item NULL'));
        $this->assertTrue(false !== strpos($content, '$items array(1)'));
        $this->assertTrue(false !== strpos($content, 'test view template end'));
    }
}
