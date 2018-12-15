<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\Tales;

/**
 * Main module view
 */
class View extends \Ufocms\Modules\View //implements ViewInterface
{
    use Tools;
    
    /**
     * @see parent
     */
    protected function getMetaDesc()
    {
        if (0 != $this->params->itemId) {
            $settings = $this->model->getSettings();
            $item = $this->model->getItem();
            if ($settings['InheritMeta']) {
                return htmlspecialchars($item['MetaDesc'] . ' ' . $this->context['section']['metadesc'] . ' ' . $this->context['site']['SiteMetaDescription']);
            } else {
                return htmlspecialchars($item['MetaDesc']);
            }
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
            if ($settings['InheritMeta']) {
                return htmlspecialchars($item['MetaKeys'] . ' ' . $this->context['section']['metakeys'] . ' ' . $this->context['site']['SiteMetaKeywords']);
            } else {
                return htmlspecialchars($item['MetaKeys']);
            }
        }
        return parent::getMetaKeys();
    }
}
