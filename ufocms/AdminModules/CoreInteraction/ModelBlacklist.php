<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\CoreInteraction;

/**
 * Core sendform model class
 */
class ModelBlacklist extends \Ufocms\AdminModules\Model
{
    /**
     * @see parent
     */
    protected function init()
    {
        parent::init();
        $this->itemsTable = 'interaction_blacklist';
        $this->itemIdField = 'Id';
        $this->itemDisabledField = '';
        $this->primaryFilter = '';
        $this->defaultSort = 'DateCreate DESC';
        $this->canCreateItems = false;
        $this->canUpdateItems = false;
    }
    
    protected function setFields()
    {
        $this->fields = array(
            array('Type' => 'int',          'Name' => 'Id',             'Value' => 0,   'Title' => 'id',            'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => false),
            array('Type' => 'datetime',     'Name' => 'DateCreate',     'Value' => '',  'Title' => 'Дата/время',    'Filter' => true,   'Show' => true,     'Sort' => true,     'Edit' => false),
            array('Type' => 'text',         'Name' => 'IP',             'Value' => '',  'Title' => 'IP',            'Filter' => true,   'Show' => true,     'Sort' => true,     'Edit' => false),
        );
    }
}
