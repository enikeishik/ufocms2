<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\CoreSendform;

/**
 * View class
 */
class View extends \Ufocms\AdminModules\View
{
    /**
     * @see parent
     */
    public function render($layout = null, $ui = null, $uiParams = null, $uiParamsAppend = false)
    {
        //UI
        $container = new \Ufocms\Frontend\Container([
            'debug'     => &$this->debug, 
            'config'    => &$this->config, 
            'params'    => &$this->params, 
            'core'      => &$this->core, 
            'tools'     => &$this->tools, 
            'model'     => &$this->model, 
            'basePath'  => '?' . (is_null($this->params->coreModule) ? '' : $this->config->paramsNames['coreModule'] .  '=' . $this->params->coreModule), 
        ]);
        $this->ui = new \Ufocms\AdminModules\CoreSendform\UI($container);
        
        //Layout
        if (is_null($layout)) {
            if (in_array($this->params->action, $this->config->actionsForm)) {
                require_once 'templates/form.php';
            } else {
                require_once 'templates/single.php';
            }
        } else {
            if (false === strpos($layout, '.php')) {
                $layoutPath = 'templates/' . $layout . '.php';
            }
            if (file_exists($layoutPath)) {
                require_once $layoutPath;
            } else {
                require_once 'templates/empty.php';
            }
        }
    }
}
