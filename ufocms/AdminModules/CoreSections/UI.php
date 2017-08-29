<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\CoreSections;

/**
 * Module level UI
 */
class UI extends \Ufocms\AdminModules\UI
{
    /**
     * @see parent
     */
    protected function init()
    {
        $this->section = array('path' => '', 'title' => 'Структура сайта');
    }
    
    /**
     * @see parent
     */
    public function treeItems()
    {
        $fields = $this->model->getFields();
        $items = $this->model->getItems();
        
        $s = '<table class="items">';
        if ($this->model->isCanCreateItems()) {
            $s .= '<caption class="funcs">' . $this->getItemFuncsHtml(array('itemid' => 0), array('add')) . '</caption>';
        }
        $s .= '<tr>';
        $s .= '<th>id</th><th>Название</th><th>Управление</th></tr>';
        $raw = false;
        foreach ($fields as $field) {
            if ('title' == $field['Name'] && isset($field['Raw']) && $field['Raw']) {
                $raw = true;
            }
        }
        foreach ($items as $item) {
            $disabled = (array_key_exists('disabled', $item) && $item['disabled']) ? ' class="disabled"' : '';
            $s .=   '<tr' . $disabled . '>' . 
                        '<td class="type-int">' . $item['id'] . '</td>' . 
                        '<td>' . 
                            '<div>' . 
                                '<span class="levelpadding">' . str_pad('', $item['level'] * 7, '&#8594;', STR_PAD_LEFT) . '</span>' . 
                                ($raw ? $item['title'] : htmlspecialchars($item['title'])) . 
                                '<div class="subtext" style="margin-left: 40%;">' . 
                                    '<span class="left">' . htmlspecialchars($this->model->getModuleTitle($item['moduleid'])) . '</span>' . 
                                    '<span class="right">' . htmlspecialchars($item['path']) . '</span>' . 
                                '</div>' . 
                            '</div>' . 
                        '</td>' . 
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
                case 'path':
                    $s = ' onchange="checkUrl(this.form)" onblur="checkUrl(this.form);blnUrlSet=\'\'!=this.value;"';
                    break;
                case 'indic':
                    $s = ' onkeyup="setUrl(this.form)" onchange="setUrl(this.form)" onblur="setUrl(this.form)"';
                    break;
                case 'title':
                    $s = ' onblur="blnTitleSet=\'\'!=this.value"';
                    break;
                case 'metadesc':
                    $s = ' onblur="blnDescrSet=\'\'!=this.value"';
                    break;
                case 'metakeys':
                    $s = ' onblur="blnKeysSet=\'\'!=this.value"';
                    break;
            }
        } else {
            switch ($field['Name']) {
                case 'path':
                    $s = ' readonly ondblclick="makeEditable(this)" onchange="displayPathChangeWarning(this)"';
                    break;
            }
        }
        return parent::getFormFieldAttributes($field, $value) . $s;
    }
    
    /**
     * @see parent
     */
    protected function formFieldPathElement(array $field, $value)
    {
        //NOT url, in chrome required want something strange...
        return '<input type="text" maxlength="255" size="50"' . $this->getFormFieldAttributes($field, $value) . '>';
    }
    
    /**
     * @see parent
     */
    public function form()
    {
        return  '<script type="text/javascript" src="templates/sectionsform.js"></script>' . 
                parent::form();
    }
}
