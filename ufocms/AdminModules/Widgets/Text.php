<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\Widgets;

/**
 * Widget class
 */
class Text extends \Ufocms\AdminModules\Widget
{
    /**
     * @see parent
     */
    protected function init()
    {
        parent::init();
        $this->useContent = true;
    }
}
