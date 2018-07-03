<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\CoreWidgets;

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
        $this->section = array('path' => '', 'title' => 'Виджеты');
    }
    
    /**
     * @see parent
     */
    protected function filterByType(array $field, $basePathFields)
    {
        if ('TrgSections' == $field['Name']) {
            $items = array_merge(
                array(array('Value' => 0, 'Title' => 'Все разделы')),
                $this->model->getFieldItems($field)
            );
            return $this->filterByFieldItems($field, $items, $basePathFields);
        }
        return parent::filterByType($field, $basePathFields);
    }
    
    /**
     * @see parent
     */
    protected function listItemsHeaderField(array $field)
    {
        if ('Description' == $field['Name']) {
            return '';
        }
        return parent::listItemsHeaderField($field);
    }
    
    /**
     * @see parent
     */
    protected function listItemsItemFieldList(array $field, array $item)
    {
        if ('TypeId' == $field['Name']) {
            $value = $item[$field['Name']];
            $fieldItems = $this->model->getFieldItems($field); //$field['Items']
            foreach ($fieldItems as $fieldItem) {
                if ($value == $fieldItem['Value']) {
                    $value = $fieldItem['Title'];
                    break;
                }
            }
            $module = '';
            foreach ($fieldItems as $fieldItem) {
                if ($item['TypeId'] == $fieldItem['Value']) {
                    if ($fieldItem['Module']) {
                        $module = '<span class="info" title="Модуль: ' . htmlspecialchars($fieldItem['Module']) . '">i</span>';
                    }
                    break;
                }
            }
            return  '<td class="type-' . $field['Type'] . (isset($field['Class']) ? ' ' . $field['Class'] : '') . '" title="' . htmlspecialchars($item[$field['Name']]) . '">' . 
                        htmlspecialchars($value) . $module . 
                    '</td>';
        }
        return parent::listItemsItemFieldList($field, $item);
    }
    
    /**
     * @see parent
     */
    protected function listItemsItemFieldDefault(array $field, array $item)
    {
        if ('Title' == $field['Name']) {
            return  '<td class="type-' . $field['Type'] . '">' . 
                        htmlspecialchars($item[$field['Name']]) . 
                        ($item['Description'] ? '<span class="info" title="' . htmlspecialchars($item['Description']) . '">i</span>' : '') . 
                    '</td>';
        } else if ('Description' == $field['Name']) {
            return '';
        }
        return parent::listItemsItemFieldDefault($field, $item);
    }
    
    /**
     * @see parent
     */
    protected function getExternalFieldContent(array $field, array $item)
    {
        $items = $this->model->getLinkedTrgSectionsIds($item['Id']);
        if (in_array(0, $items)) {
            return 'Все разделы';
        } else if (1 == count($items) && -1 == $items[0]) {
            return 'Главная страница';
        } else {
            sort($items);
            return '<span title="' . implode(',', $items) . '">Выбранные разделы</span>';
        }
    }
    
    /**
     * @see parent
     */
    protected function setItemFuncs(array $item, array $funcs)
    {
        parent::setItemFuncs($item, $funcs);
        if (isset($item['TypeId']) && 1 == $item['TypeId']) {
            $s = ' <a href="' . $this->basePath . '&action=edit&itemid=' . $item['itemid'] . '" title="Редактировать">Редакт.</a>';
            $this->appendItemFunc('edit', $s);
            $s = ' <a href="' . $this->basePath . '&action=edit&itemid=' . $item['itemid'] . '&code=1" title="Редактировать в HTML редакторе">HTML</a>';
            $this->appendItemFunc('html', $s, 'edit');
        }
    }
    
    /**
     * @see parent
     */
    protected function formFieldElement(array $field, $value)
    {
        if ('TrgSections' == $field['Name']) {
$s = <<<'EOD'
<div class="selector">
<script type="text/javascript">
function selectorTrgSectionsSwitch(on) {
    var el = document.getElementById("trgsections");
    if (on > 0) {
        for (var i = 0; i < el.options.length; i++) {
            el.options[i].selected = true;
        }
        el.disabled = true;
        document.getElementById("trgsectionsall").disabled = false;
    } else {
        el.disabled = false;
        document.getElementById("trgsectionsall").disabled = true;
    }
}
</script>
<input type="hidden" name="TrgSections[]" id="trgsectionsall" value="0" disabled>
<label><input type="radio" name="TrgSectionsAll" value="1" onclick="selectorTrgSectionsSwitch(this.value)">Все</label>
<label><input type="radio" name="TrgSectionsAll" value="0" onclick="selectorTrgSectionsSwitch(this.value)" checked>выбрать</label>
</div>
EOD;
            if (in_array(0, $value)) {
                $s .= '<script type="text/javascript">document.addEventListener("DOMContentLoaded",  function() { var el = document.getElementsByName("TrgSectionsAll"); el[0].click(); });</script>';
            }
            return $s . parent::formFieldElement($field, $value);
        }
        return parent::formFieldElement($field, $value);
    }
    
    /**
     * @see parent
     */
    protected function formFields(array $fields, array $item)
    {
        if (method_exists($this->model, 'getWidgetSingleSource') 
        && $this->model->getWidgetSingleSource()) {
            foreach ($fields as &$field) {
                if ('SrcSections' == $field['Name']) {
                    $field['Type'] = 'list';
                    break;
                }
            }
        }
        return parent::formFields($fields, $item);
    }
}
