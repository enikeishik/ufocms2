<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\News;

/**
 * Widget class
 */
class WidgetAuthors extends \Ufocms\AdminModules\Widget
{
    /**
     * @see parent
     */
    protected function setFields()
    {
        $this->fields = array(
            array('Type' => 'bool', 'Name' => 'ShowCount',  'Value' => true,    'Title' => 'Показывать кол-во',         'Edit' => true),
            array('Type' => 'bool', 'Name' => 'Random',     'Value' => false,   'Title' => 'В случайном порядке',       'Edit' => true),
            array('Type' => 'int',  'Name' => 'Limit',      'Value' => 0,       'Title' => 'Количество отображаемых',   'Edit' => true),
        );
    }
}
