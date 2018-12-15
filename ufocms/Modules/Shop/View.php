<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\Shop;

/**
 * Main module view
 */
class View extends \Ufocms\Modules\View //implements ViewInterface
{
    /**
     * @var array|null
     */
    protected $category = null;
    
    /**
     * @see parent
     */
    protected function init()
    {
        $this->category = $this->context['category'] ?? null;
        parent::init();
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
    
    /**
     * @see parent
     */
    protected function getHeadTitle()
    {
        $title = parent::getHeadTitle();
        if (null !== $this->category) {
            $title = htmlspecialchars($this->category['Title']) . ' - ' . $title;
        }
        return $title;
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
    
    /**
     * @see parent
     */
    protected function getModuleTemplateEntry()
    {
        if (null !== $this->moduleParams['order']) {
            switch ($this->moduleParams['order']) {
                case 'add':
                case 'remove':
                case 'clear':
                case 'confirm':
                case 'send':
                case 'sended':
                    return '/orderresult.php';
                case 'form':
                    return '/orderform.php';
                default:
                    return '/order.php';
            }
        }
        return parent::getModuleTemplateEntry();
    }
    
    /**
     * @see parent
     */
    protected function getPaginationContext()
    {
        if (0 != $this->moduleParams['categoryId']) {
                return array(
                    'path' =>   $this->params->sectionPath . 
                                $this->moduleParams['catAlias'] . '/'
                );
        } else {
            return array('path' => $this->params->sectionPath);
        }
    }
    
    /**
     * Генерация списка категорий.
     */
    protected function renderCategories()
    {
        if (0 == $this->moduleParams['categoryId']) {
            $this->context['items'] = $this->model->getTopCategories(6, 6);
            $templateEntry = '/catstop.php';
        } else {
            $items = $this->model->getCategoryParents();
            $siblings = $this->model->getCategorySiblings($this->moduleParams['categoryId']);
            if (null !== $siblings && 1 < count($siblings)) {
                array_pop($items);
                foreach ($siblings as &$item) {
                    if ($item['Id'] == $this->moduleParams['categoryId']) {
                        $item['Children'] = $this->model->getCategoryChildren($this->moduleParams['categoryId']);
                        break;
                    }
                }
                unset($item);
                $items = array_merge($items, $siblings);
                unset($siblings);
            } else {
                $items[count($items) - 1]['Children'] = 
                    $this->model->getCategoryChildren($this->moduleParams['categoryId']);
            }
            $this->context['items'] = $items;
            $templateEntry = '/catsbranch.php';
        }
        
        extract($this->context);
        
        $template = $this->findTemplate(
            $this->templatePath, 
            $this->module['Name'], 
            $templateEntry
        );
        if (file_exists($template)) {
            include $template;
        } else {
            $template = $this->config->rootPath . 
                        $this->config->templatesDir . $this->config->themeDefault . 
                        '/' . strtolower($this->module['Name']) . 
                        $templateEntry;
            if (file_exists($template)) {
                include $template;
            }
        }
    }
}
