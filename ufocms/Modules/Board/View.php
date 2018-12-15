<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\Board;

/**
 * Main module view
 */
class View extends \Ufocms\Modules\View //implements ViewInterface
{
    /**
     * @see parent
     */
    protected function renderHead($entry = null)
    {
        if (0 == $this->params->itemId) {
            parent::renderHead();
        }
    }
}
