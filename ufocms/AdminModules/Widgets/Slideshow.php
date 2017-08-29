<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\Widgets;

/**
 * Widget class
 */
class Slideshow extends \Ufocms\AdminModules\Widget
{
    /**
     * @see parent
     */
    protected function setFields()
    {
        $this->fields = array(
            array('Type' => 'int',      'Name' => 'Duration',   'Value' => 5,       'Title' => 'Продолжительность',     'Edit' => true),
            array('Type' => 'bool',     'Name' => 'Random',     'Value' => false,   'Title' => 'Случайный показ',       'Edit' => true),
            array('Type' => 'folder',   'Name' => 'Folder',     'Value' => '',      'Title' => 'Папка изображений',     'Edit' => true),
        );
    }
}
