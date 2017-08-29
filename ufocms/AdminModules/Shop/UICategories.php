<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\Shop;

/**
 * Module level UI
 */
class UICategories extends \Ufocms\AdminModules\Shop\UI
{
    /**
     * @return string
     */
    public function treeItems()
    {
        $fields = $this->model->getFields();
        $items = $this->model->getItems();
        
        $s = '<table class="items">';
        $s .= '<caption class="funcs">' . $this->getItemFuncsHtml(array('itemid' => 0), array('add')) . '</caption>';
        $s .= '<tr>';
        $s .= '<th>id</th><th>Название</th><th>Элементов (своих)</th><th>Шаблон</th><th>Управление</th></tr>';
        foreach ($items as $item) {
            $disabled = (array_key_exists('disabled', $item) && $item['disabled']) ? ' class="disabled"' : '';
            $s .=   '<tr' . $disabled . '>' . 
                        '<td class="type-int">' . $item['itemid'] . '</td>' . 
                        '<td>' . 
                            '<div>' . 
                                '<span class="levelpadding">' . str_pad('', ($item['LevelId'] - 1) * 7, '&#8594;', STR_PAD_LEFT) . '</span>' . 
                                htmlspecialchars($item['Title']) . 
                                '<div class="subtext">' . htmlspecialchars($item['path']) . '</div>' . 
                            '</div>' . 
                        '</td>' . 
                        '<td class="type-int">' . $item['TotalItemsCount'] . ' (' . $item['SelfItemsCount'] . ')</td>' . 
                        '<td class="type-int">' . $item['TemplateId'] . '</td>' . 
                        '<td class="funcs">' . $this->getItemFuncsHtml($item, array('open', 'edit', 'up', 'down', 'disable', 'delconfirm')) . '</td>' . 
                    '</tr>';
        }
        return $s . '</table>';
    }
    
    /**
     * @see parent
     */
    protected function getFormFieldAttributes(array $field, $value)
    {
        $s = '';
        if (0 == $this->params->itemId) {
            switch ($field['Name']) {
                case 'Alias':
                    $s = ' onchange="checkAlias(this)" onblur="checkAlias(this)"';
                    break;
                case 'Title':
                    $s = ' onkeyup="setAlias(this)" onchange="setAlias(this)" onblur="setAlias(this)"';
                    break;
            }
        } else {
            switch ($field['Name']) {
                case 'Alias':
                    $s = ' readonly ondblclick="makeEditable(this)"';
                    break;
            }
        }
        return parent::getFormFieldAttributes($field, $value) . $s;
    }
    
    /**
     * @see parent
     */
    public function form()
    {
        return  '<script type="text/javascript" src="templates/shop/form.js"></script>' . 
                parent::form();
    }
}
