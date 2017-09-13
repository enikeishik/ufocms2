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
        $this->layout = 'templates/xmlsitemap.php';
        $this->ui = $this->getUI('', '?' . (is_null($this->params->coreModule) ? '' : $this->config->paramsNames['coreModule'] .  '=' . $this->params->coreModule));
        require_once $this->layout;
    }
}
