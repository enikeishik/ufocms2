<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\News;

/**
 * Module level common (for all sections) controller
 */
class CommonController extends Controller //implements IController
{
    /**
     * @see parent
     */
    protected function setModuleParamsStruct()
    {
        parent::setModuleParamsStruct();
        $this->moduleParamsStruct = array_merge($this->moduleParamsStruct, 
            array(
                'authors'   => ['type' => 'string', 'from' => 'path',   'prefix' => 'authors',  'additional' => false,  'value' => null, 'default' => ''], 
            )
        );
    }
}
