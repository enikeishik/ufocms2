<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\Oldurls;

/**
 * Main module view
 */
class View extends \Ufocms\Modules\View //implements ViewInterface
{
    /**
     * @see parent
     */
    protected function getMetaDesc()
    {
        if (0 != $this->params->itemId) {
            $settings = $this->model->getSettings();
            $item = $this->model->getItem();
            return htmlspecialchars($item['MetaDesc']);
        }
        return parent::getMetaDesc();
    }
    
    /**
     * @see parent
     */
    protected function getMetaKeys()
    {
        if (0 != $this->params->itemId) {
            $item = $this->model->getItem();
            $settings = $this->model->getSettings();
            $item = $this->model->getItem();
            return htmlspecialchars($item['MetaKeys']);
        }
        return parent::getMetaKeys();
    }
}
