<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\News;

/**
 * Module level common (for all sections) controller
 */
class CommonController extends \Ufocms\Modules\Controller //implements IController
{
    /**
     * @see parent
     */
    protected function setModuleParamsStruct()
    {
        parent::setModuleParamsStruct();
        $this->moduleParamsStruct = array_merge($this->moduleParamsStruct, 
            array(
                'isYandex'  => ['type' => 'bool',   'from' => 'path',   'prefix' => 'yandex',   'additional' => false,  'value' => null, 'default' => false], 
                'isYaDzen'  => ['type' => 'bool',   'from' => 'path',   'prefix' => 'yadzen',   'additional' => false,  'value' => null, 'default' => false], 
                'isRambler' => ['type' => 'bool',   'from' => 'path',   'prefix' => 'rambler',  'additional' => false,  'value' => null, 'default' => false], 
                'authors'   => ['type' => 'string', 'from' => 'path',   'prefix' => 'authors',  'additional' => false,  'value' => null, 'default' => ''], 
                'author'    => ['type' => 'string', 'from' => 'get',    'prefix' => 'author',   'additional' => false,  'value' => null, 'default' => ''], 
            )
        );
    }
}
