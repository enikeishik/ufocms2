<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\Documents;

/**
 * Documents module model class
 */
class Model extends \Ufocms\AdminModules\Model
{
    protected function init()
    {
        parent::init();
        $this->itemIdField = 'Id';
        $this->itemDisabledField = '';
        $this->defaultSort = '';
        $this->canCreateItems = false;
        $this->canDeleteItems = false;
        $this->params->itemId = $this->getItemIdBySectionId();
    }
    
    protected function setFields() {
        $this->fields = array(
            array('Type' => 'int',      'Name' => 'Id',         'Value' => 0,   'Title' => 'id',            'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => false),
            /* deprecated array('Type' => 'int',      'Name' => 'PageId',     'Value' => 0,   'Title' => 'Страница',      'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => true), */
            array('Type' => 'bigtext',  'Name' => 'Body',       'Value' => '',  'Title' => 'Текст',         'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => true),
        );
    }
}
