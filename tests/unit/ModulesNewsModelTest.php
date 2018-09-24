<?php
require_once __DIR__  . DIRECTORY_SEPARATOR . '_presets.php';
require_once 'ModulesAbstractModelTest.php';

use \Ufocms\Frontend\Config;
use \Ufocms\Frontend\Container;
use \Ufocms\Frontend\Core;
use \Ufocms\Frontend\Db;
use \Ufocms\Frontend\Params;
use \Ufocms\Modules\News\Model;

class ModulesNewsModelTest extends ModulesAbstractModelTest
{
    /**
     * @var Params
     */
    protected $params;
    
    /**
     * @var Db
     */
    protected $db;
    
    protected function _before()
    {
        $this->params = new Params();
        $this->params->page = 1;
        $this->params->pageSize = 10;
        $this->db = new Db();
        $this->model = $this->getModel();
    }
    
    protected function _after()
    {
        if (null !== $this->db) {
            $this->db->close();
            $this->db = null;
        }
    }
    
    protected function getModel(array $params = [], $withCore = false)
    {
        $core = null;
        if ($withCore) {
            $core = $this->getCore();
        }
        $moduleParams = array_merge(
            [
                'isRss'     => false, 
                'isYandex'  => false, 
                'isYaDzen'  => false, 
                'isRambler' => false, 
                'isYaTurbo' => false, 
            ], 
            $params
        );
        $container = new Container([
            'db'            => &$this->db, 
            'core'          => &$core, 
            'params'        => &$this->params, 
            'moduleParams'  => &$moduleParams, 
        ]);
        return new Model($container);
    }
    
    protected function getCore()
    {
        $config = $this->makeEmpty(new Config());
        $params = new Params();
        return new Core($config, $params, $this->db);
    }
    
    // tests
    public function testGetSettings()
    {
        $this->params->sectionId = 0;
        $items = $this->model->getSettings();
        $this->assertNull($items);
        
        $this->tester->haveInDatabase(
            'news_sections', 
            [
                'Id'            => 2001, 
                'SectionId'     => 2001, 
                'BodyHead'      => 'Test News Sections Header 1', 
                'BodyFoot'      => 'Test News Sections Footer 1', 
                'PageLength'    => 15, 
            ]
        );
        $this->tester->haveInDatabase(
            'news_sections', 
            [
                'Id'            => 2002, 
                'SectionId'     => 2002, 
                'BodyHead'      => 'Test News Sections Header 2', 
                'BodyFoot'      => 'Test News Sections Footer 2', 
                'PageLength'    => 15, 
            ]
        );
        $this->params->sectionId = 2002;
        $items = $this->model->getSettings();
        $this->assertTrue(is_array($items));
        $this->assertTrue(array_key_exists('SectionId', $items));
        $this->assertTrue(array_key_exists('BodyHead', $items));
        $this->assertTrue(array_key_exists('BodyFoot', $items));
        $this->assertTrue(array_key_exists('IconAttributes', $items));
        $this->assertTrue(array_key_exists('PageLength', $items));
        $this->assertTrue(array_key_exists('AnnounceLength', $items));
        $this->assertEquals('Test News Sections Header 2', $items['BodyHead']);
        $this->assertEquals('Test News Sections Footer 2', $items['BodyFoot']);
        $this->assertEquals(15, $items['PageLength']);
        $this->assertEquals(255, $items['AnnounceLength']);
    }
    
