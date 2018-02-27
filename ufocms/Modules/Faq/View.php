<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\Faq;

/**
 * Main module model
 */
class View extends \Ufocms\Modules\View //implements IView
{
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
    
    protected function renderHead()
    {
        if (0 == $this->params->itemId) {
            parent::renderHead();
        }
    }
}
