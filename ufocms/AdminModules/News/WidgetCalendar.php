<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\News;

/**
 * Widget class
 */
class WidgetCalendar extends \Ufocms\AdminModules\Widget
{
    /**
     * @see parent
     */
    protected function setFields()
    {
        $this->fields = array(
            array('Type' => 'bool', 'Name' => 'LinkEmpty',  'Value' => false,   'Title' => 'Сылки на пустых днях',  'Edit' => true),
            array('Type' => 'bool', 'Name' => 'ShowCount',  'Value' => true,    'Title' => 'Показывать кол-во',     'Edit' => true),
        );
    }
}
