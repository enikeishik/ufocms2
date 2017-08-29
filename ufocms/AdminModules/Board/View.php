<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\Board;

/**
 * View model class
 */
class View extends \Ufocms\AdminModules\View
{
    protected function getItemsLayout()
    {
        return 'templates/single.php';
    }
}
