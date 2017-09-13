<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\Documents;

/**
 * Widget class
 */
class Widget extends \Ufocms\AdminModules\Widget
{
    /**
     * @see parent
     */
    protected function setFields()
    {
        $this->fields = array(
            array('Type' => 'int',      'Name' => 'WordsCount',     'Value' => '10',    'Title' => 'Кол-во слов',       'Edit' => true),
            array('Type' => 'text',     'Name' => 'StartMark',      'Value' => '',      'Title' => 'Отметка начала',    'Edit' => true),
            array('Type' => 'text',     'Name' => 'StopMark',       'Value' => '',      'Title' => 'Отметка конца',     'Edit' => true),
        );
    }
}
