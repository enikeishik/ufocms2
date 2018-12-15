<?php
require_once __DIR__  . DIRECTORY_SEPARATOR . '_presets.php';
require_once 'ModulesViewTrait.php';

use \Ufocms\Frontend\Config;
use \Ufocms\Frontend\Container;
use \Ufocms\Frontend\Core;
use \Ufocms\Frontend\Db;
use \Ufocms\Frontend\Params;
use \Ufocms\Modules\Model;
use \Ufocms\Modules\View;

class ModulesAbstractViewTest extends \Codeception\Test\Unit
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
    
    /**
     * @var View
     */
    protected $view;
    
    protected function _before()
    {
        $this->config = new Config();
        $this->config->rootPath = dirname(dirname(__DIR__));
        $this->params = new Params();
    }

    protected function _after()
    {
        if (null !== $this->db) {
            $this->db->close();
            $this->db = null;
        }
    }
    
    /**
     * Must be redefined in child class to work with its own model.
     * @param Container $container
     * @return Model
     */
    protected function getModel($container)
    {
        return new class($container) extends Model {};
    }
    
    /**
     * Gets container.
     * @return Container
     */
    protected function getContainerForModel(array $params = [])
    {
        $this->db = new Db();
        $this->core = new class($this->config, $this->params, $this->db) extends Core {
            public function riseError($errNum, $errMsg = null, $options = null)
            {
                throw new \Exception($errNum . ': ' . $errMsg);
            }
        };
        return new Container(array_merge(
            [
                'config'    => &$this->config, 
                'params'    => &$this->params,
                'db'        => &$this->db, 
                'core'      => &$this->core, 
            ], 
            $params
        ));
    }
    
    /**
     * Gets container with initialized model in it.
     * @param array $params = []
     * @return Container
     */
    protected function getContainer(array $params = [])
    {
        $model = $this->getModel($this->getContainerForModel($params));
        return new Container(array_merge(
            [
                'config'    => &$this->config, 
                'params'    => &$this->params,
                'db'        => &$this->db, 
                'core'      => &$this->core, 
                'model'     => &$model, 
                'context'   => $this->getModuleContext(), 
                'layout'    => $this->getLayout(), 
            ], 
            $params
        ));
    }
    
    protected function getModuleContext()
    {
        return [
            'item' => null, 
            'items' => [], 
            'itemsCount' => 0, 
        ];
    }
    
    protected function getLayout()
    {
        return $this->config->templatesEntry;
    }
    
    protected function getTemplateContent()
    {
$content = <<<'EOD'
test view template begin
$item <?php var_dump($item); ?>
$items <?php var_dump($items); ?>
test view template end
EOD;
        return $content;
    }
    
    /**
     * Must be redefined in child class to work with its own view.
     * @param Container $container
     * @return View
     */
    protected function getView($container)
    {
        $view = new class($container) extends View {
            use ModulesViewTrait;
        };
        $view->setTestContent($this->getTemplateContent());
        return $view;
    }
    
    protected function getRenderContent()
    {
        $view = $this->getView($this->getContainer());
        ob_start();
        $view->render();
        return ob_get_clean();
    }
    
    // tests
    public function testSetTheme()
    {
        $container = $this->getContainer();
        $view = new class($container) extends View {
            public function getTemplateUrl()
            {
                return $this->templateUrl;
            }
        };
        
        $theme = $view->getTemplateUrl();
        $this->assertEquals($this->config->templatesDir . $this->config->themeDefault, $theme);
        
        $view->setTheme('test-theme');
        $theme = $view->getTemplateUrl();
        $this->assertTrue(($this->config->templatesDir . '/test-theme') == $theme);
    }
    
    public function testRender()
    {
        //remove travis-ci VM id from var_dump
        $content = preg_replace('/ \/tmp\/tmp.+\n/', ' ', $this->getRenderContent());
        $this->assertTrue(false !== strpos($content, 'test view template begin'));
        $this->assertTrue(false !== strpos($content, '$item NULL'));
        $this->assertTrue(false !== strpos($content, '$items array(0)'));
        $this->assertTrue(false !== strpos($content, 'test view template end'));
    }
}
