<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\CoreComments;

/**
 * Core comments blacklist model class
 */
class ModelBlacklist extends \Ufocms\AdminModules\Model
{
    protected function init()
    {
        parent::init();
        $this->itemsTable = 'comments_blacklist';
        $this->itemIdField = 'id';
        $this->itemDisabledField = '';
        $this->primaryFilter = '';
        $this->defaultSort = 'url';
        $this->canCreateItems = false;
        $this->canUpdateItems = false;
    }
    
    protected function setFields()
    {
        $this->fields = array(
            array('Type' => 'int',      'Name' => 'id',     'Value' => 0,   'Title' => 'id',    'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => false),
            array('Type' => 'slist',    'Name' => 'url',    'Value' => '',  'Title' => 'URL',   'Filter' => true,   'Show' => true,     'Sort' => true,     'Edit' => false,    'Items' => 'getUrls'),
            array('Type' => 'text',     'Name' => 'ip',     'Value' => '',  'Title' => 'IP',    'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => false),
        );
    }
    
    protected function getUrls()
    {
        $sql =  'SELECT DISTINCT url' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'comments_blacklist' . 
                ' ORDER BY url';
        $items = $this->db->getItems($sql);
        foreach ($items as &$item) {
            $item = array('Value' => $item['url'], 'Title' => $item['url']);
        }
        unset($item);
        return $items;
    }
}