    public function testGetItem()
    {
        $item = $this->model->getItem();
        $this->assertNull($item);
        
        $this->tester->haveInDatabase(
            'news', 
            [
                'Id'            => 2003, 
                'SectionId'     => 2003, 
                'DateCreate'    => '2000-01-01 00:00:00', 
                'Title'         => 'Test News Title 3', 
                'Author'        => 'Test News Author 3', 
                'Icon'          => '', 
                'Announce'      => '', 
                'Body'          => '', 
                'ViewedCnt'     => 0, 
                'IsHidden'      => 0, 
                'IsRss'         => 1, 
            ]
        );
        $this->params->itemId = 2003;
        $item = $this->model->getItem();
        $this->assertTrue(is_array($item));
        $this->assertTrue(array_key_exists('SectionId', $item));
        $this->assertTrue(array_key_exists('DateCreate', $item));
        $this->assertTrue(array_key_exists('Title', $item));
        $this->assertTrue(array_key_exists('Author', $item));
        $this->assertEquals(2003, $item['SectionId']);
        $this->assertEquals('2000-01-01 00:00:00', $item['DateCreate']);
        $this->assertEquals('Test News Title 3', $item['Title']);
        $this->assertEquals('Test News Author 3', $item['Author']);
        
        $this->tester->haveInDatabase(
            'news', 
            [
                'Id'            => 2004, 
                'SectionId'     => 2004, 
                'DateCreate'    => '2000-01-01 00:00:00', 
                'Title'         => 'Test News Title 4', 
                'Author'        => 'Test News Author 4', 
                'Icon'          => '', 
                'Announce'      => '', 
                'Body'          => '', 
                'ViewedCnt'     => 0, 
                'IsHidden'      => 1, 
                'IsRss'         => 1, 
            ]
        );
        $this->params->itemId = 2004;
        $model = $this->getModel();
        $item = $model->getItem();
        $this->assertNull($item);
        
        $this->tester->haveInDatabase(
            'news', 
            [
                'Id'            => 2005, 
                'SectionId'     => 2005, 
                'DateCreate'    => '2222-01-01 00:00:00', 
                'Title'         => 'Test News Title 4', 
                'Author'        => 'Test News Author 4', 
                'Icon'          => '', 
                'Announce'      => '', 
                'Body'          => '', 
                'ViewedCnt'     => 0, 
                'IsHidden'      => 0, 
                'IsRss'         => 1, 
            ]
        );
        $this->params->itemId = 2005;
        $model = $this->getModel();
        $item = $model->getItem();
        $this->assertNull($item);
    }
    
    public function testGetItemsCount()
    {
        $itemsCount = $this->model->getItemsCount();
        $this->assertNull($itemsCount);
        
        $this->params->sectionId = 0;
        $items = $this->model->getItems();
        $itemsCount = $this->model->getItemsCount();
        $this->assertNotNull($itemsCount);
        $this->assertEquals(0, $itemsCount);
        
        $this->tester->haveInDatabase(
            'news', 
            [
                'Id'            => 2011, 
                'SectionId'     => 2011, 
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
                'Id'            => 2012, 
                'SectionId'     => 2011, 
                'DateCreate'    => '2000-01-01 00:00:00', 
                'Title'         => 'Test News Title 12', 
                'Author'        => 'Test News Author 12', 
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
                'Id'            => 2013, 
                'SectionId'     => 2011, 
                'DateCreate'    => '2222-01-01 00:00:00', 
                'Title'         => 'Test News Title 13', 
                'Author'        => 'Test News Author 13', 
                'Icon'          => '', 
                'Announce'      => '', 
                'Body'          => '', 
                'ViewedCnt'     => 0, 
                'IsHidden'      => 0, 
                'IsRss'         => 1, 
            ]
        );
        $this->params->sectionId = 2011;
        $model = $this->getModel();
        $items = $model->getItems();
        $itemsCount = $model->getItemsCount();
        $this->assertNotNull($itemsCount);
        $this->assertEquals(1, $itemsCount);
    }
    
    public function testGetItemsByDate()
    {
        $this->params->sectionId = 0;
        $model = $this->getModel(['date' => '1970-01-01']);
        $items = $model->getItemsByDate();
        $itemsCount = $model->getItemsCount();
        $this->assertNotNull($itemsCount);
        $this->assertTrue(0 == $itemsCount);
        
        $this->tester->haveInDatabase(
            'news', 
            [
                'Id'            => 2021, 
                'SectionId'     => 2021, 
                'DateCreate'    => '2000-01-01 00:00:00', 
                'Title'         => 'Test News Title 21', 
                'Author'        => 'Test News Author 21', 
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
                'Id'            => 2022, 
                'SectionId'     => 2021, 
                'DateCreate'    => '2000-01-01 00:00:00', 
                'Title'         => 'Test News Title 22', 
                'Author'        => 'Test News Author 22', 
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
                'Id'            => 2023, 
                'SectionId'     => 2021, 
                'DateCreate'    => '2001-01-01 00:00:00', 
                'Title'         => 'Test News Title 23', 
                'Author'        => 'Test News Author 23', 
                'Icon'          => '', 
                'Announce'      => '', 
                'Body'          => '', 
                'ViewedCnt'     => 0, 
                'IsHidden'      => 0, 
                'IsRss'         => 1, 
            ]
        );
        $this->params->sectionId = 2021;
        $model = $this->getModel(['date' => '2000-01-01']);
        $items = $model->getItemsByDate();
        $itemsCount = $model->getItemsCount();
        $this->assertNotNull($itemsCount);
        $this->assertEquals(1, $itemsCount);
    }
    
