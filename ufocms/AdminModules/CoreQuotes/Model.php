<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\CoreQuotes;

/**
 * Core quots mechanism model class
 */
class Model extends \Ufocms\AdminModules\Model
{
    /**
     * @see parent
     */
    protected function init()
    {
        parent::init();
        $this->itemsTable = 'quotes';
        $this->itemIdField = 'id';
        $this->itemDisabledField = 'disabled';
        $this->primaryFilter = '';
        $this->defaultSort = 'id DESC';
    }
    
    protected function setFields()
    {
        $this->fields = array(
            array('Type' => 'int',          'Name' => 'id',         'Value' => 0,       'Title' => 'id',            'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => false),
            array('Type' => 'list',         'Name' => 'groupid',    'Value' => 0,       'Title' => 'Группа',        'Filter' => true,   'Show' => true,     'Sort' => true,     'Edit' => true,     'Required' => true, 'Items' => 'getGroups'),
            array('Type' => 'bigtext',      'Name' => 'quote',      'Value' => '',      'Title' => 'Цитата',        'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true,     'Raw' => true),
            array('Type' => 'bool',         'Name' => 'disabled',   'Value' => false,   'Title' => 'Отключена',     'Filter' => true,   'Show' => true,     'Sort' => true,     'Edit' => true),
        );
    }
    
    public function getGroups()
    {
        $sql =  'SELECT id AS Value, title AS Title' . 
				' FROM ' . C_DB_TABLE_PREFIX . 'quotes_groups' . 
				' ORDER BY title';
        return $this->db->getItems($sql);
    }
}
