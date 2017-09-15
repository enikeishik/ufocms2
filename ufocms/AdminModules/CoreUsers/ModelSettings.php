<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\CoreUsers;

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
        $this->itemsTable = 'users_params';
        $this->itemDisabledField = '';
        $this->canCreateItems = false;
        $this->canDeleteItems = false;
        $this->params->itemId = 1;
    }
    
    protected function setFields()
    {
        $this->fields = array(
            array('Type' => 'int',          'Name' => 'Id',                 'Value' => 0,       'Title' => 'id',                                    'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => false),
            array('Type' => 'bigtext',      'Name' => 'BodyHead',           'Value' => '',      'Title' => 'Текст перед',                           'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'bigtext',      'Name' => 'BodyFoot',           'Value' => '',      'Title' => 'Текст после',                           'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'int',          'Name' => 'PageLength',         'Value' => 0,       'Title' => 'Записей на страницу',                   'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'list',         'Name' => 'Orderby',            'Value' => 0,       'Title' => 'Сортировка',                            'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true,     'Items' => 'getOrderItems'),
            array('Type' => 'bool',         'Name' => 'IsModerated',        'Value' => false,   'Title' => 'Премодерирование',                      'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true,     'Info' => 'новые пользователи будут скрытыми, пока их не откроет администратор'),
            array('Type' => 'bool',         'Name' => 'IsGlobalAE',         'Value' => true,    'Title' => 'Глобальный Email администратора',       'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true,     'Info' => 'использовать глобальный Email администратора сайта'),
            array('Type' => 'bool',         'Name' => 'IsGlobalAEF',        'Value' => true,    'Title' => 'Глобальный обратный Email адм.',        'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true,     'Info' => 'использовать глобальный обратный Email администратора сайта'),
            array('Type' => 'text',         'Name' => 'AdminEmail',         'Value' => '',      'Title' => 'Email администратора',                  'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'text',         'Name' => 'AdminEmailFrom',     'Value' => '',      'Title' => 'Обратный Email администратора',         'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'text',         'Name' => 'RecoverySubject',    'Value' => '',      'Title' => 'Тема письма восстановления пароля',     'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'mediumtext',   'Name' => 'RecoveryMessage',    'Value' => '',      'Title' => 'Текст письма восстановления пароля',    'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'int',          'Name' => 'ImageWidth',         'Value' => 0,       'Title' => 'Ширина картинки',                       'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'int',          'Name' => 'ImageHeight',        'Value' => 0,       'Title' => 'Высота картинки',                       'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'int',          'Name' => 'ImageMaxSize',       'Value' => 0,       'Title' => 'Макс. размер файла картинки',           'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'text',         'Name' => 'ImageTypes',         'Value' => '',      'Title' => 'Допустимые типы файла картинки',        'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'text',         'Name' => 'UploadDir',          'Value' => '',      'Title' => 'Папка загрузки картинок',               'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
        );
    }
    
    protected function getOrderItems()
    {
        return array(
            array('Value' => 0, 'Title' => 'по названию, по возрастанию'),
            array('Value' => 1, 'Title' => 'по названию, по убыванию'),
            array('Value' => 2, 'Title' => 'по дате, по возрастанию'),
            array('Value' => 3, 'Title' => 'по дате, по убыванию'),
        );
    }
}
