<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\SysUsers;

/**
 * Main module model
 */
class View extends \Ufocms\Modules\View //implements IView
{
    protected function getModuleTemplateEntry()
    {
        if (null !== $this->moduleParams['form']) {
            switch ($this->moduleParams['form']) {
                case 'register':
                    return '/formregister.php';
                case 'recover':
                    return '/formrecover.php';
            }
        }
        return parent::getModuleTemplateEntry();
    }
    
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
