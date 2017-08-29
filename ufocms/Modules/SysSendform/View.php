<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\SysSendform;

/**
 * Main module model
 */
class View extends \Ufocms\Modules\View //implements IView
{
    protected function getModuleContext()
    {
        return array(
            'settings'      => $this->model->getSettings(), 
            'item'          => null, 
            'items'         => $this->model->getItems(), 
            'actionResult'  => $this->model->getActionResult(), 
        );
    }
}
