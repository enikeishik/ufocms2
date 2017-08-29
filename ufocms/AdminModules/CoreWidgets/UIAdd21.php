<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\CoreWidgets;

/**
 * Module level UI
 */
class UIAdd21 extends UIAdd2
{
    /**
     * @see parent
     */
    protected function formField(array $field, $value)
    {
        if ('SrcSections' == $field['Name'] || 'SrcItems' == $field['Name']) {
            return '';
        } else if ('Content' == $field['Name'] && !$this->basePathItems['useContent']) {
            return;
        }
        return parent::formField($field, $value);
    }
}
