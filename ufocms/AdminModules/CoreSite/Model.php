<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\CoreSite;

/**
 * Core site model class
 */
class Model extends \Ufocms\AdminModules\Model
{
    /**
     * @see parent
     */
    protected function init()
    {
        parent::init();
        $this->itemsTable = 'siteparams';
        $this->itemIdField = 'Id';
        $this->itemDisabledField = '';
        $this->primaryFilter = '';
        $this->defaultSort = 'PGroup,POrder,PName';
        $this->canDeleteItems = false;
    }
    
    protected function setFields()
    {
        $this->fields = array(
            array('Type' => 'int',      'Name' => 'Id',             'Value' => 0,       'Title' => 'id',            'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => false),
            array('Type' => 'int',      'Name' => 'POrder',         'Value' => 0,       'Title' => 'Порядок',       'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => false),
            array('Type' => 'int',      'Name' => 'PType',          'Value' => 202,     'Title' => 'Тип',           'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => false),
            array('Type' => 'combo',    'Name' => 'PGroup',         'Value' => '',      'Title' => 'Группа',        'Filter' => true,   'Show' => true,     'Sort' => true,     'Edit' => true,     'Unchange' => true,     'Items' => 'getGroups'),
            array('Type' => 'text',     'Name' => 'PName',          'Value' => '',      'Title' => 'Название',      'Filter' => true,   'Show' => true,     'Sort' => true,     'Edit' => true,     'Unchange' => true),
            array('Type' => 'text',     'Name' => 'PValue',         'Value' => '',      'Title' => 'Значение',      'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'text',     'Name' => 'PDefault',       'Value' => '',      'Title' => 'По-умолчанию',  'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true,     'Unchange' => true),
            array('Type' => 'text',     'Name' => 'PDescription',   'Value' => '',      'Title' => 'Описание',      'Filter' => true,   'Show' => true,     'Sort' => false,    'Edit' => true,     'Unchange' => true),
        );
    }
    
    protected function getGroups()
    {
        static $groups = null;
        if (null === $groups) {
            $sql =  'SELECT DISTINCT PGroup' . 
                    ' FROM ' . C_DB_TABLE_PREFIX . 'siteparams' . 
                    ' ORDER BY PGroup';
            $groups = $this->db->getItems($sql);
            foreach ($groups as &$item) {
                $item = array('Value' => $item['PGroup'], 'Title' => $item['PGroup']);
            }
            unset($item);
        }
        return $groups;
    }
    
    public function update()
    {
        if (is_null($this->params->itemId)) {
            $this->result = 'Request error: param `itemid` not set';
            return;
        }
        if (0 == $this->params->itemId) {
            foreach ($this->fields as $field) {
                if ($field['Edit'] && !isset($_POST[$field['Name']])) {
                    $this->result = 'Request error: field `' . $field['Name'] . '` not set';
                    return;
                }
            }
            $sql =  'INSERT INTO ' . C_DB_TABLE_PREFIX . 'siteparams' . 
                    ' (POrder,PType,PGroup,PName,PValue,PDefault,PDescription)' . 
                    ' VALUES(100,202,' . 
                    "'" . $this->db->addEscape($_POST['PGroup']) . "'," . 
                    "'" . $this->db->addEscape($_POST['PName']) . "'," . 
                    "'" . $this->db->addEscape($_POST['PValue']) . "'," . 
                    "'" . $this->db->addEscape($_POST['PDefault']) . "'," . 
                    "'" . $this->db->addEscape($_POST['PDescription']) . "')";
        } else {
            if (!isset($_POST['PValue'])) {
                $this->result = 'Request error: field `PValue` not set';
                return;
            }
            $sql =  'UPDATE ' . C_DB_TABLE_PREFIX . 'siteparams' . 
                    " SET PValue='" . $this->db->addEscape($_POST['PValue']) . "'" . 
                    ' WHERE Id=' . $this->params->itemId;
        }
        if ($this->db->query($sql)) {
            $this->result = 'updated';
        } else {
            $this->result = 'DB error: ' . $this->db->getError();
        }
    }
}
