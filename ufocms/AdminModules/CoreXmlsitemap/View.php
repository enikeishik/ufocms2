<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\CoreXmlsitemap;

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
        $this->ui = new \Ufocms\AdminModules\UI($container);
        
        //Layout
        require_once 'templates/xmlsitemap.php';
    }
}
