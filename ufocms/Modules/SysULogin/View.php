<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\SysULogin;

/**
 * Main module model
 */
class View extends \Ufocms\Modules\View //implements IView
{
    /**
     * @return array
     */
    protected function getModuleContext()
    {
        return array_merge(
            parent::getModuleContext(), 
            array(
                'error' => $this->model->getError(), 
                'from'  => $this->model->getFrom(), 
            )
        );
    }
}
