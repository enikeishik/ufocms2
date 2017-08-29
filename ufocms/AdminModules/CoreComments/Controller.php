<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\CoreComments;

/**
 * Module level controller
 */
class Controller extends \Ufocms\AdminModules\Controller //implements IController
{
    public function dispatch()
    {
        if ('blacklist' == $this->params->subModule) {
            $this->setModel('ModelBlacklist');
            $this->modelAction();
            $this->setView();
            $this->renderView('list', 'UIBlacklist', '?' . $this->config->paramsNames['coreModule'] . '=comments&' . $this->config->paramsNames['subModule'] . '=blacklist');
        } else {
            $this->setModel();
            $this->modelAction();
            $this->setView();
            $this->renderView();
        }
    }
}
