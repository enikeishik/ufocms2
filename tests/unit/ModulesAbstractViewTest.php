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
     * @var View
     */
    protected $view;
    
    protected function _before()
    {
        $this->config = new Config();
        $this->params = new Params();
    }

    protected function _after()
    {
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
     * Gets container with initialized model in it.
     * @return Container
     */
    protected function getContainer(array $params = [])
    {
        $db = new Db();
        $core = new class($this->config, $this->params, $db) extends Core {
            public function riseError($errNum, $errMsg = null, $options = null)
            {
                throw new \Exception($errNum . ': ' . $errMsg);
            }
        };
        $container = new Container(array_merge(
            [
                'config'    => &$this->config, 
                'params'    => &$this->params,
                'db'        => &$db, 
                'core'      => &$core, 
            ], 
            $params
        ));
        $model = $this->getModel($container);
        return new Container(array_merge(
            [
                'config'    => &$this->config, 
                'params'    => &$this->params,
                'db'        => &$db, 
                'core'      => &$core, 
                'model'     => &$model,
            ], 
            $params
        ));
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
        $container = new Container([
            'config' => &$this->config, 
        ]);
        $view = new class($container) extends View {
            public function getTemplateUrl()
            {
                return $this->templateUrl;
            }
        };
        
        $theme = $view->getTemplateUrl();
        $this->assertNull($theme);
        
        $view->setTheme();
        $theme = $view->getTemplateUrl();
        $this->assertTrue(($this->config->templatesDir . $this->config->themeDefault) == $theme);
        
        $view->setTheme('test-theme');
        $theme = $view->getTemplateUrl();
        $this->assertTrue(($this->config->templatesDir . '/test-theme') == $theme);
    }
    
    public function testRender()
    {
        $content = $this->getRenderContent();
        $this->assertTrue(false !== strpos($content, 'test view template begin'));
        $this->assertTrue(false !== strpos($content, '$item NULL'));
        $this->assertTrue(false !== strpos($content, '$items array(0)'));
        $this->assertTrue(false !== strpos($content, 'test view template end'));
    }
}
