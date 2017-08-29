<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\News2;

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
                'tagId'     => ['type' => 'int',    'from' => 'path',   'prefix' => 'tag',      'additional' => false,  'value' => null, 'default' => 0], 
                'isYandex'  => ['type' => 'bool',   'from' => 'path',   'prefix' => 'yandex',   'additional' => false,  'value' => null, 'default' => false], 
                'isYaDzen'  => ['type' => 'bool',   'from' => 'path',   'prefix' => 'yadzen',   'additional' => false,  'value' => null, 'default' => false], 
                'pageSize'  => ['type' => 'int',    'from' => 'path',   'prefix' => 'psize',    'additional' => true,   'value' => null, 'default' => $this->config->pageSizeDefault], 
            )
        );
    }
    
    protected function modelAction(&$model)
    {
        parent::modelAction($model);
        if (2 == $this->params->actionId) {
            $model->add();
        }
    }
}
