<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\Auctions;

use \Ufocms\Modules\ModelInterface;

/**
 * Module level controller
 */
class Controller extends \Ufocms\Modules\Controller //implements ControllerInterface
{
    protected function setModuleParamsStruct()
    {
        parent::setModuleParamsStruct();
        $this->moduleParamsStruct = array_merge($this->moduleParamsStruct, 
            array(
                'requestType' => ['type' => 'string', 'from' => 'get',    'prefix' => 'type',    'additional' => false,  'value' => null, 'default' => 'xhr'], 
            )
        );
    }
    
    protected function getModuleContext(ModelInterface &$model)
    {
        if ('login' == $this->moduleParams['requestType']) {
            return array(
                'settings'      => null, 
                'item'          => null, 
                'items'         => null, 
            );
        }
        return array_merge(
            parent::getModuleContext($model), 
            array('currentUser' => $this->core->getUsers()->getCurrent())
        );
    }
    
    protected function getLayout()
    {
        switch ($this->moduleParams['requestType']) {
            case 'xhr':
                return 'json.php';
            case 'iframe':
                return 'iframe.php';
            default:
                return parent::getLayout();
        }
    }
}
