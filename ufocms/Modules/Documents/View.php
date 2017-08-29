<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\Documents;

/**
 * Main module view
 */
class View extends \Ufocms\Modules\View
{
    /**
     * @return array
     */
    protected function getModuleContext()
    {
        return array(
            'item'      => $this->model->getItem(), 
            'items'     => null, 
        );
    }
}
