<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\News;

/**
 * Main module model
 */
class CommonView extends \Ufocms\Modules\View //implements IView
{
    use Tools;
    
    /**
     * @see parent
     */
    protected function init()
    {
        if (0 != $this->params->itemId) {
            $item = $this->model->getItem();
            $this->core->riseError(301, 'Use section path', $item['path'] . $this->params->itemId);
        }
    }
    
    /**
     * @see parent
     */
    protected function getApplicationContext()
    {
        $arr = parent::getApplicationContext();
        $arr['section']['path']     = $this->params->sectionPath . '/' . strtolower($this->module['Name']) . '/';
        $arr['section']['indic']    = 'Новости';
        $arr['section']['title']    = 'Новости';
        return $arr;
    }
    
    /**
     * @see parent
     */
    protected function getModuleContext()
    {
        if (0 != $this->params->itemId) {
             //not used, riseError 301 in init()
            return array(
                'settings'      => null, 
                'item'          => $this->model->getItem(), 
                'items'         => null, 
                'itemsCount'    => $this->model->getItemsCount(), 
            );
        } else if (null !== $this->moduleParams['date']) {
            return array(
                'settings'      => null, 
                'item'          => null, 
                'items'         => $this->model->getItemsByDate(), 
                'itemsCount'    => $this->model->getItemsCount(), 
            );
        } else if (null !== $this->moduleParams['authors']) {
            return array(
                'settings'      => null, 
                'item'          => null, 
                'items'         => $this->model->getAuthors(), 
                'itemsCount'    => $this->model->getItemsCount(), 
            );
        } else if (null !== $this->moduleParams['author']) {
            return array(
                'settings'      => null, 
                'item'          => null, 
                'items'         => $this->model->getItemsByAuthor(), 
                'itemsCount'    => $this->model->getItemsCount(), 
            );
        } else {
            return array(
                'settings'      => null, 
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
     * @see parent
     */
    protected function getModuleTemplateEntry()
    {
        if (0 != $this->params->itemId) {
            //not used, riseError 301 in init()
            return '/commonitem.php';
        } else if (null !== $this->moduleParams['authors']) {
            return '/commonauthors.php';
        } else {
            return '/commonindex.php';
        }
    }
    
    /**
     * @see parent
     */
    protected function renderHead()
    {
        if (0 == $this->params->itemId) {
            parent::renderHead();
        }
    }
}
