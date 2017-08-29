<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\Auctions;

/**
 * Main module model
 */
class View extends \Ufocms\Modules\View //implements IView
{
    protected function getModuleContext()
    {
        if ('login' == $this->moduleParams['requestType']) {
            return array(
                'settings'      => null, 
                'item'          => null, 
                'items'         => null, 
            );
        }
        return array_merge(
            parent::getModuleContext(), 
            array('currentUser' => $this->core->getUsers()->getCurrent())
        );
    }
    
    protected function getLayout()
    {
        switch ($this->moduleParams['requestType']) {
            case 'xhr':
                return $this->findTemplate($this->templatePath, $this->module['Name'], '/json.php');
            case 'iframe':
                return $this->findTemplate($this->templatePath, $this->module['Name'], '/iframe.php');
            default:
                return parent::getLayout();
        }
    }
    
    protected function getModuleTemplateEntry()
    {
        if ('login' == $this->moduleParams['requestType']) {
            return '/login.php';
        }
        if ((null !== $this->params->actionId || null !== $this->params->action) 
            && 'xhr' == $this->moduleParams['requestType']
        ) {
            return '/resultjson.php';
        }
        return parent::getModuleTemplateEntry();
    }
}
