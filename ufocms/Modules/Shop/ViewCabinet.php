<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\Shop;

/**
 * Cabinet model
 */
class ViewCabinet extends \Ufocms\Modules\View //implements ViewInterface
{
    protected function getModuleTemplateEntry()
    {
        if ('login' == $this->moduleParams['cabinet']) {
            return '/login.php';
        }
        return '/cabinet.php';
    }
}
