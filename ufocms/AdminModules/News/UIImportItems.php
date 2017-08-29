<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\News;

/**
 * Module level UI
 */
class UIImportItems extends \Ufocms\AdminModules\News\UI
{
    /**
     * @see parent
     */
    public function singleItems()
    {
        $fields = $this->model->getFields();
        $items = $this->model->getItems();
        
        $s =    '<form action="' . $this->basePath . '" method="post" onsubmit="document.getElementById(\'removeUnusedBtn\').click(); return true;">' . 
                '<div class="items">';
        
        $i = 0;
        foreach ($items as $item) {
            $i++;
            $s .=   '<div class="item" id="view' . $i . '">' . 
                        '<div class="itemhead">' . 
                            '<div class="itemfield"><span style="background-color: #eee; padding: 5px 5px 2px 2px;">' . 
                                '<label><input type="checkbox"' . 
                                    ' onclick="showForm(' . $i . ')"' . 
                                '>&nbsp;импорт</label></span></div>' . 
                            '<div class="itemfield"><span class="fieldname">Дата</span>' . $item['DateCreate'] . '</div>' . 
                        '</div>' . 
                        '<div class="itembody">' . 
                            '<div class="itemfield"><div class="fieldname">Заголовок</div><div class="fieldvalue">' . $item['Title'] . '</div></div>' . 
                            '<div class="itemfield"><div class="fieldname">Анонс</div><div class="fieldvalue">' . $item['Announce'] . '</div></div>' . 
                            '<div class="itemfield"><div class="fieldname">Текст</div><div class="fieldvalue">' . $item['Body'] . '</div></div>' . 
                        '</div>' . 
                    '</div>';
            
            $s .=   '<div class="item" id="form' . $i . '" style="display: none;">' . 
                        '<input type="hidden" name="Import' . $i . '" id="Import' . $i . '" value="0">' . 
                        '<table border="1" cellpadding="4" cellspacing="0" width="100%" class="form">';
            foreach ($fields as $field) {
                if (!$field['Edit']) {
                    continue;
                }
                $fieldName = $field['Name'];
                $field['Name'] .= $i;
                $s .= $this->formField($field, $item[$fieldName]);
            }
            $s .=       '</table>' . 
                    '</div>';
        }
        $s .=   '<div><input type="button" id="removeUnusedBtn" value="Убрать ненужные" title="Убрать элементы не помеченные для импорта" onclick="removeUnused(' . $i . '); this.parentNode.style.display=\'none\';"></div>';
        
        $s .=   '</div>' . 
                '<div><input type="hidden" name="itemscount" value="' . $i . '"><input type="submit" value="Импортировать"></div>' . 
                '</form>' . "\r\n";
        $s .=   '<script type="text/javascript">' . "\r\n" . 
                'function showForm(i) { var el = document.getElementById("view" + i); el.parentNode.removeChild(el); document.getElementById("form" + i).style.display = ""; document.getElementById("Import" + i).value = "1"; }' . "\r\n" . 
                'function removeUnused(total) { for (var i = 1; i <= total; i++) { var el = document.getElementById("view" + i); if (el) { el.parentNode.removeChild(el); el = document.getElementById("form" + i); el.parentNode.removeChild(el); } } }' . "\r\n" . 
                '</script>' . "\r\n";
        return $s;
    }
    
    /**
     * @see parent
     */
    protected function formFieldListElement(array $field, $value)
    {
        $s = '<select' . $this->getFormFieldAttributes($field, $value) . ('mlist' == $field['Type'] ? ' multiple size="10"' : '') . '>';
        $items = $this->model->getFieldItems('SectionId');
        foreach ($items as $item) {
            $selected = $value == $item['Value'];
            if ($selected) {
                $s .= '<option value="' . htmlspecialchars($item['Value']) . '" selected>' . htmlspecialchars($item['Title']) . '</option>';
            } else {
                $s .= '<option value="' . htmlspecialchars($item['Value']) . '">' . htmlspecialchars($item['Title']) . '</option>';
            }
        }
        $s .= '</select>';
        return $s;
    }
    
    /**
     * @see parent
     */
    protected function formFieldComboElement(array $field, $value)
    {
        $options = '';
        $items = $this->model->getFieldItems('Author');
        foreach ($items as $item) {
            $options .= '<option value="' . htmlspecialchars($item['Value']) . '"></option>';
        }
        return  '<input type="text" list="' . $field['Name'] . 'DataList"' . 
                    ' maxlength="255" size="40"' . 
                    $this->getFormFieldAttributes($field, $value) . 
                '>' . 
                '<datalist id="' . $field['Name'] . 'DataList">' . 
                    $options . 
                '</datalist>';
    }
}
