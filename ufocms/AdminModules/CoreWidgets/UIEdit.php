<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\CoreWidgets;

/**
 * Module level UI
 */
class UIEdit extends UI
{
    /**
     * @see parent
     */
    protected function init()
    {
        parent::init();
        $this->section['title'] .= ' \ редактирование';
    }
    
    /**
     * @see parent
     */
    protected function formFieldElement(array $field, $value)
    {
        if ('TypeId' == $field['Name']) {
            $items = $this->model->getFieldItems($field); //init $field['Items']
            unset($items);
            $field =& $this->model->getFieldRef($field['Name']); //get link to field instead of current copy
            $field['Type'] = 'list';
            foreach ($field['Items'] as &$item) {
                $item['Title'] .= ' [' . $item['Module'] . ']';
            }
            unset($item);
            
        } else if ('SrcSections' == $field['Name']) {
            $items = $this->model->getFieldItems($field); //$field['Items']
            $moduleId = $this->model->getTypeModuleId($this->model->getItem()['TypeId']);
            
            if ($this->model->getWidgetSingleSource() && $this->model->getWidgetSourceDepends()) {
                //set JS handler for reload page on SrcSections change with SrcSections id as parameter
                //only for sourceDepend widgets
                $qstring = '?';
                foreach ($this->basePathItems as $name => $val) {
                    if ('SrcSections' != $name) {
                        $qstring .= $name . '=' . $val . '&';
                    }
                }
                $jsHandler = ' onchange="location.href=\'' . htmlspecialchars($qstring, ENT_QUOTES) . 'SrcSections=\' + this.options[this.options.selectedIndex].value"';
                
                $s = '<select name="SrcSections"' . $jsHandler . ' required>';
            } else if ($this->model->getWidgetSourceDepends()) {
                $qstring = '?';
                foreach ($this->basePathItems as $name => $val) {
                    if ('SrcSections' != $name) {
                        $qstring .= $name . '=' . $val . '&';
                    }
                }
                $jsHandler = ' onchange="location.href=\'' . htmlspecialchars($qstring, ENT_QUOTES) . '\' + getSrcSectionsSelected(this)"';
                $s =    '<script type="text/javascript">' . 
                        'function getSrcSectionsSelected(s) { var r = ""; for (var i = 0; i < s.options.length; i++) { if (s.options[i].selected) { r += "&SrcSections[]=" + s.options[i].value; } } return "" != r ? r.substr(1) : r; }' . 
                        '</script>' . 
                        '<select name="SrcSections[]" multiple size="10"' . $jsHandler . ' required>';
            } else if ($this->model->getWidgetSingleSource()) {
                $s = '<select name="SrcSections" required>';
            } else {
                $s = '<select name="SrcSections' . ('mlist' == $field['Type'] ? '[]" multiple size="10"' : '"') . ' required>';
            }
            
            foreach ($items as $item) {
                if (!$this->basePathItems['SrcSections']) {
                    if (is_array($value)) {
                        $selected = in_array($item['Value'], $value);
                    } else if (is_string($value) && $this->tools->isStringOfIntegers($value)) {
                        $selected = in_array($item['Value'], $this->tools->getArrayOfIntegersFromString($value));
                    } else {
                        $selected = $value == $item['Value'];
                    }
                } else {
                    $val = $this->basePathItems['SrcSections'];
                    if (is_array($val)) {
                        $selected = in_array($item['Value'], $val);
                    } else if (is_string($val) && $this->tools->isStringOfIntegers($val)) {
                        $selected = in_array($item['Value'], $this->tools->getArrayOfIntegersFromString($val));
                    } else {
                        $selected = $val == $item['Value'];
                    }
                }
                $s .=   '<option' . 
                            ($item['IsEnabled'] && $moduleId == $item['ModuleId'] ? ' value="' . $item['Value'] . '"' : ' disabled') . 
                            ($selected ? ' selected' : '') . 
                        '>' . 
                        htmlspecialchars($item['Title']) . 
                        '</option>';
            }
            return $s . '</select>';
            
        } else if ('SrcItems' == $field['Name']) {
            //TODO: make this
            
        }
        return parent::formFieldElement($field, $value);
    }
    
    /**
     * @see parent
     */
    protected function formField(array $field, $value)
    {
        if (('SrcSections' == $field['Name'] || 'SrcItems' == $field['Name']) && !$this->basePathItems['useSources']) {
            return '';
        } else if ('Content' == $field['Name'] && !$this->basePathItems['useContent']) {
            return;
        }
        return parent::formField($field, $value);
    }
}
