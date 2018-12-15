<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\News;

use \Ufocms\Modules\ModelInterface;

/**
 * Module level controller
 */
class Controller extends \Ufocms\Modules\Controller //implements ControllerInterface
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
                'isYaTurbo' => ['type' => 'bool',   'from' => 'path',   'prefix' => 'yaturbo',  'additional' => false,  'value' => null, 'default' => false], 
                'isRambler' => ['type' => 'bool',   'from' => 'path',   'prefix' => 'rambler',  'additional' => false,  'value' => null, 'default' => false], 
                'isAMP'     => ['type' => 'bool',   'from' => 'path',   'prefix' => 'amp',      'additional' => true,   'value' => null, 'default' => false], 
                'author'    => ['type' => 'string', 'from' => 'get',    'prefix' => 'author',   'additional' => false,  'value' => null, 'default' => ''], 
            )
        );
    }
    
    /**
     * @param ModelInterface &$model
     * @return array
     */
    protected function getModuleContext(ModelInterface &$model)
    {
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
        } else if (null !== $this->moduleParams['date']) {
            return array(
                'settings'      => $model->getSettings(), 
                'item'          => null, 
                'items'         => $model->getItemsByDate(), 
                'itemsCount'    => $model->getItemsCount(), 
            );
        } else if (null !== $this->moduleParams['author']) {
            return array(
                'settings'      => $model->getSettings(), 
                'item'          => null, 
                'items'         => $model->getItemsByAuthor(), 
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
        } else if ($this->moduleParams['isAMP']) {
            return 'itemamp.php';
        } else {
            return parent::getLayout();
        }
    }
}
