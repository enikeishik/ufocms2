<?php
require_once __DIR__  . DIRECTORY_SEPARATOR . '_presets.php';

use \Ufocms\Frontend\Config;
use \Ufocms\Frontend\Container;
use \Ufocms\Frontend\Core;
use \Ufocms\Frontend\Db;
use \Ufocms\Frontend\Error as UfocmsError;
use \Ufocms\Frontend\Interaction;
use \Ufocms\Frontend\InteractionManage;
use \Ufocms\Frontend\Params;

class FrontendCoreTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    
    /**
     * @var Config
     */
    protected $config;
    
    /**
     * @var Params
     */
    protected $params;
    
    /**
     * @var Db
     */
    protected $db;
    
    /**
     * @var Core
     */
    protected $core;
    
    protected function _before()
    {
        $this->config = new Config();
        $this->params = new Params();
        $this->db = new Db();
        $this->core = $this->getCore();
    }
    
    protected function _after()
    {
        if (null !== $this->db) {
            $this->db->close();
            $this->db = null;
        }
    }
    
    protected function getCore()
    {
        return new Core($this->config, $this->params, $this->db);
    }
    
    protected function testGetObject($objectType)
    {
        $method = 'get' . $objectType;
        $var = $this->core->$method();
        $this->assertTrue(is_object($var));
        $this->assertTrue(is_a($var, '\\Ufocms\\Frontend\\' . $objectType));
    }
    
    // tests
    
    public function testSetGetCurrentSection()
    {
        $this->core->setCurrentSection();
        $item = $this->core->getCurrentSection();
        $this->assertNull($item);
        
        $this->tester->haveInDatabase(
            'sections', 
            [
                'id'        => 100001, 
                'topid'     => 100001, 
                'parentid'  => 0, 
                'moduleid'  => 1, 
                'path'      => '/test-section-path-01/', 
                'indic'     => 'Test Section 01', 
                'isenabled' => 1, 
            ]
        );
        $this->params->systemPath = false;
        $this->params->sectionPath = '/test-section-path-01/';
        $this->core->setCurrentSection();
        $item = $this->core->getCurrentSection();
        $this->assertTrue(is_array($item));
        $this->assertTrue(array_key_exists('id', $item));
        $this->assertTrue(array_key_exists('path', $item));
        $this->assertTrue(array_key_exists('indic', $item));
        $this->assertEquals(100001, $item['id']);
        $this->assertEquals('/test-section-path-01/', $item['path']);
        $this->assertEquals('Test Section 01', $item['indic']);
        $this->assertEquals(1, $item['isenabled']);
        
        $this->tester->haveInDatabase(
            'sections', 
            [
                'id'        => 100002, 
                'topid'     => 100002, 
                'parentid'  => 0, 
                'moduleid'  => 1, 
                'path'      => '/test-section-path-02/', 
                'indic'     => 'Test Section 02', 
                'isenabled' => 0, 
            ]
        );
        $this->params->systemPath = false;
        $this->params->sectionPath = '/test-section-path-02/';
        $this->core->setCurrentSection();
        $item = $this->core->getCurrentSection();
        $this->assertTrue(is_array($item));
        $this->assertTrue(array_key_exists('id', $item));
        $this->assertTrue(array_key_exists('path', $item));
        $this->assertTrue(array_key_exists('indic', $item));
        $this->assertEquals(100002, $item['id']);
        $this->assertEquals('/test-section-path-02/', $item['path']);
        $this->assertEquals('Test Section 02', $item['indic']);
        $this->assertEquals(0, $item['isenabled']);
        
        $this->params->systemPath = false;
        $this->params->sectionPath = '/test-section-path-03/';
        $this->core->setCurrentSection();
        $item = $this->core->getCurrentSection();
        $this->assertNull($item);
        
        $this->params->systemPath = true;
        foreach ($this->config->systemSections as $path => $class) {
            $this->params->sectionPath = $path;
            $this->params->moduleName = $class;
            break;
        }
        $this->core->setCurrentSection();
        $item = $this->core->getCurrentSection();
        $class = '\\Ufocms\\Modules\\' . $this->params->moduleName . '\\Section';
        $object = new $class();
        $section = $object->section;
        $section['path'] = $this->params->sectionPath;
        $this->assertEquals($section, $item);
    }
    
    public function testIsPathExists()
    {
        $this->assertFalse($this->core->isPathExists(''));
        $this->assertTrue($this->core->isPathExists('/'));
        $this->assertFalse($this->core->isPathExists('/not-existing-path-123/'));
    }
    
    public function testGetMaxExistingPath()
    {
        $path = $this->core->getMaxExistingPath([]);
        $this->assertNull($path);
        
        $path = $this->core->getMaxExistingPath(['/']);
        $this->assertNotNull($path);
        $this->assertEquals('/', $path);
        
        $path = $this->core->getMaxExistingPath(['/', '/not-existing-path-123/']);
        $this->assertNotNull($path);
        $this->assertEquals('/', $path);
        
        $path = $this->core->getMaxExistingPath(['/not-existing-path-123/']);
        $this->assertNull($path);
        
        $this->tester->haveInDatabase(
            'sections', 
            [
                'id'        => 100011, 
                'topid'     => 100011, 
                'parentid'  => 0, 
                'moduleid'  => 1, 
                'path'      => '/test-max-path-11/', 
                'indic'     => 'Test Section 11', 
                'isenabled' => 1, 
            ]
        );
        $this->tester->haveInDatabase(
            'sections', 
            [
                'id'        => 100012, 
                'topid'     => 100011, 
                'parentid'  => 100011, 
                'moduleid'  => 1, 
                'path'      => '/test-max-path-11/test-max-path-12/', 
                'indic'     => 'Test Section 12', 
                'isenabled' => 1, 
            ]
        );
        $path = $this->core->getMaxExistingPath(['/', '/test-max-path-11/', '/test-max-path-11/test-max-path-12/']);
        $this->assertNotNull($path);
        $this->assertEquals('/test-max-path-11/test-max-path-12/', $path);
    }
    
    public function testGetSite()
    {
        $item = $this->core->getSite();
        $this->assertTrue(is_array($item));
        $this->assertTrue(0 < count($item));
        $this->assertTrue(array_key_exists('SiteUrl', $item));
        $this->assertFalse(array_key_exists('NotExistsParamName', $item));
    }
    
    public function testGetSection()
    {
        $item = $this->core->getSection();
        $this->assertNull($item);
        
        $item = $this->core->getSection('');
        $this->assertNull($item);
        
        $item = $this->core->getSection('/not-existing-path-123/');
        $this->assertNull($item);
        
        $item = $this->core->getSection('/');
        $this->assertTrue(is_array($item));
        $this->assertTrue(array_key_exists('id', $item));
        $this->assertTrue(array_key_exists('path', $item));
        
        $item = $this->core->getSection(0);
        $this->assertNull($item);
        
        $item = $this->core->getSection(-1);
        $this->assertTrue(is_array($item));
        $this->assertTrue(array_key_exists('id', $item));
        $this->assertTrue(array_key_exists('path', $item));
        
        //make new object to prevent caching
        $this->core = $this->getCore();
        $item = $this->core->getSection(-1, 'id');
        $this->assertTrue(is_array($item));
        $this->assertTrue(array_key_exists('id', $item));
        $this->assertFalse(array_key_exists('path', $item));
        
        $this->core = $this->getCore();
        $item = $this->core->getSection(-1, 'id, path');
        $this->assertTrue(is_array($item));
        $this->assertTrue(array_key_exists('id', $item));
        $this->assertTrue(array_key_exists('path', $item));
        $this->assertFalse(array_key_exists('notpath', $item));
        
        $this->core = $this->getCore();
        $item = $this->core->getSection(-1, ['id', 'path']);
        $this->assertTrue(is_array($item));
        $this->assertTrue(array_key_exists('id', $item));
        $this->assertTrue(array_key_exists('path', $item));
        $this->assertFalse(array_key_exists('notpath', $item));
        
        $this->core = $this->getCore();
        $item = $this->core->getSection(-1, 'incorrect,fields');
        $this->assertNull($item);
    }
    
    public function testGetModule()
    {
        $item = $this->core->getModule();
        $this->assertNull($item);
        
        $this->tester->haveInDatabase(
            'sections', 
            [
                'id'        => 100021, 
                'topid'     => 100021, 
                'parentid'  => 0, 
                'moduleid'  => 100021, 
                'path'      => '/test-module-path-21/', 
                'indic'     => 'Test Section 21', 
                'isenabled' => 1, 
            ]
        );
        $this->params->sectionId = 100021;
        $item = $this->core->getModule();
        $this->assertNull($item);
        
        $this->tester->haveInDatabase(
            'modules', 
            [
                'id'        => 100022, 
                'muid'      => 100022, 
                'mname'     => 'Test Module Name', 
                'mfile'     => 'mod_test_module.php', 
                'isenabled' => 1, 
            ]
        );
        $this->tester->haveInDatabase(
            'sections', 
            [
                'id'        => 100022, 
                'topid'     => 100022, 
                'parentid'  => 0, 
                'moduleid'  => 100022, 
                'path'      => '/test-module-path-22/', 
                'indic'     => 'Test Section 22', 
                'isenabled' => 1, 
            ]
        );
        $this->params->sectionId = 100022;
        $item = $this->core->getModule();
        $this->assertTrue(is_array($item));
        $this->assertTrue(array_key_exists('muid', $item));
        $this->assertTrue(array_key_exists('mfile', $item));
        $this->assertEquals(100022, $item['muid']);
        $this->assertEquals('mod_test_module.php', $item['mfile']);
    }
    
    public function testGetSections()
    {
        $fields = null;
        $filter = null;
        $items = $this->core->getSections($fields, $filter);
        $this->assertTrue(is_array($items));
        
        $fields = null;
        $filter = "`path`=''";
        $items = $this->core->getSections($fields, $filter);
        $this->assertTrue(is_array($items));
        $this->assertEquals(0, count($items));
        
        $this->tester->haveInDatabase(
            'sections', 
            [
                'id'        => 100031, 
                'topid'     => 100031, 
                'parentid'  => 0, 
                'moduleid'  => 100031, 
                'mask'      => '0031', 
                'path'      => '/test-section-path-31/', 
                'indic'     => 'Test Section 31', 
                'isenabled' => 1, 
            ]
        );
        $this->tester->haveInDatabase(
            'sections', 
            [
                'id'        => 100032, 
                'topid'     => 100031, 
                'parentid'  => 100031, 
                'moduleid'  => 100031, 
                'mask'      => '00310001', 
                'path'      => '/test-section-path-32/', 
                'indic'     => 'Test Section 32', 
                'isenabled' => 1, 
            ]
        );
        $this->tester->haveInDatabase(
            'sections', 
            [
                'id'        => 100033, 
                'topid'     => 100031, 
                'parentid'  => 100031, 
                'moduleid'  => 100031, 
                'mask'      => '00310002', 
                'path'      => '/test-section-path-33/', 
                'indic'     => 'Test Section 33', 
                'isenabled' => 0, 
            ]
        );
        $this->tester->haveInDatabase(
            'sections', 
            [
                'id'        => 100034, 
                'topid'     => 100031, 
                'parentid'  => 100032, 
                'moduleid'  => 100031, 
                'mask'      => '003100010001', 
                'path'      => '/test-section-path-34/', 
                'indic'     => 'Test Section 34', 
                'isenabled' => 1, 
            ]
        );
        $fields = null;
        $filter = "`parentId`='100031'";
        $items = $this->core->getSections($fields, $filter);
        $this->assertTrue(is_array($items));
        $this->assertEquals(1, count($items));
    }
    
    public function testGetModuleSections()
    {
        $items = $this->core->getModuleSections(0);
        $this->assertTrue(is_array($items));
        $this->assertEquals(0, count($items));
        
        $items = $this->core->getModuleSections(-1);
        $this->assertTrue(is_array($items));
        $this->assertEquals(1, count($items));
        
        $items = $this->core->getModuleSections(1);
        $this->assertTrue(is_array($items));
    }
    
    public function testGetSectionParentsRecursive()
    {
        $items = $this->core->getSectionParentsRecursive(-1);
        $this->assertTrue(is_array($items));
        $this->assertEquals(0, count($items));
        
        $items = $this->core->getSectionParentsRecursive(0);
        $this->assertTrue(is_array($items));
        $this->assertEquals(0, count($items));
        
        $this->tester->haveInDatabase(
            'sections', 
            [
                'id'        => 100041, 
                'topid'     => 100041, 
                'parentid'  => 0, 
                'moduleid'  => 100041, 
                'mask'      => '0041', 
                'path'      => '/test-section-41/', 
                'indic'     => 'Test Section 41', 
                'isenabled' => 1, 
            ]
        );
        $this->tester->haveInDatabase(
            'sections', 
            [
                'id'        => 100042, 
                'topid'     => 100041, 
                'parentid'  => 100041, 
                'moduleid'  => 100041, 
                'mask'      => '00410001', 
                'path'      => '/test-section-42/', 
                'indic'     => 'Test Section 42', 
                'isenabled' => 1, 
            ]
        );
        $this->tester->haveInDatabase(
            'sections', 
            [
                'id'        => 100043, 
                'topid'     => 100041, 
                'parentid'  => 100042, 
                'moduleid'  => 100041, 
                'mask'      => '004100010001', 
                'path'      => '/test-section-43/', 
                'indic'     => 'Test Section 43', 
                'isenabled' => 1, 
            ]
        );
        $items = $this->core->getSectionParentsRecursive(100043);
        $this->assertTrue(is_array($items));
        $this->assertEquals(2, count($items));
        $this->assertEquals([100041, 100042], $items);
        
        $this->tester->haveInDatabase(
            'sections', 
            [
                'id'        => 100044, 
                'topid'     => 100045, 
                'parentid'  => 100045, 
                'moduleid'  => 100045, 
                'mask'      => '00450001', 
                'path'      => '/test-section-44/', 
                'indic'     => 'Test Section 44', 
                'isenabled' => 1, 
            ]
        );
        $items = $this->core->getSectionParentsRecursive(100044);
        $this->assertTrue(is_array($items));
        $this->assertEquals(1, count($items));
        $this->assertEquals([100045], $items);
    }
    
    public function testGetSectionParents()
    {
        $items = $this->core->getSectionParents();
        $this->assertNull($items);
        
        $items = $this->core->getSectionParents('id');
        $this->assertNull($items);
        
        $this->tester->haveInDatabase(
            'sections', 
            [
                'id'        => 100051, 
                'topid'     => 100051, 
                'parentid'  => 0, 
                'levelid'   => 0, 
                'moduleid'  => 100051, 
                'mask'      => '0051', 
                'path'      => '/test-section-51/', 
                'indic'     => 'Test Section 51', 
                'isenabled' => 1, 
            ]
        );
        $this->tester->haveInDatabase(
            'sections', 
            [
                'id'        => 100052, 
                'topid'     => 100051, 
                'parentid'  => 100051, 
                'levelid'   => 1, 
                'moduleid'  => 100051, 
                'mask'      => '00510001', 
                'path'      => '/test-section-52/', 
                'indic'     => 'Test Section 52', 
                'isenabled' => 1, 
            ]
        );
        $this->tester->haveInDatabase(
            'sections', 
            [
                'id'        => 100053, 
                'topid'     => 100051, 
                'parentid'  => 100052, 
                'levelid'   => 2, 
                'moduleid'  => 100051, 
                'mask'      => '005100010001', 
                'path'      => '/test-section-53/', 
                'indic'     => 'Test Section 53', 
                'isenabled' => 1, 
            ]
        );
        $this->params->systemPath = false;
        $this->params->sectionPath = '/test-section-53/';
        $core = $this->getCore();
        $core->setCurrentSection();
        $items = $core->getSectionParents();
        $this->assertTrue(is_array($items));
        $this->assertEquals(3, count($items));
        $this->assertTrue(is_array($items[2]));
        $this->assertTrue(array_key_exists('id', $items[2]));
        $this->assertEquals(100053, $items[2]['id']);
        $this->assertTrue(is_array($items[1]));
        $this->assertTrue(array_key_exists('id', $items[1]));
        $this->assertEquals(100052, $items[1]['id']);
        $this->assertTrue(is_array($items[0]));
        $this->assertTrue(array_key_exists('id', $items[0]));
        $this->assertEquals(100051, $items[0]['id']);
    }
    
    public function testGetWidgetsData()
    {
        $data = $this->core->getWidgetsData(10000, 10000);
        $this->assertTrue(is_array($data));
        $this->assertEquals(0, count($data));
        
        $this->tester->haveInDatabase(
            'widgets_types', 
            [
                'Id'        => 100001, 
                'ModuleId'  => 100001, 
                'Name'      => 'TestWidgetType', 
                'Title'     => 'Test Widget Type', 
            ]
        );
        $this->tester->haveInDatabase(
            'widgets', 
            [
                'Id'                => 100001, 
                'TypeId'            => 100001, 
                'PlaceId'           => 100001, 
                'OrderId'           => 1, 
                'IsDisabled'        => 0, 
                'ShowTitle'         => 1, 
                'SrcSections'       => '', 
                'Title'             => 'Test Widget', 
                'Content'           => '', 
                'Params'            => '{"Param1":"Value1","Param2":0,"Param3":1}', 
                'ContentExpiry'     => 0, 
                'ContentCreated'    => '', 
                'ContentHash'       => '', 
            ]
        );
        $this->tester->haveInDatabase(
            'widgets_targets', 
            [
                'Id'        => 100001, 
                'WidgetId'  => 100001, 
                'SectionId' => -1, 
            ]
        );
        $db = new Db();
        $core = new class($this->config, $this->params, $db) extends Core {
            public function getWidgetsData($targetId, $placeId, $offset = 0, $limit = 0)
            {
                $widgetsData = null;
                return parent::getWidgetsData($targetId, $placeId, $offset, $limit);
            }
        };
        $data = $core->getWidgetsData(-1, 100001);
        $this->assertTrue(is_array($data));
        $this->assertEquals(1, count($data));
        $this->assertTrue(is_array($data[0]));
        $this->assertTrue(array_key_exists('ShowTitle', $data[0]));
        $this->assertTrue(array_key_exists('SrcSections', $data[0]));
        $this->assertTrue(array_key_exists('Title', $data[0]));
        $this->assertTrue(array_key_exists('Content', $data[0]));
        $this->assertTrue(array_key_exists('Params', $data[0]));
        $this->assertTrue(array_key_exists('ModuleId', $data[0]));
        $this->assertEquals(1, $data[0]['ShowTitle']);
        $this->assertEquals('', $data[0]['SrcSections']);
        $this->assertEquals('Test Widget', $data[0]['Title']);
        $this->assertEquals('', $data[0]['Content']);
        $this->assertEquals('{"Param1":"Value1","Param2":0,"Param3":1}', $data[0]['Params']);
        $this->assertEquals(100001, $data[0]['ModuleId']);
    }
    
    public function testGetUsers()
    {
        $this->testGetObject('Users');
    }
    
    public function testGetQuotes()
    {
        $this->testGetObject('Quotes');
    }
    
    public function testGetComments()
    {
        $_SERVER['REQUEST_URI'] = '';
        $this->testGetObject('Comments');
    }
    
    public function testGetInteraction()
    {
        $this->testGetObject('Interaction');
    }
    
    public function testGetInteractionManage()
    {
        $this->testGetObject('InteractionManage');
    }
    
    public function testGetContainer()
    {
        $this->testGetObject('Container');
    }
    
    public function testRiseError()
    {
        $config = new Config();
        $params = new Params();
        $db = new Db();
        $core = $this->make(
            new Core($config, $params, $db), 
            ['riseError' => function($errNum) {
                
            }]
        );
        $core->riseError(0);
        
        $core = new class($config, $params, $db) extends Core {
            public function riseError($errNum, $errMsg = null, $options = null)
            {
                $_SERVER['DOCUMENT_ROOT'] = dirname(dirname(__DIR__));
                $config = new Config();
                $container = new Container(['config' => $config]);
                $error = new class($container) extends UfocmsError {
                    public function rise($errNum, $errMsg = null, $options = null)
                    {
                        return $this->getTemplate($errNum);
                    }
                };
                return $error->rise($errNum, $errMsg, $options);
            }
        };
        $this->assertNull($core->riseError(0));
        $this->assertNull($core->riseError(200));
        $this->assertNotNull($core->riseError(301));
        $this->assertNotNull($core->riseError(302));
        $this->assertNotNull($core->riseError(401));
        $this->assertNotNull($core->riseError(403));
        $this->assertNotNull($core->riseError(404));
        $this->assertNotNull($core->riseError(500));
    }
}
