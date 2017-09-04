<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\Faq;

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
    
    protected function setItems()
    {
        $section = $this->core->getSection();
        $sectionPath = $section['path'];
        unset($section);
        parent::setItems();
        foreach ($this->items as &$item) {
            $item['path'] = $sectionPath . $item['Id'];
        }
        unset($item);
    }
    
    protected function setFields()
    {
        $this->fields = array(
            array('Type' => 'int',          'Name' => 'Id',             'Value' => 0,       'Title' => 'id',            'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => false),
            array('Type' => 'list',         'Name' => 'SectionId',      'Value' => 0,       'Title' => 'Раздел',        'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => true,     'Required' => true, 'Items' => 'getSections'),
            array('Type' => 'datetime',     'Name' => 'DateCreate',     'Value' => '',      'Title' => 'Дата вопроса',  'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => true,     'Required' => true),
            array('Type' => 'datetime',     'Name' => 'DateAnswer',     'Value' => '',      'Title' => 'Дата ответа',   'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => true),
            array('Type' => 'bool',         'Name' => 'IsHidden',       'Value' => false,   'Title' => 'Скрыто',        'Filter' => true,   'Show' => false,    'Sort' => false,    'Edit' => true,     'Items' => array(array('Value' => 1, 'Title' => 'Скрыто'), array('Value' => 0, 'Title' => 'Открыто'))),
            array('Type' => 'text',         'Name' => 'UIP',            'Value' => '',      'Title' => 'IP',            'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => false),
            array('Type' => 'text',         'Name' => 'USign',          'Value' => '',      'Title' => 'Автор вопроса', 'Filter' => true,   'Show' => true,     'Sort' => true,     'Edit' => true),
            array('Type' => 'text',         'Name' => 'UEmail',         'Value' => '',      'Title' => 'Email',         'Filter' => true,   'Show' => true,     'Sort' => true,     'Edit' => true),
            array('Type' => 'text',         'Name' => 'UUrl',           'Value' => '',      'Title' => 'WWW',           'Filter' => true,   'Show' => true,     'Sort' => true,     'Edit' => true),
            array('Type' => 'bigtext',      'Name' => 'UMessage',       'Value' => '',      'Title' => 'Вопрос',        'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true,     'Raw' => true),
            array('Type' => 'text',         'Name' => 'AIP',            'Value' => '',      'Title' => 'IP',            'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => false),
            array('Type' => 'text',         'Name' => 'ASign',          'Value' => '',      'Title' => 'Автор ответа',  'Filter' => true,   'Show' => true,     'Sort' => true,     'Edit' => true),
            array('Type' => 'text',         'Name' => 'AEmail',         'Value' => '',      'Title' => 'Email',         'Filter' => true,   'Show' => true,     'Sort' => true,     'Edit' => true),
            array('Type' => 'text',         'Name' => 'AUrl',           'Value' => '',      'Title' => 'WWW',           'Filter' => true,   'Show' => true,     'Sort' => true,     'Edit' => true),
            array('Type' => 'bigtext',      'Name' => 'AMessage',       'Value' => '',      'Title' => 'Ответ',         'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true,     'Raw' => true),
        );
    }
}
