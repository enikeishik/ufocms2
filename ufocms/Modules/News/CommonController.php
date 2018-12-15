<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\News;

use \Ufocms\Modules\ModelInterface;

/**
 * Module level common (for all sections) controller
 */
class CommonController extends Controller //implements ControllerInterface
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
    
    /**
     * @see parent
     */
    protected function getModuleContext(ModelInterface &$model)
    {
        if (0 != $this->params->itemId) {
             //not used, riseError 301 in init()
            return array(
                'settings'      => $model->getSettings(), 
                'item'          => $model->getItem(), 
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
        } else if (null !== $this->moduleParams['authors']) {
            return array(
                'settings'      => $model->getSettings(), 
                'item'          => null, 
                'items'         => $model->getAuthors(), 
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
}
