<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\CoreQuotes;

/**
 * Core insertions mechanism model class
 */
class ModelGroups extends \Ufocms\AdminModules\Model
{
    protected function init()
    {
        parent::init();
        $this->itemsTable = 'quotes_groups';
        $this->itemIdField = 'id';
        $this->itemDisabledField = '';
        $this->primaryFilter = '';
        $this->defaultSort = 'title';
    }
    
    protected function setFields()
    {
        $this->fields = array(
            array('Type' => 'int',      'Name' => 'id',     'Value' => 0,   'Title' => 'id',        'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => false),
            array('Type' => 'text',     'Name' => 'title',  'Value' => '',  'Title' => 'Название',  'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => true,     'Required' => true),
        );
    }
    
    protected function delGroupQuotes($groupId)
    {
        $sql =  'DELETE FROM ' . C_DB_TABLE_PREFIX . 'quotes' . 
                ' WHERE groupid=' . $groupId;
        return $this->db->query($sql);
    }
    
    public function delete()
    {
        if ($this->delGroupQuotes($this->params->itemId)) {
            return parent::delete();
        } else {
            $this->result = 'DB error: ' . $this->db->getError();
            return false;
        }
    }
}
