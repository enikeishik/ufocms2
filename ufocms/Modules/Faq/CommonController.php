<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\Faq;

use \Ufocms\Modules\ModelInterface;

/**
 * Main module controller
 */
class CommonController extends \Ufocms\Modules\Controller //implements ControllerInterface
{
    /**
     * @see parent
     */
    protected function getModuleContext(ModelInterface &$model)
    {
        if (0 != $this->params->itemId) {
            //not used, riseError 301 in init()
            return array(
                'settings'      => null, 
                'item'          => $model->getItem(), 
                'items'         => null, 
                'itemsCount'    => $model->getItemsCount(), 
            );
        } else if (null !== $this->moduleParams['date']) {
            return array(
                'settings'      => null, 
                'item'          => null, 
                'items'         => $model->getItemsByDate(), 
                'itemsCount'    => $model->getItemsCount(), 
            );
        } else {
            return array(
                'settings'      => null, 
                'item'          => null, 
                'items'         => $model->getItems(), 
                'itemsCount'    => $model->getItemsCount(), 
            );
        }
    }
}
