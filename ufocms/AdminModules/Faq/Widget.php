<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\Faq;

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
            array('Type' => 'int',  'Name' => 'ItemsStart',     'Value' => 0,   'Title' => 'Пропустить эл-ов',          'Edit' => true),
            array('Type' => 'int',  'Name' => 'ItemsCount',     'Value' => 5,   'Title' => 'Вывести эл-ов',             'Edit' => true),
            array('Type' => 'int',  'Name' => 'DaysLimit',      'Value' => 0,   'Title' => 'Выводить за, дней',         'Edit' => true),
            array('Type' => 'list', 'Name' => 'SortOrder',      'Value' => 0,   'Title' => 'Сортировка',                'Edit' => true,     'Items' => 'getSort'),
        );
    }
    
    /**
     * @return array
     */
    protected function getSort()
    {
        return array(
            array('Value' => 0, 'Title' => 'По дате вопроса, по убыванию'),
            array('Value' => 1, 'Title' => 'По дате вопроса, по возрастанию'),
            array('Value' => 2, 'Title' => 'По дате ответа, по возрастанию'),
            array('Value' => 3, 'Title' => 'По дате ответа, по убыванию'),
        );
    }
}
