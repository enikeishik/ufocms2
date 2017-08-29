<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\Mainpage;

/**
 * Mainpage module model class
 */
class Model extends \Ufocms\AdminModules\Model
{
    protected function init()
    {
        parent::init();
        $this->itemIdField = 'id';
        $this->itemDisabledField = '';
        $this->defaultSort = '';
        $this->canCreateItems = false;
        $this->canDeleteItems = false;
        $this->params->itemId = 1;
    }
    
    protected function setFields() {
        $this->fields = array(
            array('Type' => 'int',      'Name' => 'id',     'Value' => 0,   'Title' => 'id',        'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => false),
            array('Type' => 'bigtext',  'Name' => 'body',   'Value' => '',  'Title' => 'Текст',     'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => true),
        );
    }
}
