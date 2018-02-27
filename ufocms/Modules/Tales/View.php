<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\Tales;

/**
 * Main module model
 */
class View extends \Ufocms\Modules\View //implements IView
{
    use Tools;
    
    /**
     * @see parent
     */
    protected function getModuleContext()
    {
        if (0 != $this->params->itemId) {
            $item = $this->model->getItem();
            if (null === $item) {
                $this->core->riseError(404, 'Item not exists');
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
