<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\SysUsers;

/**
 * Module level controller
 */
class Controller extends \Ufocms\Modules\Controller //implements IController
{
    protected function setModuleParamsStruct()
    {
        parent::setModuleParamsStruct();
        $this->moduleParamsStruct = array_merge($this->moduleParamsStruct, 
            array(
                'from' => ['type' => 'string', 'from' => 'get', 'prefix' => 'from', 'additional' => false, 'value' => null, 'default' => ''], 
            )
        );
    }
}
