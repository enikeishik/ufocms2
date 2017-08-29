<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\News2;

/**
 * News module model class
 */
class ModelSettings extends \Ufocms\AdminModules\Model
{
    /**
     * @see parent
     */
    protected function init()
    {
        parent::init();
        $this->itemsTable = 'news2_sections';
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
            array('Type' => 'list',         'Name' => 'Orderby',            'Value' => 0,       'Title' => 'Сортировка',                'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true,     'Items' => 'getOrders'),
            array('Type' => 'text',         'Name' => 'IconAttributes',     'Value' => '',      'Title' => 'Атрибуты картинки анонса',  'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'text',         'Name' => 'InsIconAttributes',  'Value' => '',      'Title' => 'Атрибуты картинки вставки', 'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'int',          'Name' => 'AnnounceLength',     'Value' => 0,       'Title' => 'Размер анонса',             'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'int',          'Name' => 'PageLength',         'Value' => 0,       'Title' => 'Записей на страницу',       'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'int',          'Name' => 'RssOutCount',        'Value' => 0,       'Title' => 'Записей в RSS',             'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'int',          'Name' => 'RssExpireOffset',    'Value' => 0,       'Title' => 'RSS expire',                'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            /* deprecated array('Type' => 'int',          'Name' => 'TimerOffset',        'Value' => 0,       'Title' => 'Отсрочка публикации',       'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true), */
            array('Type' => 'bool',         'Name' => 'IsPublic',           'Value' => false,   'Title' => 'Публикация посетителями',   'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'int',          'Name' => 'ThumbnailWidth',     'Value' => 0,       'Title' => 'Ширина уменьш. картинки',   'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'int',          'Name' => 'ThumbnailHeight',    'Value' => 0,       'Title' => 'Высота уменьш. картинки',   'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'int',          'Name' => 'ImageMaxWidth',      'Value' => 0,       'Title' => 'Макс. ширина картинки',     'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'int',          'Name' => 'ImageMaxHeight',     'Value' => 0,       'Title' => 'Макс. высота картинки',     'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'int',          'Name' => 'ImageMaxSize',       'Value' => 0,       'Title' => 'Макс. объем картинки',      'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'text',         'Name' => 'ImageTypes',         'Value' => '',      'Title' => 'Допустимые типы картинок',  'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'text',         'Name' => 'UploadDir',          'Value' => '',      'Title' => 'Папка загрузки картинок',   'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'bool',         'Name' => 'IsModerated',        'Value' => false,   'Title' => 'Премодерирование',          'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'bool',         'Name' => 'IsGlobalAE',         'Value' => false,   'Title' => 'Исп. глобальный Email',     'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'text',         'Name' => 'AlertEmail',         'Value' => '',      'Title' => 'Email для уведомлений',     'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'text',         'Name' => 'AlertEmailSubj',     'Value' => '',      'Title' => 'Тема уведомления',          'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'mediumtext',   'Name' => 'AlertEmailBody',     'Value' => '',      'Title' => 'Текст уведомления',         'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
        );
    }
    
    protected function getOrders()
    {
        return array();
    }
}
