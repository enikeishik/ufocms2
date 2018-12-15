<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\News2;

/**
 * Main module view
 */
class View extends \Ufocms\Modules\View //implements ViewInterface
{
    use Tools;
    
    protected function getModuleTemplateEntry()
    {
        if (1 === $this->params->actionId) {
            return '/form.php';
        }
        return parent::getModuleTemplateEntry();
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
