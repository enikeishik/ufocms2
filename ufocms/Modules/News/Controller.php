<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\News;

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
                'isYandex'  => ['type' => 'bool',   'from' => 'path',   'prefix' => 'yandex',   'additional' => false,  'value' => null, 'default' => false], 
                'isYaDzen'  => ['type' => 'bool',   'from' => 'path',   'prefix' => 'yadzen',   'additional' => false,  'value' => null, 'default' => false], 
                'isYaTurbo' => ['type' => 'bool',   'from' => 'path',   'prefix' => 'yaturbo',  'additional' => false,  'value' => null, 'default' => false], 
                'isRambler' => ['type' => 'bool',   'from' => 'path',   'prefix' => 'rambler',  'additional' => false,  'value' => null, 'default' => false], 
                'author'    => ['type' => 'string', 'from' => 'get',    'prefix' => 'author',   'additional' => false,  'value' => null, 'default' => ''], 
            )
        );
    }
}
