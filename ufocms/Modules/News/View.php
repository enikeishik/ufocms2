<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\News;

/**
 * Main module view
 */
class View extends \Ufocms\Modules\View //implements ViewInterface
{
    use Tools;
    
    /**
     * @see parent
     */
    protected function renderHead($entry = null)
    {
        if (0 == $this->params->itemId) {
            parent::renderHead();
        } else {
            parent::renderHead('/itemhead.php');
        }
    }
}
