<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\Tales;

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
                'alias'    => ['type' => 'string', 'from' => 'path',   'prefix' => '',  'additional' => false,  'value' => null, 'default' => ''], 
            )
        );
    }
    
    /**
     * @see parent
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
