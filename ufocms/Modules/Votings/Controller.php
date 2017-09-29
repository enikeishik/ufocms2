<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\Votings;

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
                'results'  => ['type' => 'bool',   'from' => 'path',   'prefix' => 'results',   'additional' => true,  'value' => null, 'default' => false], 
            )
        );
    }
}
