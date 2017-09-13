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
        //Layout
        if (is_null($layout)) {
            if (in_array($this->params->action, $this->config->actionsForm)) {
                $this->layout = 'templates/form.php';
            } else {
                $this->layout = 'templates/single.php';
            }
        } else {
            if (false === strpos($layout, '.php')) {
                $layoutPath = 'templates/' . $layout . '.php';
            }
            if (file_exists($layoutPath)) {
                $this->layout = $layoutPath;
            } else {
                $this->layout = 'templates/empty.php';
            }
        }
        
        //UI
        $this->ui = $this->getUI('', '?' . (is_null($this->params->coreModule) ? '' : $this->config->paramsNames['coreModule'] .  '=' . $this->params->coreModule));
        
        require_once $this->layout;
    }
}
