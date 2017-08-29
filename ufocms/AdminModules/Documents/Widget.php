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
            array('Type' => 'int',          'Name' => 'WordsCount',     'Title' => 'Кол-во слов',       'Edit' => true),
            array('Type' => 'text',         'Name' => 'StartMark',      'Title' => 'Отметка начала',    'Edit' => true),
            array('Type' => 'text',         'Name' => 'StopMark',       'Title' => 'Отметка конца',     'Edit' => true),
        );
    }
}
