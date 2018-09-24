<?php
require_once __DIR__  . DIRECTORY_SEPARATOR . '_presets.php';
require_once 'ModulesAbstractViewTest.php';
require_once 'ModulesViewTrait.php';

use \Ufocms\Frontend\Config;
use \Ufocms\Frontend\Container;
use \Ufocms\Modules\News\Model;
use \Ufocms\Modules\News\View;

class ModulesNewsViewTest extends ModulesAbstractViewTest
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
    
    protected function getViewByParams(array $params)
    {
        $container = new Container($params);
        return new class($container) extends View {
            protected $context = [];
            protected function findTemplate($theme, $module, $entry)
            {
                return $entry;
            }
            public function getLayout()
            {
                return parent::getLayout();
            }
            public function renderHead($entry = null)
            {
                parent::renderHead();
            }
        };
    }
    
    protected function getViewForContext($container)
    {
        return new class($container) extends View {
            public function getGetModuleContext()
            {
                return $this->getModuleContext();
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
    public function testGetModuleContextSectionNotExist()
    {
        $this->params->sectionId = 0;
        $view = $this->getViewForContext($this->getContainer());
        $context = $view->getGetModuleContext();
        $this->assertTrue(is_array($context));
        $this->assertTrue(array_key_exists('items', $context));
        $this->assertNotNull($context['items']);
        $this->assertTrue(is_array($context['items']));
        $this->assertEquals(0, count($context['items']));
    }
    
    public function testGetModuleContextSectionExists()
    {
        $this->tester->haveInDatabase(
            'news_sections', 
            [
                'Id'            => 2051, 
                'SectionId'     => 2051, 
                'BodyHead'      => 'Test News Sections Header 51', 
                'BodyFoot'      => 'Test News Sections Footer 51', 
                'PageLength'    => 15, 
            ]
        );
        $this->tester->haveInDatabase(
            'news', 
            [
                'Id'            => 2051, 
                'SectionId'     => 2051, 
                'DateCreate'    => '2000-01-01 00:00:00', 
                'Title'         => 'Test News Title 51', 
                'Author'        => 'Test News Author 51', 
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
                'Id'            => 2052, 
                'SectionId'     => 2051, 
                'DateCreate'    => '2000-01-01 00:00:00', 
                'Title'         => 'Test News Title 52', 
                'Author'        => 'Test News Author 52', 
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
                'Id'            => 2053, 
                'SectionId'     => 2052, 
                'DateCreate'    => '2000-01-01 00:00:00', 
                'Title'         => 'Test News Title 53', 
                'Author'        => 'Test News Author 53', 
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
                'Id'            => 2054, 
                'SectionId'     => 2051, 
                'DateCreate'    => '2222-01-01 00:00:00', 
                'Title'         => 'Test News Title 54', 
                'Author'        => 'Test News Author 54', 
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
        $this->params->sectionId = 2051;
        $view = $this->getViewForContext($this->getContainer());
        $context = $view->getGetModuleContext();
        $this->assertTrue(is_array($context));
        $this->assertTrue(array_key_exists('item', $context));
        $this->assertTrue(array_key_exists('items', $context));
        $this->assertTrue(array_key_exists('itemsCount', $context));
        $this->assertNull($context['item']);
        $this->assertTrue(is_array($context['items']));
        $this->assertNotNull($context['itemsCount']);
        $this->assertEquals(1, $context['itemsCount']);
    }
    
    protected function testGetModuleContextItem(array $item, $itemId, $exists = true)
    {
        $this->tester->haveInDatabase('news', $item);
        $this->params->itemId = $itemId;
        
        $view = $this->getViewForContext($this->getContainer());
        if (!$exists) {
            $this->expectedExceptionContains(
                function() use ($view) { $view->getGetModuleContext(); }, 
                '404: Item not exists'
            );
        } else {
            $view = $this->getViewForContext($this->getContainer());
            $context = $view->getGetModuleContext();
            $this->assertTrue(is_array($context));
            $this->assertTrue(array_key_exists('item', $context));
            $this->assertTrue(array_key_exists('items', $context));
            $this->assertTrue(array_key_exists('itemsCount', $context));
            $this->assertTrue(is_array($context['item']));
            $this->assertNull($context['items']);
            $this->assertNull($context['itemsCount']);
            $this->assertTrue(array_key_exists('Title', $context['item']));
            $this->assertEquals($item['Title'], $context['item']['Title']);
        }
    }
    
    public function testGetModuleContextItemNotExists()
    {
        $this->testGetModuleContextItem(
            [
                'Id'            => 2061, 
                'SectionId'     => 2061, 
                'DateCreate'    => '2000-01-01 00:00:00', 
                'Title'         => 'Test News Title 61', 
                'Author'        => 'Test News Author 61', 
                'Icon'          => '', 
                'Announce'      => '', 
                'Body'          => '', 
                'ViewedCnt'     => 0, 
                'IsHidden'      => 0, 
                'IsRss'         => 1, 
            ], 
            2062, 
            false
        );
        
        $this->testGetModuleContextItem(
            [
                'Id'            => 2062, 
                'SectionId'     => 2062, 
                'DateCreate'    => '2000-01-01 00:00:00', 
                'Title'         => 'Test News Title 62', 
                'Author'        => 'Test News Author 62', 
                'Icon'          => '', 
                'Announce'      => '', 
                'Body'          => '', 
                'ViewedCnt'     => 0, 
                'IsHidden'      => 1, 
                'IsRss'         => 1, 
            ], 
            2062, 
            false
        );
        
        $this->testGetModuleContextItem(
            [
                'Id'            => 2063, 
                'SectionId'     => 2063, 
                'DateCreate'    => '2222-01-01 00:00:00', 
                'Title'         => 'Test News Title 63', 
                'Author'        => 'Test News Author 63', 
                'Icon'          => '', 
                'Announce'      => '', 
                'Body'          => '', 
                'ViewedCnt'     => 0, 
                'IsHidden'      => 0, 
                'IsRss'         => 1, 
            ], 
            2063, 
            false
        );
    }
    
    public function testGetModuleContextItemExists()
    {
        $this->testGetModuleContextItem(
            [
                'Id'            => 2064, 
                'SectionId'     => 2064, 
                'DateCreate'    => '2000-01-01 00:00:00', 
                'Title'         => 'Test News Title 64', 
                'Author'        => 'Test News Author 64', 
                'Icon'          => '', 
                'Announce'      => '', 
                'Body'          => '', 
                'ViewedCnt'     => 0, 
                'IsHidden'      => 0, 
                'IsRss'         => 1, 
            ], 
            2064, 
            true
        );
    }
    
    public function testGetModuleContextItemsByDate()
    {
        $this->tester->haveInDatabase(
            'news_sections', 
            [
                'Id'            => 2071, 
                'SectionId'     => 2071, 
                'BodyHead'      => 'Test News Sections Header 71', 
                'BodyFoot'      => 'Test News Sections Footer 71', 
                'PageLength'    => 15, 
            ]
        );
        $this->tester->haveInDatabase(
            'news', 
            [
                'Id'            => 2071, 
                'SectionId'     => 2071, 
                'DateCreate'    => '2012-12-22 00:00:00', 
                'Title'         => 'Test News Title 71', 
                'Author'        => 'Test News Author 71', 
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
                'Id'            => 2072, 
                'SectionId'     => 2071, 
                'DateCreate'    => '2000-01-01 00:00:00', 
                'Title'         => 'Test News Title 72', 
                'Author'        => 'Test News Author 72', 
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
                'Id'            => 2073, 
                'SectionId'     => 2072, 
                'DateCreate'    => '2000-01-01 00:00:00', 
                'Title'         => 'Test News Title 73', 
                'Author'        => 'Test News Author 73', 
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
                'Id'            => 2074, 
                'SectionId'     => 2071, 
                'DateCreate'    => '2222-01-01 00:00:00', 
                'Title'         => 'Test News Title 74', 
                'Author'        => 'Test News Author 74', 
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
        $this->params->sectionId = 2071;
        $view = $this->getViewForContext($this->getContainer(['moduleParams' => ['date' => '2012-12-22']]));
        $context = $view->getGetModuleContext();
        $this->assertTrue(is_array($context));
        $this->assertTrue(array_key_exists('item', $context));
        $this->assertTrue(array_key_exists('items', $context));
        $this->assertTrue(array_key_exists('itemsCount', $context));
        $this->assertNull($context['item']);
        $this->assertTrue(is_array($context['items']));
        $this->assertNotNull($context['itemsCount']);
        $this->assertEquals(1, $context['itemsCount']);
    }
    
    public function testGetModuleContextItemsByAuthor()
    {
        $this->tester->haveInDatabase(
            'news_sections', 
            [
                'Id'            => 2081, 
                'SectionId'     => 2081, 
                'BodyHead'      => 'Test News Sections Header 81', 
                'BodyFoot'      => 'Test News Sections Footer 81', 
                'PageLength'    => 15, 
            ]
        );
        $this->tester->haveInDatabase(
            'news', 
            [
                'Id'            => 2081, 
                'SectionId'     => 2081, 
                'DateCreate'    => '2000-01-01 00:00:00', 
                'Title'         => 'Test News Title 81', 
                'Author'        => 'Test News Author 81', 
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
                'Id'            => 2082, 
                'SectionId'     => 2081, 
                'DateCreate'    => '2000-01-01 00:00:00', 
                'Title'         => 'Test News Title 82', 
                'Author'        => 'Test News Author 82', 
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
                'Id'            => 2083, 
                'SectionId'     => 2082, 
                'DateCreate'    => '2000-01-01 00:00:00', 
                'Title'         => 'Test News Title 83', 
                'Author'        => 'Test News Author 83', 
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
                'Id'            => 2084, 
                'SectionId'     => 2081, 
                'DateCreate'    => '2222-01-01 00:00:00', 
                'Title'         => 'Test News Title 84', 
                'Author'        => 'Test News Author 84', 
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
        $this->params->sectionId = 2081;
        $view = $this->getViewForContext($this->getContainer(['moduleParams' => ['date' => null, 'author' => 'Test News Author 81']]));
        $context = $view->getGetModuleContext();
        $this->assertTrue(is_array($context));
        $this->assertTrue(array_key_exists('item', $context));
        $this->assertTrue(array_key_exists('items', $context));
        $this->assertTrue(array_key_exists('itemsCount', $context));
        $this->assertNull($context['item']);
        $this->assertTrue(is_array($context['items']));
        $this->assertNotNull($context['itemsCount']);
        $this->assertEquals(1, $context['itemsCount']);
    }
    
    public function testGetLayout()
    {
        $params = [
            'moduleParams' => [
                'isRss'     => true, 
                'isYandex'  => false, 
                'isYaDzen'  => false, 
                'isYaTurbo' => false, 
                'isRambler' => false, 
                'isAMP'     => false, 
            ]
        ];
        $view = $this->getViewByParams($params);
        $this->assertTrue('/rss.php' == $view->getLayout());
        
        $params = [
            'moduleParams' => [
                'isRss'     => false, 
                'isYandex'  => true, 
                'isYaDzen'  => false, 
                'isYaTurbo' => false, 
                'isRambler' => false, 
                'isAMP'     => false, 
            ]
        ];
        $view = $this->getViewByParams($params);
        $this->assertTrue('/yandex.php' == $view->getLayout());
        
        $params = [
            'moduleParams' => [
                'isRss'     => false, 
                'isYandex'  => false, 
                'isYaDzen'  => true, 
                'isYaTurbo' => false, 
                'isRambler' => false, 
                'isAMP'     => false, 
            ]
        ];
        $view = $this->getViewByParams($params);
        $this->assertTrue('/yadzen.php' == $view->getLayout());
        
        $params = [
            'moduleParams' => [
                'isRss'     => false, 
                'isYandex'  => false, 
                'isYaDzen'  => false, 
                'isYaTurbo' => true, 
                'isRambler' => false, 
                'isAMP'     => false, 
            ]
        ];
        $view = $this->getViewByParams($params);
        $this->assertTrue('/yaturbo.php' == $view->getLayout());
        
        $params = [
            'moduleParams' => [
                'isRss'     => false, 
                'isYandex'  => false, 
                'isYaDzen'  => false, 
                'isYaTurbo' => false, 
                'isRambler' => true, 
                'isAMP'     => false, 
            ]
        ];
        $view = $this->getViewByParams($params);
        $this->assertTrue('/rambler.php' == $view->getLayout());
        
        $params = [
            'moduleParams' => [
                'isRss'     => false, 
                'isYandex'  => false, 
                'isYaDzen'  => false, 
                'isYaTurbo' => false, 
                'isRambler' => false, 
                'isAMP'     => true, 
            ]
        ];
        $view = $this->getViewByParams($params);
        $this->assertTrue('/itemamp.php' == $view->getLayout());
        
        $params = [
            'config' => new Config(), 
            'moduleParams' => [
                'isRss'     => false, 
                'isYandex'  => false, 
                'isYaDzen'  => false, 
                'isYaTurbo' => false, 
                'isRambler' => false, 
                'isAMP'     => false, 
            ]
        ];
        $view = $this->getViewByParams($params);
        $this->assertTrue('/index.php' == $view->getLayout());
    }
    
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
        $content = $this->getRenderContent();
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
        $content = $this->getRenderContent();
        $this->assertTrue(false !== strpos($content, 'test view template begin'));
        $this->assertTrue(false !== strpos($content, '$item NULL'));
        $this->assertTrue(false !== strpos($content, '$items array(1)'));
        $this->assertTrue(false !== strpos($content, 'test view template end'));
    }
}
