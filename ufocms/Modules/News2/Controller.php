<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\News2;

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
                'tagId'     => ['type' => 'int',    'from' => 'path',   'prefix' => 'tag',      'additional' => false,  'value' => null, 'default' => 0], 
                'isYandex'  => ['type' => 'bool',   'from' => 'path',   'prefix' => 'yandex',   'additional' => false,  'value' => null, 'default' => false], 
                'isYaDzen'  => ['type' => 'bool',   'from' => 'path',   'prefix' => 'yadzen',   'additional' => false,  'value' => null, 'default' => false], 
                'isYaTurbo' => ['type' => 'bool',   'from' => 'path',   'prefix' => 'yaturbo',  'additional' => false,  'value' => null, 'default' => false], 
                'isRambler' => ['type' => 'bool',   'from' => 'path',   'prefix' => 'rambler',  'additional' => false,  'value' => null, 'default' => false], 
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
    
    /**
     * @return array
     */
    protected function getModuleContext(ModelInterface &$model)
    {
        if (null !== $this->params->actionId) {
            return array(
                'settings'      => null, 
                'item'          => null, 
                'items'         => null, 
                'itemsCount'    => null, 
                'actionResult'  => $model->getActionResult(), 
            );
        }
        if (0 != $this->params->itemId) {
            $item = $model->getItem();
            if (null === $item) {
                $this->core->riseError(404, 'Item not exists');
            }
            return array(
                'settings'      => $model->getSettings(), 
                'item'          => $item, 
                'items'         => null, 
                'itemsCount'    => $model->getItemsCount(), 
            );
        } else if (null !== $this->moduleParams['tagId']) {
            return array(
                'settings'      => $model->getSettings(), 
                'item'          => null, 
                'items'         => $model->getItemsByTag(), 
                'itemsCount'    => $model->getItemsCount(), 
            );
        } else if (null !== $this->moduleParams['date']) {
            return array(
                'settings'      => $model->getSettings(), 
                'item'          => null, 
                'items'         => $model->getItemsByDate(), 
                'itemsCount'    => $model->getItemsCount(), 
            );
        } else {
            return array(
                'settings'      => $model->getSettings(), 
                'item'          => null, 
                'items'         => $model->getItems(), 
                'itemsCount'    => $model->getItemsCount(), 
            );
        }
    }
    
    /**
     * @see parent
     */
    protected function getLayout()
    {
        if ($this->moduleParams['isYandex']) {
            return 'yandex.php';
        } else if ($this->moduleParams['isYaDzen']) {
            return 'yadzen.php';
        } else if ($this->moduleParams['isYaTurbo']) {
            return 'yaturbo.php';
        } else if ($this->moduleParams['isRambler']) {
            return 'rambler.php';
        } else {
            return parent::getLayout();
        }
    }
}
