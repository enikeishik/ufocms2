<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\Congrats;

/**
 * Module settings model class
 */
class ModelSettings extends \Ufocms\AdminModules\Model
{
    /**
     * @see parent
     */
    protected function init()
    {
        parent::init();
        $this->itemsTable = 'congrats_settings';
        $this->itemDisabledField = '';
        $this->canCreateItems = false;
        $this->canDeleteItems = false;
        $this->params->itemId = $this->getItemIdBySectionId();
    }
    
    protected function setFields()
    {
        $this->fields = array(
            array('Type' => 'int',          'Name' => 'Id',                 'Value' => 0,       'Title' => 'id',                        'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => false),
            array('Type' => 'int',          'Name' => 'SectionId',          'Value' => 0,       'Title' => 'Раздел',                    'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => false),
            array('Type' => 'bigtext',      'Name' => 'BodyHead',           'Value' => '',      'Title' => 'Текст перед',               'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'bigtext',      'Name' => 'BodyFoot',           'Value' => '',      'Title' => 'Текст после',               'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'int',          'Name' => 'ShowDays',           'Value' => 30,      'Title' => 'Показывать, дней',          'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'int',          'Name' => 'PageLength',         'Value' => 10,      'Title' => 'Записей на страницу',       'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'list',         'Name' => 'Orderby',            'Value' => 0,       'Title' => 'Сортировка',                'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true,     'Items' => 'getOrderItems'),
        );
    }
    
    /**
     * @return array
     */
    protected function getOrderItems()
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
