<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\Oldurls;

/**
 * Main module model
 */
class View extends \Ufocms\Modules\View //implements IView
{
    /**
     * @see parent
     */
    protected function getModuleContext()
    {
        if (0 != $this->params->itemId) {
            $item = $this->model->getItem();
            if (null === $item) {
                $this->core->riseError(404, 'Item not exists');
            } else if (isset($item['Target']) && '' != $item['Target']) {
                $this->core->riseError(301, 'Move to current URL', $item['Target']);
            }
            return array(
                'settings'      => $this->model->getSettings(), 
                'item'          => $item, 
                'items'         => null, 
                'itemsCount'    => $this->model->getItemsCount(), 
            );
        } else {
            return array(
                'settings'      => $this->model->getSettings(), 
                'item'          => null, 
                'items'         => $this->model->getItems(), 
                'itemsCount'    => $this->model->getItemsCount(), 
            );
        }
    }
    
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
