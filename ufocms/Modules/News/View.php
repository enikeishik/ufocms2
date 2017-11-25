<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\News;

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
        } else if (null !== $this->moduleParams['date']) {
            return array(
                'settings'      => $this->model->getSettings(), 
                'item'          => null, 
                'items'         => $this->model->getItemsByDate(), 
                'itemsCount'    => $this->model->getItemsCount(), 
            );
        } else if (null !== $this->moduleParams['author']) {
            return array(
                'settings'      => $this->model->getSettings(), 
                'item'          => null, 
                'items'         => $this->model->getItemsByAuthor(), 
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
        } else if ($this->moduleParams['isYaTurbo']) {
            return  $this->templatePath . 
                    '/' . strtolower($this->module['Name']) . 
                    '/yaturbo.php';
        } else if ($this->moduleParams['isRambler']) {
            return  $this->templatePath . 
                    '/' . strtolower($this->module['Name']) . 
                    '/rambler.php';
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
