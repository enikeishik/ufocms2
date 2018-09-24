<?php
require_once __DIR__  . DIRECTORY_SEPARATOR . '_presets.php';
require_once 'ModulesWidgetTrait.php';

use \Ufocms\Frontend\Config;
use \Ufocms\Frontend\Container;
use \Ufocms\Frontend\Core;
use \Ufocms\Frontend\Db;
use \Ufocms\Frontend\Params;
use \Ufocms\Modules\Model;
use \Ufocms\Modules\Widget;

class ModulesAbstractWidgetTest extends \Codeception\Test\Unit
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
    protected $data;
    
    /**
     * @var View
     */
    protected $view;
    
    protected function _before()
    {
        $this->config = new Config();
        $this->params = new Params();
        $this->data = $this->getData();
    }

    protected function _after()
    {
    }
    
    protected function getData($srcSections = null, array $params = null)
    {
        return [
            'SrcSections'   => $srcSections ?? '', 
            'SrcItems'      => '', 
            'ShowTitle'     => false, 
            'Title'         => '', 
            'Content'       => '', 
            'Params'        => json_encode($params ?? []), 
            'ModuleId'      => 0, 
            'madmin'        => 'mod_', 
            'Name'          => '', 
        ];
    }
    
    protected function getContainer()
    {
        $db = new Db();
        $core = new Core($this->config, $this->params, $db);
        return new Container([
            'config'        => &$this->config, 
            'params'        => &$this->params,
            'db'            => &$db, 
            'core'          => &$core, 
            'data'          => &$this->data, 
            'templateUrl'   => '', 
        ]);
    }
    
    protected function getTemplateContent()
    {
$content = <<<'EOD'
test widget template begin
$showTitle <?php var_dump($showTitle); ?>
$title <?php var_dump($title); ?>
$content <?php var_dump($content); ?>
$items <?php if (isset($items)) { var_dump($items); } else { echo PHP_EOL; } ?>
test widget template end
EOD;
        return $content;
    }
    
    /**
     * Must be redefined in child class to work with its own widget.
     * @param Container $container
     * @return Widget
     */
    protected function getWidget($container)
    {
        $widget = new class($container) extends Widget {
            use ModulesWidgetTrait;
        };
        $widget->setTestContent($this->getTemplateContent());
        return $widget;
    }
    
    protected function getRenderContent()
    {
        $widget = $this->getWidget($this->getContainer());
        ob_start();
        $widget->render();
        return ob_get_clean();
    }
    
    // tests
    public function testRender()
    {
        $content = $this->getRenderContent();
        $this->assertTrue(false !== strpos($content, 'test widget template begin'));
        $this->assertTrue(false !== strpos($content, '$showTitle bool(false)'));
        $this->assertTrue(false !== strpos($content, '$title string(0) ""'));
        $this->assertTrue(false !== strpos($content, '$content string(0) ""'));
        $this->assertTrue(false !== strpos($content, '$items'));
        $this->assertTrue(false !== strpos($content, 'test widget template end'));
    }
}
