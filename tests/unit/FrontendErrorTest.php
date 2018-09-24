<?php
require_once __DIR__  . DIRECTORY_SEPARATOR . '_presets.php';

use \Ufocms\Frontend\Config;
use \Ufocms\Frontend\Container;
use \Ufocms\Frontend\Error as UfocmsError;

class FrontendErrorTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    
    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testRise()
    {
        $_SERVER['DOCUMENT_ROOT'] = dirname(dirname(__DIR__));
        $config = new Config();
        $container = new Container(['config' => $config]);
        $error = $this->make(
            new UfocmsError($container), 
            ['rise' => function($errNum) {
                
            }]
        );
        $error->rise(0);
        
        $error = new class($container) extends UfocmsError {
            public function rise($errNum, $errMsg = null, $options = null)
            {
                return $this->getTemplate($errNum);
            }
        };
        $this->assertNull($error->rise(0));
        $this->assertNull($error->rise(200));
        $this->assertNotNull($error->rise(301));
        $this->assertNotNull($error->rise(302));
        $this->assertNotNull($error->rise(401));
        $this->assertNotNull($error->rise(403));
        $this->assertNotNull($error->rise(404));
        $this->assertNotNull($error->rise(500));
    }
}
