<?php
require_once __DIR__  . DIRECTORY_SEPARATOR . '_presets.php';
require_once 'ModulesAbstractControllerTest.php';
require_once 'ModulesControllerTrait.php';

use \Ufocms\Modules\News\Controller;

class ModulesNewsControllerTest extends ModulesAbstractControllerTest
{
    protected function getModuleClasses()
    {
        return [
            'Model' => '\Ufocms\Modules\News\Model', 
        ];
    }
    
    protected function getController($container)
    {
        return new class($container) extends Controller {
            use ModulesControllerTrait;
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
        $controller = $this->getController($this->getContainer());
        $context = $controller->getGetModuleContext();
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
        $controller = $this->getController($this->getContainer());
        $context = $controller->getGetModuleContext();
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
        $controller = $this->getController($this->getContainer());
        $this->params->itemId = $itemId;
        
        if (!$exists) {
            $this->expectedExceptionContains(
                function() use ($controller) { $controller->getGetModuleContext(); }, 
                '404: Item not exists'
            );
        } else {
            $context = $controller->getGetModuleContext();
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
        $controller = $this->getController($this->getContainer(['moduleParams' => ['date' => '2012-12-22']]));
        $context = $controller->getGetModuleContext();
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
        $controller = $this->getController($this->getContainer(['moduleParams' => ['date' => null, 'author' => 'Test News Author 81']]));
        $context = $controller->getGetModuleContext();
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
        $this->params->sectionParams = ['rss'];
        $this->assertEquals('rss.php', $this->getController($this->getContainer())->getLayout());
        
        $this->params->sectionParams = ['yandex'];
        $this->assertEquals('yandex.php', $this->getController($this->getContainer())->getLayout());
        
        $this->params->sectionParams = ['yadzen'];
        $this->assertEquals('yadzen.php', $this->getController($this->getContainer())->getLayout());
        
        $this->params->sectionParams = ['yaturbo'];
        $this->assertEquals('yaturbo.php', $this->getController($this->getContainer())->getLayout());
        
        $this->params->sectionParams = ['rambler'];
        $this->assertEquals('rambler.php', $this->getController($this->getContainer())->getLayout());
        
        $this->params->sectionParams = ['amp'];
        $this->assertEquals('itemamp.php', $this->getController($this->getContainer())->getLayout());
        
        $this->params->sectionParams = [];
        $this->assertEquals('/index.php', $this->getController($this->getContainer())->getLayout());
    }
    
    public function testDispatch()
    {
        $this->testModuleParams(['isYandex', 'path', 'yandex', true]);
        $this->testModuleParams(['isYaDzen', 'path', 'yadzen', true]);
        $this->testModuleParams(['isYaTurbo', 'path', 'yaturbo', true]);
        $this->testModuleParams(['isRambler', 'path', 'rambler', true]);
        $this->testModuleParams(['isAMP', 'path', 'amp', true]);
        $this->testModuleParams(['author', 'get', 'author', 'some author name']);
        
        $this->testModuleParams([
            ['author', 'get', 'author', 'some author name'], 
            ['author', 'get', 'author', 'some author name'], 
        ]);
        
        $this->params->sectionParams = ['123', 'amp'];
        $this->expectedException('404: Item not exists');
        
        $this->params->sectionParams = ['amp', 'amp'];
        $this->expectedException('404: Module parameter unknown');
        
        $this->params->sectionParams = ['yandex', '123'];
        $this->expectedException('404: Module parameter unknown');
    }
}
