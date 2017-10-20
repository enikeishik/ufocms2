<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\Faq;

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
        $this->itemsTable = 'faq_sections';
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
            array('Type' => 'int',          'Name' => 'PageLength',         'Value' => 0,       'Title' => 'Записей на страницу',       'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'list',         'Name' => 'Orderby',            'Value' => 0,       'Title' => 'Сортировка',                'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true,     'Items' => 'getOrderItems'),
            array('Type' => 'bool',         'Name' => 'IsModerated',        'Value' => false,   'Title' => 'Премодерирование',          'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'bool',         'Name' => 'IsReferer',          'Value' => true,    'Title' => 'Проверять источник',        'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'bool',         'Name' => 'IsCaptcha',          'Value' => true,    'Title' => 'Использовать CAPTCHA',      'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'int',          'Name' => 'MessageMaxLen',      'Value' => 0,       'Title' => 'Макс. объем сообщения',     'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'text',         'Name' => 'AlertEmail',         'Value' => '',      'Title' => 'Email для уведомлений',     'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'text',         'Name' => 'AlertEmailSubj',     'Value' => '',      'Title' => 'Тема email уведомления',    'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'mediumtext',   'Name' => 'AlertEmailBody',     'Value' => '',      'Title' => 'Текст email уведомления',   'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'mediumtext',   'Name' => 'PostMessage',        'Value' => '',      'Title' => 'Текст успешн. уведомл.',    'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'mediumtext',   'Name' => 'PostMessageErr',     'Value' => '',      'Title' => 'Текст уведомл. ошибки',     'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'mediumtext',   'Name' => 'PostMessageBad',     'Value' => '',      'Title' => 'Текст не успешн. уведомл.', 'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
        );
    }
    
    protected function getOrderItems()
    {
        return array(
            array('Value' => 0, 'Title' => 'по дате вопроса, по убыванию'),
            array('Value' => 1, 'Title' => 'по дате ответа, по убыванию'),
            array('Value' => 2, 'Title' => 'по дате вопроса, по возрастанию'),
            array('Value' => 3, 'Title' => 'по дате ответа, по возрастанию'),
        );
    }
}
