<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\Shop;

use \Ufocms\Modules\ModelInterface;

/**
 * Cabinet controller
 */
class ControllerCabinet extends \Ufocms\Modules\Controller //implements ControllerInterface
{
    /**
     * @return array
     */
    protected function getModuleContext(ModelInterface &$model)
    {
        switch ($this->moduleParams['cabinet']) {
            case 'show':
                return array(
                    'settings'      => null, 
                    'item'          => null, 
                    'items'         => $model->getItems(), 
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
