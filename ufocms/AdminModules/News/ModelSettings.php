<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\News;

/**
 * News module settings model class
 */
class ModelSettings extends \Ufocms\AdminModules\Model
{
    /**
     * @see parent
     */
    protected function init()
    {
        parent::init();
        $this->itemsTable = 'news_sections';
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
            array('Type' => 'text',         'Name' => 'IconAttributes',     'Value' => '',      'Title' => 'Атрибуты картинки анонса',  'Filter' => true,   'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'int',          'Name' => 'PageLength',         'Value' => 0,       'Title' => 'Записей на страницу',       'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'int',          'Name' => 'AnnounceLength',     'Value' => 0,       'Title' => 'Размер анонса',             'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'int',          'Name' => 'RssExpireOffset',    'Value' => 0,       'Title' => 'RSS expire',                'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            /* deprecated array('Type' => 'int',          'Name' => 'TimerOffset',        'Value' => 0,       'Title' => 'Отсрочка публикации',       'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true), */
            array('Type' => 'bool',         'Name' => 'IsArchive',          'Value' => false,   'Title' => 'Постраничный вывод',        'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
        );
    }
}
