<?php
require_once __DIR__  . DIRECTORY_SEPARATOR . '_presets.php';
require_once 'ModulesAbstractControllerTest.php';
require_once 'ModulesControllerTrait.php';

use \Ufocms\Modules\News\Controller;

class ModulesNewsControllerTest extends ModulesAbstractControllerTest
{
    protected function getController($container)
    {
        return new class($container) extends Controller {
            use ModulesControllerTrait;
        };
    }
    
    // tests
    public function testDispatch()
    {
        parent::testDispatch();
        
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
        $this->module['Model'] = 'stdClass';
        $this->expectedException('render');
        
        $this->params->sectionParams = ['amp', 'amp'];
        $this->module['Model'] = 'stdClass';
        $this->expectedException('404: Module parameter unknown');
        
        $this->params->sectionParams = ['yandex', '123'];
        $this->module['Model'] = 'stdClass';
        $this->expectedException('404: Module parameter unknown');
    }
}
