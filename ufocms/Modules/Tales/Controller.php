<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\Tales;

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
                'alias'    => ['type' => 'string', 'from' => 'path',   'prefix' => '',  'additional' => false,  'value' => null, 'default' => ''], 
            )
        );
    }
}
