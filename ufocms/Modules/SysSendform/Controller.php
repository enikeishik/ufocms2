<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\SysSendform;

use \Ufocms\Modules\ModelInterface;

/**
 * Main module controller
 */
class Controller extends \Ufocms\Modules\Controller //implements ControllerInterface
{
    protected function getModuleContext(ModelInterface &$model)
    {
        return array(
            'settings'      => $model->getSettings(), 
            'item'          => null, 
            'items'         => $model->getItems(), 
            'actionResult'  => $model->getActionResult(), 
        );
    }
}
