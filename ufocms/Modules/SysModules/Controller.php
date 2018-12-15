<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\SysModules;

/**
 * Module level controller
 */
class Controller extends \Ufocms\Modules\Controller //implements ControllerInterface
{
    /**
     * @see parent
     */
    protected function setModuleParamsStruct()
    {
        parent::setModuleParamsStruct();
        $params = array(
            'module' => ['type' => 'string', 'from' => 'path', 'prefix' => '', 'additional' => false, 'value' => null, 'default' => ''], 
        );
        //reserve params for usage in module
        for ($i = 2; $i < $this->config->pathNestingLimit; $i++) {
            $params['module' . $i] = $params['module'];
        }
        $this->moduleParamsStruct = array_merge($this->moduleParamsStruct, $params);
    }
    
    /**
     * @see parent
     */
    public function dispatch()
    {
        //find which module was asked
        if ('' == $this->moduleParams['module']) {
            $this->core->riseError(403, 'Use endpoint path');
        }
        
        //redefine module classes into endpoint module common classes
        $moduleName = ucfirst($this->moduleParams['module']);
        $this->module['Name']       = $moduleName;
        $this->module['Controller'] = '\\Ufocms\\Modules\\' . $moduleName . '\\CommonController';
        $this->module['Model']      = '\\Ufocms\\Modules\\' . $moduleName . '\\CommonModel';
        $this->module['View']       = '\\Ufocms\\Modules\\' . $moduleName . '\\CommonView';
        
        //remove param with asked module, because in next Controller::init calling setParams will raise error on this param
        array_shift($this->params->sectionParams);
        if (null !== $controller = $this->getController()) {
            $controller->dispatch();
            return;
        }
        
        $model = $this->getModel();
        if (null === $model) {
            $this->core->riseError(404, 'Model not exists'); //exit('404-model'); //throw new Exception
        }
        $this->modelAction($model);
        
        $view = $this->getView($model, $this->getModuleContext($model), $this->getLayout());
        $view->render();
    }
    
    /**
     * @return \Ufocms\Modules\Controller|null
     */
    protected function getController()
    {
        $container = $this->core->getContainer([
            'debug'     => &$this->debug, 
            'config'    => &$this->config, 
            'params'    => &$this->params, 
            'db'        => &$this->db, 
            'core'      => &$this->core, 
            'module'    => &$this->module, 
        ]);
        $class = $this->module['Controller'];
        if (class_exists($class)) {
            return new $class($container);
        } else {
            return null;
        }
    }
}
