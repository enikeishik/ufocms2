<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\SysUsers;

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
                'form' => ['type' => 'string', 'from' => 'get', 'prefix' => 'form', 'additional' => false, 'value' => null, 'default' => ''], 
            )
        );
    }
    
    /**
     * @return array
     */
    protected function getModuleContext(ModelInterface &$model)
    {
        return array_merge(
            parent::getModuleContext($model), 
            array(
                'error' => $model->getError(), 
                'from'  => $model->getFrom(), 
            )
        );
    }
}
