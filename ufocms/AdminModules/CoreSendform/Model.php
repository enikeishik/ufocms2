<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\CoreSendform;

/**
 * Core sendform model class
 */
class Model extends \Ufocms\AdminModules\Model
{
    protected function init()
    {
        parent::init();
        $this->itemsTable = 'sendforms';
        $this->itemIdField = 'Id';
        $this->itemDisabledField = 'Status';
        $this->primaryFilter = '';
        $this->defaultSort = 'DateCreate DESC';
        $this->canCreateItems = false;
        $this->canUpdateItems = false;
        $this->config->registerAction('remove');
        $this->config->registerMakeAction('remove');
        $this->params->actionUnsafe = false;
    }
    
    /**
     * @see parent
     */
    protected function getItemsSqlCondition($table = '')
    {
        $sql = '';
        if (!is_null($this->params->filterName) 
        && !is_null($this->params->filterValue) 
        && !is_null($field = $this->getField($this->params->filterName))) {
            $sql =  ' WHERE ' . 
                    $this->getSqlFieldPrefix($table);
            if ('Status' == $this->params->filterName) {
                switch ($this->params->filterValue) {
                    case '-1':
                        $sql = '';
                        break;
                    case '0':
                    case '1':
                    case '2':
                        $sql .= '`Status`=' . $this->params->filterValue;
                        break;
                    default:
                        $sql .= '`Status`<2';
                }
            } else {
                switch ($field['Type']) {
                    case 'int':
                    case 'list':
                        if ('' != $this->params->filterValue) {
                            $sql .= '`' . $this->params->filterName . '`=' . (int) $this->params->filterValue;
                        } else {
                            $sql = '';
                        }
                        break;
                    case 'bool':
                        $sql .= '`' . $this->params->filterName . '`=' . (int) $this->params->filterValue;
                        break;
                    case 'slist':
                        $sql .= '`' . $this->params->filterName . "`='" . $this->db->addEscape($this->params->filterValue) . "'";
                        break;
                    case 'text':
                    case 'mediumtext':
                    case 'bigtext':
                    case 'combo':
                    default:
                        $sql .= '`' . $this->params->filterName . "` LIKE '%" . $this->db->addEscape($this->params->filterValue) . "%'";
                }
            }
        } else {
            $sql = ' WHERE `Status`<2';
            $this->params->filterName = 'Status';
            $this->params->filterValue = -2;
        }
        return $sql;
    }
    
    protected function setFields()
    {
        $this->fields = array(
            array('Type' => 'int',          'Name' => 'Id',             'Value' => 0,   'Title' => 'id',            'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => false),
            array('Type' => 'datetime',     'Name' => 'DateCreate',     'Value' => '',  'Title' => 'Дата/время',    'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => false),
            array('Type' => 'list',         'Name' => 'Status',         'Value' => 0,   'Title' => 'Статус',        'Filter' => true,   'Show' => true,     'Sort' => true,     'Edit' => true,     'Items' => 'getStatuses',    'ItemsDefault' => true),
            array('Type' => 'slist',        'Name' => 'Url',            'Value' => '',  'Title' => 'URL',           'Filter' => true,   'Show' => true,     'Sort' => true,     'Edit' => false,    'Items' => 'getUrls'),
            array('Type' => 'text',         'Name' => 'IP',             'Value' => '',  'Title' => 'IP',            'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => false),
            array('Type' => 'bigtext',      'Name' => 'Form',           'Value' => '',  'Title' => 'Форма',         'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => false,    'Raw' => true),
        );
    }
    
    protected function getStatuses()
    {
        return array(
            array('Value' => -2, 'Title' => 'Все, кроме прочитанных'),
            array('Value' => 0,  'Title' => 'Не доставленные по email'),
            array('Value' => 1,  'Title' => 'Доставленные по email'),
            array('Value' => 2,  'Title' => 'Со статусом «Прочитано»'),
            array('Value' => -1, 'Title' => 'Все статусы'),
        );
    }
    
    protected function getUrls()
    {
        $sql =  'SELECT DISTINCT Url' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'sendforms' . 
                ' ORDER BY Url';
        $items = $this->db->getItems($sql);
        foreach ($items as &$item) {
            $item = array('Value' => $item['Url'], 'Title' => $item['Url']);
        }
        unset($item);
        return $items;
    }
    
    /**
     * @todo: make implementation
     */
    public function remove()
    {
        $this->disable();
        $this->result = 'removed';
    }
    
    /**
     * @see parent
     */
    protected function getItemSqlDisable()
    {
        return  'UPDATE ' . C_DB_TABLE_PREFIX . 'sendforms' . 
                ' SET Status=2' . 
                ' WHERE Id=' . $this->params->itemId;
    }
    
    /**
     * @see parent
     */
    protected function getItemSqlEnable()
    {
        return  'UPDATE ' . C_DB_TABLE_PREFIX . 'sendforms' . 
                ' SET Status=1' . 
                ' WHERE Id=' . $this->params->itemId;
    }
}
