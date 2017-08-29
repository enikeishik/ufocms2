<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\Board;

/**
 * Module level controller
 */
class Controller extends \Ufocms\AdminModules\Controller //implements IController
{
    public function dispatch()
    {
        if (!is_null($this->params->subModule)) {
            
            if ('settings' == $this->params->subModule) {
                
                $this->setModel('ModelSettings');
                $this->modelAction();
                $this->setView();
                $this->renderView(
                    'form', 
                    '', 
                    '?' . $this->config->paramsNames['sectionId'] . '=' . $this->params->sectionId . '&' . $this->config->paramsNames['subModule'] . '=settings'
                );
                
            }
            
        } else {
            
            $this->setModel();
            $this->modelAction();
            $this->setView();
            $this->renderView();
            
        }
    }
}
