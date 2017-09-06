<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\Tales;

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
            array('Type' => 'list', 'Name' => 'SortOrder',      'Value' => 0,   'Title' => 'Сортировка',                'Edit' => true,     'Items' => 'getSort'),
        );
    }
    
    /**
     * @return array
     */
    protected function getSort()
    {
        return array(
            array('Value' => '0', 'Title' => 'по сортировке, по возрастанию'), 
            array('Value' => '1', 'Title' => 'по сортировке, по убыванию'), 
            array('Value' => '2', 'Title' => 'по названию, по возрастанию'), 
            array('Value' => '3', 'Title' => 'по названию, по убыванию'), 
            array('Value' => '4', 'Title' => 'по дате, по возрастанию'), 
            array('Value' => '5', 'Title' => 'по дате, по убыванию'), 
            array('Value' => '6', 'Title' => 'по URL, по возрастанию'), 
            array('Value' => '7', 'Title' => 'по URL, по убыванию'), 
            array('Value' => '8', 'Title' => 'по просмотрам, по возрастанию'), 
            array('Value' => '9', 'Title' => 'по просмотрам, по убыванию'), 
        );
    }
}
