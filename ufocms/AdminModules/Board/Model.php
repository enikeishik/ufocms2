<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\Board;

/**
 * Module model class
 */
class Model extends \Ufocms\AdminModules\Model
{
    protected function init()
    {
        parent::init();
        $this->itemDisabledField = 'IsHidden';
        $this->defaultSort = 'DateCreate DESC';
        $this->canCreateItems = false;
    }
    
    protected function setFields()
    {
        $this->fields = array(
            array('Type' => 'int',          'Name' => 'Id',         'Value' => 0,       'Title' => 'id',            'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => false),
            array('Type' => 'list',         'Name' => 'SectionId',  'Value' => 0,       'Title' => 'Раздел',        'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => true,     'Required' => true, 'Items' => 'getSections'),
            array('Type' => 'datetime',     'Name' => 'DateCreate', 'Value' => '',      'Title' => 'Дата создания', 'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => true,     'Required' => true),
            array('Type' => 'bool',         'Name' => 'IsHidden',   'Value' => false,   'Title' => 'Скрыто',        'Filter' => true,   'Show' => false,    'Sort' => false,    'Edit' => true,     'Items' => array(array('Value' => 1, 'Title' => 'Скрыто'), array('Value' => 0, 'Title' => 'Открыто'))),
            array('Type' => 'int',          'Name' => 'ViewedCnt',  'Value' => 0,       'Title' => 'Просмотры',     'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => false),
            array('Type' => 'text',         'Name' => 'IP',         'Value' => '',      'Title' => 'IP',            'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => false),
            array('Type' => 'text',         'Name' => 'Title',      'Value' => '',      'Title' => 'Заголовок',     'Filter' => true,   'Show' => true,     'Sort' => true,     'Edit' => true,     'Required' => true),
            array('Type' => 'bigtext',      'Name' => 'Message',    'Value' => '',      'Title' => 'Сообщение',     'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true,     'Raw' => true),
            array('Type' => 'bigtext',      'Name' => 'Contacts',   'Value' => '',      'Title' => 'Контакты',      'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true,     'Raw' => true),
        );
    }
    
    protected function setItems()
    {
        parent::setItems();
        $section = $this->core->getSection();
        $sectionPath = $section['path'];
        unset($section);
        foreach ($this->items as &$item) {
            $item['path'] = $sectionPath . $item['Id'];
        }
        unset($item);
    }
    
    /**
     * @see parent
     */
    protected function getItemSqlUpdate(array $data)
    {
        foreach ($data as $name => &$field) {
            if ('bigtext' == $field['Type']) {
                $field['Value'] = nl2br($field['Value'], false);
            }
        }
        unset($field);
        return parent::getItemSqlUpdate($data);
    }
}
