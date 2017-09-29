<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\CoreWidgets;

/**
 * Module level UI
 */
class UIAdd1 extends UI
{
    /**
     * @see parent
     */
    protected function init()
    {
        parent::init();
        $this->section['title'] .= ' \ добавление';
    }
    
    /**
     * @see parent
     */
    protected function formHandler(array $item = null)
    {
        //на всякий случай также дополняем параметрами путь к обработчику формы
        return  $this->basePath . 
                '&' . $this->config->paramsNames['action'] . '=add' . 
                '&' . $this->config->paramsNames['itemId'] . '=0' . 
                '&step=2';
    }
    
    /**
     * @see parent
     */
    protected function formElement(array $attributes)
    {
        //переводим параметры из qstr в поля
        $s = '';
        foreach ($this->basePathItems as $name => $value) {
            $s .= '<input type="hidden" name="' . $name . '" value="' . $value . '">';
        }
        $s .= '<input type="hidden" name="' . $this->config->paramsNames['action'] . '" value="add">';
        $s .= '<input type="hidden" name="' . $this->config->paramsNames['itemId'] . '" value="0">';
        $s .= '<input type="hidden" name="step" value="2">';
        return parent::formElement($attributes) . $s;
    }
    
    /**
     * @see parent
     */
    protected function formElementAttributes($handler)
    {
        //форма пойдет через GET
        return array(
            'action' => $handler, 
            'method' => 'get'
        );
    }
    
    /**
     * @see parent
     */
    protected function formFieldRadioListElement(array $field, $value)
    {
        $s = '';
        $items = $this->model->getFieldItems($field); //$field['Items']
        foreach ($items as $item) {
            if (is_array($value)) {
                $selected = in_array($item['Value'], $value);
            } else if (is_string($value) && $this->tools->isStringOfIntegers($value)) {
                $selected = in_array($item['Value'], $this->tools->getArrayOfIntegersFromString($value));
            } else {
                $selected = $value == $item['Value'];
            }
            if ($selected) {
                $selected = ' checked';
            } else {
                $selected = '';
            }
            $s .=   '<div>' . 
                        '<label>' . 
                            '<input type="radio"' . $this->getFormFieldAttributes($field, $value) . ' value="' . htmlspecialchars($item['Value']) . '"' . $selected . '>' . 
                            htmlspecialchars($item['Title']) . 
                            ($item['Module'] ? '<span>[' . htmlspecialchars($item['Module']) . ']</span>' : '') . 
                        '</label>' . 
                        '<div>' . htmlspecialchars($item['Description']) . '</div>' . 
                    '</div>';
        }
        return $s;
    }
    
    /**
     * @see parent
     */
    protected function formFields(array $fields, array $item)
    {
        foreach ($fields as $field) {
            if ('TypeId' == $field['Name']) {
                break;
            }
        }
        return $this->formField($field, $item[$field['Name']]);
    }
    
    /**
     * @see parent
     */
    protected function formSubmitElementAttributes()
    {
        return array(
            'value' => 'Далее'
        );
    }
}
