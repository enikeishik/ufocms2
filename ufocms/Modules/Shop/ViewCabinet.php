<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\Shop;

/**
 * Main module model
 */
class ViewCabinet extends \Ufocms\Modules\View //implements IView
{
    /**
     * @return array
     */
    protected function getModuleContext()
    {
        switch ($this->moduleParams['cabinet']) {
            case 'show':
                return array(
                    'settings'      => null, 
                    'item'          => null, 
                    'items'         => $this->model->getItems(), 
                );
                
            case 'login':
                return array(
                    'settings'      => null, 
                    'item'          => null, 
                    'items'         => null, 
                );
                
            default:
                $this->core->riseError(404, 'Unknown value of `cabinet` parameter');
        }
    }
    
    protected function getModuleTemplateEntry()
    {
        if ('login' == $this->moduleParams['cabinet']) {
            return '/login.php';
        }
        return '/cabinet.php';
    }
}