    public function testGetItemsByAuthor()
    {
        $this->params->sectionId = 0;
        $model = $this->getModel(['author' => 'not existing author']);
        $items = $model->getItemsByAuthor();
        $itemsCount = $model->getItemsCount();
        $this->assertNotNull($itemsCount);
        $this->assertEquals(0, $itemsCount);
        
        $this->tester->haveInDatabase(
            'news', 
            [
                'Id'            => 2031, 
                'SectionId'     => 2031, 
                'DateCreate'    => '2000-01-01 00:00:00', 
                'Title'         => 'Test News Title 31', 
                'Author'        => 'Test News Author 31', 
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
                'Id'            => 2032, 
                'SectionId'     => 2031, 
                'DateCreate'    => '2000-01-01 00:00:00', 
                'Title'         => 'Test News Title 32', 
                'Author'        => 'Test News Author 31', 
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
                'Id'            => 2033, 
                'SectionId'     => 2031, 
                'DateCreate'    => '2222-01-01 00:00:00', 
                'Title'         => 'Test News Title 33', 
                'Author'        => 'Test News Author 31', 
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
                'Id'            => 2034, 
                'SectionId'     => 2031, 
                'DateCreate'    => '2000-01-01 00:00:00', 
                'Title'         => 'Test News Title 34', 
                'Author'        => 'Test News Author 32', 
                'Icon'          => '', 
                'Announce'      => '', 
                'Body'          => '', 
                'ViewedCnt'     => 0, 
                'IsHidden'      => 0, 
                'IsRss'         => 1, 
            ]
        );
        $this->params->sectionId = 2031;
        $model = $this->getModel(['author' => 'Test News Author 31']);
        $items = $model->getItemsByAuthor();
        $itemsCount = $model->getItemsCount();
        $this->assertNotNull($itemsCount);
        $this->assertEquals(1, $itemsCount);
    }
    
    public function testGetSections()
    {
        $this->params->sectionId = 0;
        $model = $this->getModel([], true);
        $items = $model->getSections();
        $this->assertNotNull($items);
        $this->assertTrue(is_array($items));
        
        $this->tester->haveInDatabase(
            'sections', 
            [
                'id'        => 2041, 
                'topid'     => 2041, 
                'parentid'  => 0, 
                'moduleid'  => 2, 
                'path'      => '/test-news-1/', 
                'indic'     => 'Test News Sections 1', 
                'isenabled' => 1, 
            ]
        );
        $this->tester->haveInDatabase(
            'sections', 
            [
                'id'        => 2042, 
                'topid'     => 2042, 
                'parentid'  => 0, 
                'moduleid'  => 2, 
                'path'      => '/test-news-2/', 
                'indic'     => 'Test News Sections 2', 
                'isenabled' => 0, 
            ]
        );
        $this->tester->haveInDatabase(
            'sections', 
            [
                'id'        => 2043, 
                'topid'     => 2043, 
                'parentid'  => 0, 
                'moduleid'  => 0, 
                'path'      => '/test-news-3/', 
                'indic'     => 'Test News Sections 3', 
                'isenabled' => 1, 
            ]
        );
        $this->params->sectionId = 2041;
        $model = $this->getModel([], true);
        $items = $model->getSections();
        $this->assertNotNull($items);
        $this->assertTrue(is_array($items));
        $this->assertEquals(1, count($items));
        $this->assertTrue(array_key_exists('Value', $items[0]));
        $this->assertTrue(array_key_exists('Title', $items[0]));
        $this->assertEquals(2041, $items[0]['Value']);
        $this->assertEquals('Test News Sections 1', $items[0]['Title']);
    }
}
