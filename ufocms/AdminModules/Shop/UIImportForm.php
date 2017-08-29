<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\Shop;

/**
 * Module level UI
 */
class UIImportForm extends \Ufocms\AdminModules\Shop\UI
{
    protected function formHandler(array $item = null)
    {
        return  $this->basePath . 
                '&' . $this->config->paramsNames['action'] . '=import' . 
                '&step=2';
    }
    
    public function form()
    {
        extract(array(
            'categoryId' => $this->model->getCurrentCategoryId(), 
            'categories' => $this->model->getCategories(), 
        ));
        include 'TplImportForm.php';
    }
}
