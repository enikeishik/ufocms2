<?php
require_once __DIR__  . DIRECTORY_SEPARATOR . '_presets.php';

use \Ufocms\Frontend\Container;
use \Ufocms\Frontend\Debug;
use \Ufocms\Backend\Audit;
use \Ufocms\Backend\Db;
use \Ufocms\Backend\Config;
use \Ufocms\Backend\Core;
use \Ufocms\Backend\Params;

class BackendCoreTest extends \Codeception\Test\Unit
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
     * @var Core
     */
    protected $core;
    
    protected function _before()
    {
        $this->config = new Config();
        $this->params = new Params();
        $this->core = $this->getCore();
    }
    
    protected function _after()
    {
    }
    
    protected function getCore()
    {
        $audit = $this->make(
            new Audit($this->config), 
            ['record' => function($record) { }]
        );
        $debug = new class extends Debug {
            public static function varDump($var, $dump = true, $exit = true, $float = false)
            {
                echo $var;
            }
        };
        $db = new Db($audit);
        return new class($this->config, $this->params, $db, $debug) extends Core {
            public function riseError($errNum, $errMsg = null, $options = null)
            {
                throw new \Exception($errNum . ': ' . $errMsg);
            }
        };
    }
    
    protected function exceptionContains(callable $call, string $exceptionContains)
    {
        try {
            $call();
        } catch (\Exception $e) {
            $this->assertTrue(false !== strpos($e->getMessage(), $exceptionContains));
        }
    }
    
    protected function expectedNoException(callable $call)
    {
        try {
            $call();
            $this->assertTrue(true);
        } catch (\Exception $e) {
            $this->assertTrue(false);
        }
    }
    
    // tests
    
    public function testGetSite()
    {
        $this->tester->haveInDatabase(
            'siteparams', 
            [
                'Id'        => -101, 
                'PName'     => 'TestParamName', 
                'PValue'    => 'Test Param Value', 
            ]
        );
        
        $item = $this->core->getSite();
        $this->assertTrue(is_array($item));
        $this->assertTrue(0 < count($item));
        $this->assertTrue(array_key_exists('SiteUrl', $item));
        $this->assertTrue(is_array($item['SiteUrl']));
        $this->assertTrue(array_key_exists('PName', $item['SiteUrl']));
        $this->assertTrue(array_key_exists('PValue', $item['SiteUrl']));
        $this->assertEquals('SiteUrl', $item['SiteUrl']['PName']);
        $this->assertEquals('', $item['SiteUrl']['PValue']);
        
        $item = $this->core->getSite();
        $this->assertTrue(is_array($item));
        $this->assertTrue(0 < count($item));
        $this->assertTrue(array_key_exists('TestParamName', $item));
        $this->assertTrue(is_array($item['TestParamName']));
        $this->assertTrue(array_key_exists('PName', $item['TestParamName']));
        $this->assertTrue(array_key_exists('PValue', $item['TestParamName']));
        $this->assertEquals('TestParamName', $item['TestParamName']['PName']);
        $this->assertEquals('Test Param Value', $item['TestParamName']['PValue']);
    }
    
    public function testGetSection()
    {
        $item = $this->core->getSection();
        $this->assertNull($item);
        
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
        $this->tester->haveInDatabase(
            'modules', 
            [
                'id'        => -101, 
                'muid'      => -101, 
                'mname'     => 'Test Module Name', 
                'mfile'     => 'mod_test_module.php', 
                'isenabled' => 1, 
            ]
        );
        $this->tester->haveInDatabase(
            'sections', 
            [
                'id'        => -101, 
                'topid'     => -101, 
                'parentid'  => -101, 
                'moduleid'  => -101, 
                'path'      => '/test-module-path/', 
                'indic'     => 'Test Module Section', 
                'isenabled' => 1, 
            ]
        );
        $this->params->sectionId = -101;
        
        $item = $this->core->getModule();
        $this->assertTrue(is_array($item));
        $this->assertTrue(array_key_exists('id', $item));
        $this->assertTrue(array_key_exists('muid', $item));
        $this->assertTrue(array_key_exists('mname', $item));
        $this->assertTrue(array_key_exists('mfile', $item));
        $this->assertTrue(array_key_exists('isenabled', $item));
        $this->assertFalse(array_key_exists('notexists', $item));
        $this->assertEquals(-101, $item['id']);
        $this->assertEquals(-101, $item['muid']);
        $this->assertEquals('Test Module Name', $item['mname']);
        $this->assertEquals('mod_test_module.php', $item['mfile']);
        $this->assertEquals(1, $item['isenabled']);
    }
    
    public function testGetModuleByName()
    {
        $item = $this->core->getModuleByName('');
        $this->assertNull($item);
        
        $item = $this->core->getModuleByName('not-exists-module');
        $this->assertNull($item);
        
        $item = $this->core->getModuleByName('mainpage');
        $this->assertTrue(is_array($item));
        $this->assertTrue(array_key_exists('id', $item));
        $this->assertTrue(array_key_exists('muid', $item));
    }
    
    public function testGetSectionChildren()
    {
        $items = $this->core->getSectionChildren(10000);
        $this->assertTrue(is_array($items));
        
        $this->tester->haveInDatabase(
            'modules', 
            [
                'id'        => 10001, 
                'muid'      => 10001, 
                'mname'     => 'Test Module Name', 
                'mfile'     => 'mod_test_module.php', 
                'isenabled' => 1, 
            ]
        );
        $this->tester->haveInDatabase(
            'modules', 
            [
                'id'        => 10002, 
                'muid'      => 10002, 
                'mname'     => 'Another Test Module Name', 
                'mfile'     => 'mod_test_module_2.php', 
                'isenabled' => 1, 
            ]
        );
        $items = [
            ['id' => 10001, 'topid' => 10001, 'parentid' => 0, 'moduleid' => 10001, 'path' => '/test-section-children/', 'indic' => 'Test Section Children', 'isenabled' => 1], 
            ['id' => 10002, 'topid' => 10001, 'parentid' => 10001, 'moduleid' => 10001, 'path' => '/test-section-children/child-1/', 'indic' => 'Test Section Children - 1', 'isenabled' => 1], 
            ['id' => 10003, 'topid' => 10001, 'parentid' => 10001, 'moduleid' => 10001, 'path' => '/test-section-children/child-2/', 'indic' => 'Test Section Children - 2', 'isenabled' => 1], 
            ['id' => 10004, 'topid' => 10001, 'parentid' => 10001, 'moduleid' => 10001, 'path' => '/test-section-children/child-3/', 'indic' => 'Test Section Children - 3', 'isenabled' => 1], 
            ['id' => 10005, 'topid' => 10001, 'parentid' => 10001, 'moduleid' => 10001, 'path' => '/test-section-children/child-4/', 'indic' => 'Test Section Children - 4', 'isenabled' => 1], 
            ['id' => 10006, 'topid' => 10001, 'parentid' => 10002, 'moduleid' => 10002, 'path' => '/test-section-children/child-2/child-1/', 'indic' => 'Test Section Children - 2 - 1', 'isenabled' => 1], 
            ['id' => 10007, 'topid' => 10001, 'parentid' => 10002, 'moduleid' => 10002, 'path' => '/test-section-children/child-2/child-2/', 'indic' => 'Test Section Children - 2 - 2', 'isenabled' => 1], 
        ];
        foreach ($items as $item) {
            $this->tester->haveInDatabase('sections', $item);
        }
        
        $items = $this->core->getSectionChildren(10001);
        $this->assertTrue(is_array($items));
        $this->assertEquals(4, count($items));
        $this->assertTrue(array_key_exists('id10002', $items));
        $this->assertTrue(array_key_exists('path', $items['id10002']));
        $this->assertTrue(array_key_exists('mname', $items['id10002']));
        $this->assertEquals('/test-section-children/child-1/', $items['id10002']['path']);
        $this->assertEquals('Test Module Name', $items['id10002']['mname']);
        
        $items = $this->core->getSectionChildren(10002);
        $this->assertTrue(is_array($items));
        $this->assertEquals(2, count($items));
        $this->assertTrue(array_key_exists('id10006', $items));
        $this->assertTrue(array_key_exists('path', $items['id10006']));
        $this->assertTrue(array_key_exists('mname', $items['id10006']));
        $this->assertEquals('/test-section-children/child-2/child-1/', $items['id10006']['path']);
        $this->assertEquals('Another Test Module Name', $items['id10006']['mname']);
    }
    
    public function testCheckXsrf()
    {
        $_SERVER['REQUEST_URI'] = '';
        
        ob_start();
        $this->core->checkXsrf();
        $out = ob_get_clean();
        $this->assertNotFalse(strpos($out, 'XSRF check fail'));
    }
    
    public function testGetRoles()
    {
        $var = $this->core->getRoles();
        $this->assertTrue(is_object($var));
        $this->assertTrue(is_a($var, '\\Ufocms\\Backend\\Roles'));
    }
    
    public function testCheckUserAccess()
    {
        $this->expectedNoException(
            function() { $this->core->checkUserAccess(-1, -1, 'adminlogin'); }
        );
        $this->expectedNoException(
            function() { $this->core->checkUserAccess(-1, -1, 'adminlogout'); }
        );
        
        $this->exceptionContains(
            function() { $this->core->checkUserAccess(-1, -1, ''); }, 
            '403: User not set'
        );
        
        //TODO: set user
        // $this->exceptionContains(
            // function() { $this->core->checkUserAccess(-1, -1, ''); }, 
            // '403: User access restricted'
        // );
        // $this->exceptionContains(
            // function() { $this->core->checkUserAccess(-1, -1, ''); }, 
            // '403: User do not have required permissions'
        // );
    }
    
    public function testFixUserAction()
    {
        //$this->core->fixUserAction(Model $model, $action);
        //TODO: make Model stub and check invokation of Model::disabled - true/false
    }
}
