<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\CoreWidgets;

/**
 * Module level UI
 */
class UIAdd22 extends UIAdd2
{
    /**
     * @see parent
     */
    protected function formFieldElement(array $field, $value)
    {
        if ('SrcSections' == $field['Name']) {
            $items = $this->model->getFieldItems($field); //$field['Items']
            $moduleId = $this->model->getTypeModuleId($this->basePathItems['TypeId']);
            
            if ($this->model->getWidgetSingleSource() && $this->model->getWidgetSourceDepends()) {
                //set JS handler for reload page on SrcSections change with SrcSections id as parameter
                //only for sourceDepend widgets
                $jsHandler = '';
                
                //if SrcSections not set, find first item with required type
                //and set it as SrcSections, for sourceDepends widgets
                if (0 == $this->basePathItems['SrcSections']) {
                    foreach ($items as $item) {
                        if ($item['IsEnabled'] && $moduleId == $item['ModuleId']) {
                            $this->basePathItems['SrcSections'] = $item['Value'];
                            $this->moduleParams['SrcSections'] = $item['Value'];
                            break;
                        }
                    }
                }
                
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
                if (is_array($value)) {
                    $selected = in_array($item['Value'], $value);
                } else if (is_string($value) && $this->tools->isStringOfIntegers($value)) {
                    $selected = in_array($item['Value'], $this->tools->getArrayOfIntegersFromString($value));
                } else {
                    $selected = $value == $item['Value'];
                }
                if ($item['IsEnabled'] && $moduleId == $item['ModuleId']) {
                    $s .=   '<option value="' . $item['Value'] . '"' . ($selected ? ' selected' : '') . '>' . 
                            htmlspecialchars($item['Title']) . 
                            '</option>';
                } else {
                    $s .=   '<option disabled>' . 
                            htmlspecialchars($item['Title']) . 
                            '</option>';
                }
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
        if ('Content' == $field['Name']) {
            //hide this for non Text (HTML) widgets
            return;
        }
        return parent::formField($field, $value);
    }
}
