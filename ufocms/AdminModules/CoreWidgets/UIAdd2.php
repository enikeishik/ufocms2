<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\CoreWidgets;

/**
 * Module level UI
 */
class UIAdd2 extends UI
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
    protected function formFieldElement(array $field, $value)
    {
        if ('TypeId' == $field['Name']) {
            $typeId = $this->basePathItems['TypeId'];
            $s = '<input type="hidden" name="TypeId" value="' . $typeId . '">';
            $items = $this->model->getFieldItems($field); //init $field['Items']
            unset($items);
            $field =& $this->model->getFieldRef($field['Name']); //get link to field instead of current copy
            $field['Type'] = 'list';
            $field['Disabled'] = true;
            foreach ($field['Items'] as &$item) {
                $item['Title'] .= ' [' . $item['Module'] . ']';
            }
            unset($item);
            return parent::formFieldElement($field, $typeId) . $s;
        }
        return parent::formFieldElement($field, $value);
    }
    
    /**
     * @see parent
     */
    protected function formHandler(array $item = null)
    {
        return parent::formHandler($item) . '&step=3';
    }
}
