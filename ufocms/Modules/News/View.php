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
        } else if ($this->moduleParams['isYandex']) {
            return $this->findTemplate(
                $this->templatePath, 
                $this->module['Name'], 
                '/yandex.php'
            );
        } else if ($this->moduleParams['isYaDzen']) {
            return $this->findTemplate(
                $this->templatePath, 
                $this->module['Name'], 
                '/yadzen.php'
            );
        } else if ($this->moduleParams['isYaTurbo']) {
            return  $this->findTemplate(
                $this->templatePath, 
                $this->module['Name'], 
                '/yaturbo.php'
            );
        } else if ($this->moduleParams['isRambler']) {
            return $this->findTemplate(
                $this->templatePath, 
                $this->module['Name'], 
                '/rambler.php'
            );
        } else if ($this->moduleParams['isAMP']) {
            return $this->findTemplate(
                $this->templatePath, 
                $this->module['Name'], 
                '/itemamp.php'
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
        } else {
            parent::renderHead('/itemhead.php');
        }
    }
}
