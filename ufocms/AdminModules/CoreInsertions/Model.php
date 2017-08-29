<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\CoreInsertions;

/**
 * Core insertions mechanism model class
 */
class Model extends \Ufocms\AdminModules\Model
{
    /**
     * @see parent    
     */
    protected function init()
    {
        parent::init();
        $this->itemsTable = 'insertions';
        $this->itemIdField = 'Id';
        $this->itemDisabledField = '';
        $this->primaryFilter = 'TargetId=' . (!is_null($this->params->sectionId) ? $this->params->sectionId : 0);
        $this->defaultSort = 'PlaceId, OrderId';
    }
    
    protected function setFields()
    {
        $targetId = !is_null($this->params->sectionId) ? $this->params->sectionId : 0;
        $this->fields = array(
            array('Type' => 'int',          'Name' => 'Id',             'Value' => 0,           'Title' => 'id',                'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => false),
            array('Type' => 'list',         'Name' => 'TargetId',       'Value' => $targetId,   'Title' => 'Раздел вывода',     'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => true,     'Required' => true, 'Items' => 'getSections'),
            array('Type' => 'int',          'Name' => 'PlaceId',        'Value' => 0,           'Title' => 'Место вывода',      'Filter' => true,   'Show' => true,     'Sort' => true,     'Edit' => true,     'Required' => true),
            array('Type' => 'int',          'Name' => 'OrderId',        'Value' => 0,           'Title' => 'Порядок вывода',    'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => true,     'Required' => true),
            array('Type' => 'list',         'Name' => 'SourceId',       'Value' => 0,           'Title' => 'Источник',          'Filter' => true,   'Show' => true,     'Sort' => true,     'Edit' => true,     'Required' => true, 'Items' => 'getSections'),
            array('Type' => 'text',         'Name' => 'SourcesIds',     'Value' => '',          'Title' => 'Источники (,)',     'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'text',         'Name' => 'Title',          'Value' => '',          'Title' => 'Заголовок',         'Filter' => true,   'Show' => true,     'Sort' => true,     'Edit' => true,     'Required' => true),
            array('Type' => 'text',         'Name' => 'ItemsIds',       'Value' => '',          'Title' => 'Элементы (,)',      'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'int',          'Name' => 'ItemsStart',     'Value' => '',          'Title' => 'Начать с элемента', 'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => true),
            array('Type' => 'int',          'Name' => 'ItemsCount',     'Value' => '',          'Title' => 'Вывести элементов', 'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => true,     'Required' => true),
            array('Type' => 'int',          'Name' => 'ItemsLength',    'Value' => '',          'Title' => 'Вывести символов',  'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => true,     'Required' => true),
            array('Type' => 'text',         'Name' => 'ItemsStartMark', 'Value' => '',          'Title' => 'Отметка начала',    'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => true),
            array('Type' => 'text',         'Name' => 'ItemsStopMark',  'Value' => '',          'Title' => 'Отметка окончания', 'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => true),
            array('Type' => 'text',         'Name' => 'ItemsOptions',   'Value' => '',          'Title' => 'Доп. опции',        'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => true),
        );
    }
    
    /**
     * Get field items by demand
     * @param string|array $field
     * @return array|null
     */
    public function getFieldItems($field)
    {
        $items = parent::getFieldItems($field);
        if ('TargetId' == $field['Name']) {
            $items = array_merge(
                array(
                    array('Value' => 0, 'Title' => 'Все разделы'), 
                    array('Value' => -1, 'Title' => 'Главная страница'), 
                ), 
                $items
            );
        }
        return $items;
    }
    
    public function getSections($nc = false)
    {
        $items = $this->core->getSections();
        foreach ($items as &$item) {
            $item = array('Value' => $item['id'], 'Title' => str_pad('', $item['levelid'] * 4, '.', STR_PAD_LEFT) . $item['indic']);
        }
        unset($item);
        return $items;
    }
}
