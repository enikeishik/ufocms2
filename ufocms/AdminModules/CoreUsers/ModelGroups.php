<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\CoreUsers;

/**
 * Core users groups model class
 */
class ModelGroups extends \Ufocms\AdminModules\Model
{
    /**
     * @see parent
     */
    protected function init()
    {
        parent::init();
        $this->itemsTable = 'users_groups';
        $this->itemDisabledField = '';
        $this->primaryFilter = '';
        $this->defaultSort = 'Title';
    }
    
    protected function setFields()
    {
        $this->fields = array(
            array('Type' => 'int',      'Name' => 'Id',             'Value' => 0,   'Title' => 'id',        'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => false),
            array('Type' => 'text',     'Name' => 'Title',          'Value' => '',  'Title' => 'Название',  'Filter' => true,   'Show' => true,     'Sort' => true,     'Edit' => true),
            array('Type' => 'bigtext',  'Name' => 'Description',    'Value' => '',  'Title' => 'Описание',  'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
        );
    }
    
    /**
     * @see parent
     */
    protected function actionAfterDelete()
    {
        $sql =  'DELETE FROM ' . C_DB_TABLE_PREFIX . 'users_groups_relations' . 
                ' WHERE GroupId=' . $this->params->itemId;
        return $this->db->query($sql);
    }
}
