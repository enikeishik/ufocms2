<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\CoreQuotes;

/**
 * Module level controller
 */
class Controller extends \Ufocms\AdminModules\Controller //implements IController
{
    public function dispatch()
    {
        if (!is_null($this->params->subModule)) {
            
            if ('groups' == $this->params->subModule) {
                
                $this->setModel('ModelGroups');
                $this->modelAction();
                $this->setView();
                $this->renderView('', '', '?' . $this->config->paramsNames['coreModule'] .  '=' . $this->params->coreModule . '&' . $this->config->paramsNames['subModule'] . '=groups');
                
            }
            
        } else {
            
            $this->setModel();
            $this->modelAction();
            $this->setView();
            $this->renderView();
            
        }
    }
}
