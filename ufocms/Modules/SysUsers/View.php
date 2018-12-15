<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\SysUsers;

/**
 * Main module view
 */
class View extends \Ufocms\Modules\View //implements ViewInterface
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
}
