<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\Auctions;

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
                'requestType' => ['type' => 'string', 'from' => 'get',    'prefix' => 'type',    'additional' => false,  'value' => null, 'default' => 'xhr'], 
            )
        );
    }
}
