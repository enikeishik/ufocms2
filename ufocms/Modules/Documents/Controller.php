<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\Documents;

use \Ufocms\Modules\ModelInterface;

/**
 * Main module controller
 */
class Controller extends \Ufocms\Modules\Controller
{
    /**
     * @param ModelInterface &$model
     * @return array
     */
    protected function getModuleContext(ModelInterface &$model)
    {
        return array(
            'item'      => $model->getItem(), 
            'items'     => null, 
        );
    }
}
