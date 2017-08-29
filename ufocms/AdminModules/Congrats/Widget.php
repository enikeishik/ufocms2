<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\Congrats;

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
            array('Type' => 'int',  'Name' => 'ItemsStart',     'Value' => 0,   'Title' => 'Пропустить эл-ов',      'Edit' => true),
            array('Type' => 'int',  'Name' => 'ItemsCount',     'Value' => 5,   'Title' => 'Вывести эл-ов',         'Edit' => true),
            array('Type' => 'int',  'Name' => 'DaysLimit',      'Value' => 30,  'Title' => 'Выводить за, дней',     'Edit' => true),
            array('Type' => 'list', 'Name' => 'Pinned',         'Value' => 0,   'Title' => 'Прикрепленные',         'Edit' => true,     'Items' => [['Value' => 0, 'Title' => 'Все'], ['Value' => 1, 'Title' => 'Только прикрепленные'], ['Value' => 2, 'Title' => 'Только НЕ прикрепленные']]),
            array('Type' => 'list', 'Name' => 'Highlighted',    'Value' => 0,   'Title' => 'Подсвеченные',          'Edit' => true,     'Items' => [['Value' => 0, 'Title' => 'Все'], ['Value' => 1, 'Title' => 'Только подсвеченные'], ['Value' => 2, 'Title' => 'Только НЕ подсвеченные']]),
            array('Type' => 'list', 'Name' => 'SortOrder',      'Value' => 0,   'Title' => 'Сортировка',            'Edit' => true,     'Items' => 'getSort'),
        );
    }
    
    /**
     * @return array
     */
    protected function getSort()
    {
        return array(
            array('Value' => 0, 'Title' => 'по дате публикации, по убыванию, закрепленные сверху'),
            array('Value' => 1, 'Title' => 'по дате публикации, по возрастанию, закрепленные сверху'),
            array('Value' => 2, 'Title' => 'по дате публикации, по убыванию'),
            array('Value' => 3, 'Title' => 'по дате публикации, по возрастанию'),
            array('Value' => 4, 'Title' => 'по просмотрам, по убыванию'),
            array('Value' => 5, 'Title' => 'по просмотрам, по возрастанию'),
        );
    }
}
