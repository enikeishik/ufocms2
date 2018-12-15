<?php
require_once __DIR__  . DIRECTORY_SEPARATOR . '_presets.php';
require_once 'ModulesControllerTrait.php';

use \Ufocms\Frontend\Config;
use \Ufocms\Frontend\Container;
use \Ufocms\Frontend\Core;
use \Ufocms\Frontend\Db;
use \Ufocms\Frontend\Params;
use \Ufocms\Modules\Controller;

class ModulesAbstractControllerTest extends \Codeception\Test\Unit
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
     * @var array
     */
    protected $module;
    
    /**
     * @var Db
     */
    protected $db;
    
    /**
     * @var object
     */
    protected $core;
    
    protected function _before()
    {
        $this->config = new Config();
        $this->params = new Params();
        $this->db = new Db();
        $this->core = $this->getCore();
        $this->module = $this->getModuleClasses();
    }

    protected function _after()
    {
        if (null !== $this->db) {
            $this->db->close();
            $this->db = null;
        }
    }
    
    /**
     * @return array|null
     */
    protected function getModuleClasses()
    {
        return null;
    }
    
    /**
     * @return Core child
     */
    protected function getCore()
    {
        return new class($this->config, $this->params, $this->db) extends Core {
            public function riseError($errNum, $errMsg = null, $options = null)
            {
                throw new \Exception($errNum . ': ' . $errMsg);
            }
            public function getComments()
            {
                throw new \Exception('getComments');
            }
            public function getInteractionManage()
            {
                throw new \Exception('getInteractionManage');
            }
        };
    }
    
    /**
     * @param array $params = []
     * @return Container
     */
    protected function getContainer(array $params = [])
    {
        return new Container(array_merge(
            [
                'config'    => &$this->config, 
                'params'    => &$this->params, 
                'db'        => &$this->db, 
                'core'      => &$this->core, 
                'module'    => &$this->module, 
                'tools'     => null, 
            ], 
            $params
        ));
    }
    
    /**
     * Must be redefined in child class to work with its own controller.
     * @param Container $container
     * @return Controller
     */
    protected function getController($container)
    {
        return new class($container) extends Controller {
            use ModulesControllerTrait;
        };
    }
    
    /**
     * @param string $expectedExceptionMessage
     * @param callable $call = null
     */
    protected function expectedException(string $expectedExceptionMessage, callable $call = null)
    {
        try {
            if (null === $call) {
                $controller = $this->getController($this->getContainer());
                $controller->dispatch();
            } else {
                $call();
            }
        } catch (\Exception $e) {
            $this->assertEquals($expectedExceptionMessage, $e->getMessage());
        }
    }
    
    /**
     * @param array<[string name, string from, string prefix, mixed value]> $params
     * @param string $expectedExceptionMessage = 'render'
     */
    protected function testModuleParams(array $params, string $expectedExceptionMessage = 'render')
    {
        //prepare
        if (!is_array($params[0])) {
            $params = [$params];
        }
        $this->module['Model'] = $this->module['Model'] ?? 'stdClass';
        
        //put params into Main generated sectionParams or $_GET
        $this->params->sectionParams = [];
        $_GET = [];
        foreach ($params as $param) {
            list($name, $from, $prefix, $value) = $param;
            if ('path' == $from) {
                $this->params->sectionParams[] = $prefix;
            } else {
                $_GET[$prefix] = $value;
            }
        }
        
        //check that controller get all params correctly
        $controller = $this->getController($this->getContainer());
        $paramsSet = $controller->getModuleParamsStruct();
        foreach ($params as $param) {
            list($name, $from, $prefix, $value) = $param;
            $this->assertEquals($value, $paramsSet[$name]['value']);
        }
        
        //check controller dispatch
        $this->expectedException(
            $expectedExceptionMessage, 
            function() use ($controller) { $controller->dispatch(); }
        );
    }
    
    // tests
    public function testDispatch()
    {
        $this->expectedException('404: Model not exists');
        
        $this->params->sectionParams = ['123'];
        $this->expectedException('404: Model not exists');
        
        $this->params->sectionParams = ['123'];
        $this->module['Model'] = 'stdClass';
        $this->expectedException('404: Item not exists');
        
        $this->params->sectionParams = ['2018-01-01'];
        $this->module['Model'] = 'stdClass';
        $this->expectedException('render');
        
        $this->params->sectionParams = ['unknown'];
        $this->module['Model'] = 'stdClass';
        $this->expectedException('404: Module parameter unknown');
        
        $this->params->sectionParams = ['123', '456'];
        $this->module['Model'] = 'stdClass';
        $this->expectedException('404: Module parameter unknown');
        
        $this->params->sectionParams = ['-123'];
        $this->module['Model'] = 'stdClass';
        $this->expectedException('404: Module parameter unknown');
        
        $this->params->sectionParams = ['page2', 'page3'];
        $this->module['Model'] = 'stdClass';
        $this->expectedException('404: Module parameter unknown');
        
        $this->params->sectionParams = ['2018-21-01'];
        $this->module['Model'] = 'stdClass';
        $this->expectedException('404: Module parameter unknown');
        
        $this->params->sectionParams = ['2018-01-32'];
        $this->module['Model'] = 'stdClass';
        $this->expectedException('404: Module parameter unknown');
        
        $this->testModuleParams(['isRoot', '', '', true]);
        $this->testModuleParams(['isRoot', 'path', '123', false], '404: Item not exists');
        $this->testModuleParams(['isRss', 'path', 'rss', true]);
        $this->testModuleParams(['date', 'path', '2018-01-01', '2018-01-01']);
        $this->testModuleParams(['date', 'path', 'dt2018-01-01', '2018-01-01']);
        $this->testModuleParams(['itemId', 'path', '123', 123], '404: Item not exists');
        $this->testModuleParams(['page', 'path', 'page5', 5]);
        $this->testModuleParams(['pageSize', 'path', 'psize25', 25]);
        $this->testModuleParams(['actionId', 'path', 'action2', 2]);
        $this->testModuleParams(['commentsPage', 'path', 'comments5', 5]);
        $this->testModuleParams(['itemId', 'path', '123123123123123123123123', PHP_INT_MAX], '404: Item not exists');
        
        $this->testModuleParams([
            ['itemId', 'path', '123', 123], 
            ['commentsPage', 'path', 'comments5', 5]
        ], '404: Item not exists');
        
        $this->testModuleParams(['action', 'get', 'action', 'someaction'], 'modelAction: someaction');
        
        $this->testModuleParams(['commentsAdd', 'get', 'commentsadd', 5], 'getComments');
        
        $this->testModuleParams(['interaction', 'get', 'interaction', 1], 'getInteractionManage');
    }
}
