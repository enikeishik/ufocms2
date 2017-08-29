<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\Congrats;

/**
 * Module model class
 */
class Model extends \Ufocms\AdminModules\Model
{
    protected function init()
    {
        parent::init();
        $this->itemsTable = 'congrats_items';
        $this->defaultSort = 'DateCreate DESC';
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
            array('Type' => 'int',          'Name' => 'Id',             'Value' => 0,                           'Title' => 'id',                'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => false),
            array('Type' => 'list',         'Name' => 'SectionId',      'Value' => $this->params->sectionId,    'Title' => 'Раздел',            'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => true,     'Required' => true,     'Items' => 'getSections'),
            array('Type' => 'datetime',     'Name' => 'DateCreate',     'Value' => date('Y-m-d H:i:s'),         'Title' => 'Дата создания',     'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => true,     'Required' => true,     'Class' => 'small'),
            array('Type' => 'datetime',     'Name' => 'DateStart',      'Value' => date('Y-m-d H:i:s'),         'Title' => 'Дата публикации',   'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => true,     'Required' => true,     'Class' => 'small'),
            array('Type' => 'datetime',     'Name' => 'DateStop',       'Value' => $this->getStopDate(),        'Title' => 'Дата окончания',    'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => true,     'Required' => true,     'Class' => 'small'),
            array('Type' => 'bool',         'Name' => 'IsDisabled',     'Value' => false,                       'Title' => 'Отключено',         'Filter' => true,   'Show' => false,    'Sort' => false,    'Edit' => true,     'Items' => array(array('Value' => 1, 'Title' => 'Скрыто'), array('Value' => 0, 'Title' => 'Открыто'))),
            array('Type' => 'bool',         'Name' => 'IsEternal',      'Value' => false,                       'Title' => 'Без окончания',     'Filter' => true,   'Show' => false,    'Sort' => false,    'Edit' => true,     'Items' => array(array('Value' => 1, 'Title' => 'Без окончания'), array('Value' => 0, 'Title' => 'Обычное'))),
            array('Type' => 'bool',         'Name' => 'IsPinned',       'Value' => false,                       'Title' => 'Прикреплено',       'Filter' => true,   'Show' => false,    'Sort' => false,    'Edit' => true,     'Items' => array(array('Value' => 1, 'Title' => 'Прикреплено'), array('Value' => 0, 'Title' => 'Обычное'))),
            array('Type' => 'bool',         'Name' => 'IsHighlighted',  'Value' => false,                       'Title' => 'Подсвечено',        'Filter' => true,   'Show' => false,    'Sort' => false,    'Edit' => true,     'Items' => array(array('Value' => 1, 'Title' => 'Подсвечено'), array('Value' => 0, 'Title' => 'Обычное'))),
            array('Type' => 'int',          'Name' => 'ViewedCnt',      'Value' => 0,                           'Title' => 'Просмотров',        'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => false),
            array('Type' => 'image',        'Name' => 'Thumbnail',      'Value' => '',                          'Title' => 'Картинка мал.',     'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => true),
            array('Type' => 'image',        'Name' => 'Image',          'Value' => '',                          'Title' => 'Картинка бол.',     'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => true),
            array('Type' => 'mediumtext',   'Name' => 'ShortDesc',      'Value' => '',                          'Title' => 'Кратк. текст',      'Filter' => true,   'Show' => true,     'Sort' => false,    'Edit' => true,     'Raw' => true),
            array('Type' => 'bigtext',      'Name' => 'FullDesc',       'Value' => '',                          'Title' => 'Полн. текст',       'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => true),
        );
    }
    
    protected function getStopDate()
    {
        $container = $this->core->getContainer([
            'debug'     => &$this->debug, 
            'config'    => &$this->config, 
            'params'    => &$this->params, 
            'db'        => &$this->db, 
            'core'      => &$this->core, 
        ]);
        $itemId = $this->params->itemId;
        $settings = new ModelSettings($container);
        $showDays = $settings->getItem()['ShowDays'];
        unset($settings);
        unset($container);
        $this->params->itemId = $itemId;
        return date('Y-m-d H:i:s', time() + ($showDays * 24 * 3600));
    }
    
    protected function collectFormData(array $fields, $update = false)
    {
        $data = parent::collectFormData($fields, $update);
        if ($data['IsEternal']['Value']) {
            $data['DateStop']['Value'] = '9999-12-31 23:59:59';
        }
        return $data;
    }
}
