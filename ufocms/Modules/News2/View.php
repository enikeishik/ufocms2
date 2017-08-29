<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\News2;

/**
 * Main module model
 */
class View extends \Ufocms\Modules\View //implements IView
{
    use Tools;
    
    /**
     * @return array
     */
    protected function getModuleContext()
    {
        if (null !== $this->params->actionId) {
            return array(
                'settings'      => null, 
                'item'          => null, 
                'items'         => null, 
                'itemsCount'    => null, 
                'actionResult'  => $this->model->getActionResult(), 
            );
        }
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
        } else if (null !== $this->moduleParams['tagId']) {
            return array(
                'settings'      => $this->model->getSettings(), 
                'item'          => null, 
                'items'         => $this->model->getItemsByTag(), 
                'itemsCount'    => $this->model->getItemsCount(), 
            );
        } else if (null !== $this->moduleParams['date']) {
            return array(
                'settings'      => $this->model->getSettings(), 
                'item'          => null, 
                'items'         => $this->model->getItemsByDate(), 
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
    
    protected function getModuleTemplateEntry()
    {
        if (1 === $this->params->actionId) {
            return '/form.php';
        }
        return parent::getModuleTemplateEntry();
    }
    
    protected function getLayout()
    {
        if ($this->moduleParams['isRss']) {
            return  $this->templatePath . 
                    '/' . strtolower($this->module['Name']) . 
                    '/rss.php';
        } else if ($this->moduleParams['isYandex']) {
            return  $this->templatePath . 
                    '/' . strtolower($this->module['Name']) . 
                    '/yandex.php';
        } else if ($this->moduleParams['isYaDzen']) {
            return  $this->templatePath . 
                    '/' . strtolower($this->module['Name']) . 
                    '/yadzen.php';
        } else {
            return parent::getLayout();
        }
    }
    
    /**
     * Wrap for model
     * @see \Ufocms\Modules\News2\Model::getItemTags
     */
    public function getItemTags($itemId = null)
    {
        return $this->model->getItemTags($itemId);
    }
    
    /**
     * Wrap for model
     * @see \Ufocms\Modules\News2\Model::getSimilarItems
     */
    public function getSimilarItems($count = 5, $itemId = null)
    {
        return $this->model->getSimilarItems($count, $itemId);
    }
}
