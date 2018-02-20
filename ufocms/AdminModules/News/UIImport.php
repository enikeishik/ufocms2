<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\News;

/**
 * Module level UI
 */
class UIImport extends \Ufocms\AdminModules\News\UI
{
    public function singleItems()
    {
        $fields = $this->model->getFields();
        $items = $this->model->getItems();
        
        $s = '<div class="items">';
        foreach ($items as $item) {
            $s .=   '<div class="item"><div class="itemhead">' . 
                    '<div class="itemfield"><a href="' . $this->basePath . '&action=importitems&itemid=' . $item['itemid'] . '&wysiwyg=1">Получить данные источника</a></div>' . 
                        '<div class="itemfield"><span class="fieldname">Название</span>' . $item['Title'] . '</div>' . 
                        '<div class="itemfield"><span class="fieldname">Url</span><a href="#" onclick="javascript:window.open(\'' . $item['Url'] . '\');return false;">' . $item['Url'] . '</a></div>' . 
                    '</div></div>';
        }
        return $s . '</div>';
    }
}
