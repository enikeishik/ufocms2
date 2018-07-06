<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\Board;

/**
 * Main module model
 */
class View extends \Ufocms\Modules\View //implements IView
{
    /**
     * @see parent
     */
    protected function getLayout()
    {
        if ($this->moduleParams['isRss']) {
            return $this->findTemplate(
                $this->templatePath, 
                $this->module['Name'], 
                '/rss.php'
            );
        } else {
            return parent::getLayout();
        }
    }
    
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
