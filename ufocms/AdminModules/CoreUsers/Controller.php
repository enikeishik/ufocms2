<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\CoreUsers;

/**
 * Module level controller
 */
class Controller extends \Ufocms\AdminModules\Controller //implements IController
{
    public function dispatch()
    {
        if ('groups' == $this->params->subModule) {
            $this->setModel('ModelGroups');
            $this->modelAction();
            $this->setView();
            $this->renderView(
                '', 
                'UIGroups', 
                '?' . (is_null($this->params->coreModule) ? '' : $this->config->paramsNames['coreModule'] .  '=' . $this->params->coreModule . '&' . $this->config->paramsNames['subModule'] . '=groups')
            );
        } else if ('settings' == $this->params->subModule) {
            $this->setModel('ModelSettings');
            $this->modelAction();
            $this->setView();
            $this->renderView(
                'form', 
                'UISettings', 
                '?' . (is_null($this->params->coreModule) ? '' : $this->config->paramsNames['coreModule'] .  '=' . $this->params->coreModule . '&' . $this->config->paramsNames['subModule'] . '=settings')
            );
        } else {
            $this->setModel();
            $this->modelAction();
            $this->setView();
            $this->renderView();
        }
    }
}
