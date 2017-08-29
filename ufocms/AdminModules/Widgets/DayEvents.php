<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\Widgets;

/**
 * Widget class
 */
class DayEvents extends \Ufocms\AdminModules\Widget
{
    /**
     * @see parent
     */
    protected function setFields()
    {
        $this->fields = array(
            array('Type' => 'bool',     'Name' => 'Yesterday',  'Value' => false,   'Title' => 'Показывать вчерашние события',  'Edit' => true),
            array('Type' => 'bool',     'Name' => 'Tommorow',   'Value' => false,   'Title' => 'Показывать завтрашние события', 'Edit' => true),
            array('Type' => 'file',     'Name' => 'EventsFile', 'Value' => '',      'Title' => 'Файл с данными',                'Edit' => true,     'Required' => true),
        );
    }
}
