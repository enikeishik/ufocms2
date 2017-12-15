<?php
/**
 * UFOCMS v2 Content Management System
 * 
 * @copyright   Copyright (C) 2005 - 2017 Enikeishik <enikeishik@gmail.com>. All rights reserved.
 * @author      Enikeishik <enikeishik@gmail.com>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Ufocms\AdminModules;

/**
 * Base model class
 */
class Model extends Schema
{
    /**
     * @var \Ufocms\Frontend\Debug
     */
    protected $debug = null;
    
    /**
     * Ссылка на объект конфигурации.
     * @var \Ufocms\Backend\Config
     */
    protected $config = null;
    
    /**
     * @var \Ufocms\Backend\Params
     */
    protected $params = null;
    
    /**
     * @var \Ufocms\Backend\Db
     */
    protected $db = null;
    
    /**
     * @var \Ufocms\Backend\Core
     */
    protected $core = null;
    
    /**
     * @var array
     */
    protected $module = null;
    
    /**
     * Availabe module level parameters
     * @var array
     */
    protected $moduleParams = null;
    
    /**
     * @var \Ufocms\Frontend\Tools
     */
    protected $tools = null;
    
    /**
     * @var Model
     */
    protected $master = null;
    
    /**
     * @var string
     */
    protected $itemsTable = null;
    
    /**
     * @var string
     */
    protected $itemIdField = null;
    
    /**
     * @var string
     */
    protected $itemDisabledField = null;
    
    /**
     * @var bool
     */
    protected $itemDisabledFieldInvert = null;
    
    /**
     * @var string
     */
    protected $primaryFilter = null;
    
    /**
     * @var string
     */
    protected $defaultSort = null;
    
    /**
     * @var array
     */
    protected $items = null;
    
    /**
     * @var int
     */
    protected $itemsCount = null;
    
    /**
     * @var array
     */
    protected $sections = null;
    
    /**
     * @var mixed
     */
    protected $result = null;
    
    /**
     * @var bool
     */
    protected $canCreateItems = true;
    
    /**
     * @var bool
     */
    protected $canUpdateItems = true;
    
    /**
     * @var bool
     */
    protected $canDeleteItems = true;
    
    /**
     * @var int
     */
    protected $lastInsertedId = null;
    
    /**
     * Распаковка контейнера.
     */
    protected function unpackContainer()
    {
        $this->debug =& $this->container->getRef('debug');
        $this->config =& $this->container->getRef('config');
        $this->params =& $this->container->getRef('params');
        $this->db =& $this->container->getRef('db');
        $this->core =& $this->container->getRef('core');
        $this->tools =& $this->container->getRef('tools');
        $this->module =& $this->container->getRef('module');
        $this->moduleParams =& $this->container->getRef('moduleParams');
    }
    
    /**
     * Инициализация объекта. Переобпределяется в потомках по необходимости.
     */
    protected function init()
    {
        $this->itemsTable = strtolower($this->module['Module']);
        $this->itemIdField = 'Id';
        $this->itemDisabledField = 'IsDisabled';
        $this->itemDisabledFieldInvert = false;
        if (0 != $this->params->sectionId) {
            $this->primaryFilter = 'SectionId=' . $this->params->sectionId;
        } else {
            $this->primaryFilter = '';
        }
        $this->defaultSort = '';
        parent::init();
    }
    
    /**
     * Init $fields var
     */
    protected function setFields()
    {
        $this->fields = array(
            array('Type' => 'int',      'Name' => $this->itemIdField,           'Value' => 0,       'Title' => 'id',       'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => false),
            array('Type' => 'bool',     'Name' => $this->itemDisabledField,     'Value' => false,   'Title' => 'Отключен', 'Filter' => true,   'Show' => true,     'Sort' => true,     'Edit' => true),
        );
    }
    
    /**
     * Get model of master (when this model is slave).
     * @return Model
     */
    public function getMaster()
    {
        return $this->master;
    }
    
    /**
     * Get field model (when field contains link to structured external data) by demand.
     * @param string|array $field
     * @param mixed $value = null
     * @return object|null
     */
    public function getFieldModel($field, $value = null)
    {
        return $this->getFieldMethodStoredResult($field, 'Model', $value);
    }
    
    /**
     * Get field schema (when field value contains structured data itself, as JSON for example, and schema is external) by demand.
     * @param string|array $field
     * @return array|null
     */
    public function getFieldSchema($field)
    {
        return $this->getFieldMethodStoredResult($field, 'Schema');
    }
    
    /**
     * Get field items (when field value is item of list) by demand.
     * @param string|array $field
     * @return array|null
     */
    public function getFieldItems($field)
    {
        return $this->getFieldMethodStoredResult($field, 'Items');
    }
    
    /**
     * Get value for external field (field contains data in another table).
     * @param string|array $field
     * @return mixed
     */
    public function getItemExternalFieldValue($field)
    {
        //not store result, because this method calls once for edit form.
        return $this->getFieldMethodResult($field, 'Value', $this->params->itemId);
    }
    
    /**
     * @param string $prefix
     * @return string
     */
    protected function getSqlFieldPrefix($prefix)
    {
        if ('' == $prefix) {
            return '';
        }
        $escaped = false !== strpos($prefix, '`');
        $dotend = strrpos($prefix, '.') == (strlen($prefix) - 1);
        if ($escaped && $dotend) {
            return $prefix;
        } else if ($escaped) {
            return $prefix . '.';
        } else if ($dotend) {
            return '`' . substr($prefix, 0, -1) . '`.';
        } else {
            return '`' . $prefix . '`.';
        }
    }
    
    /**
     * @param string $alias = ''
     * @param string $addSql = ''
     * @return string
     */
    protected function getItemsSqlBase($alias = '', $addSql = '')
    {
        return  ' FROM `' . C_DB_TABLE_PREFIX . $this->itemsTable . '`' . 
                ('' != $alias ? ' AS ' . $alias : '') . 
                $addSql;
    }
    
    /**
     * @param string $name
     * @param mixed $value
     * @return string
     */
    protected function getSqlConditionInt($name, $value)
    {
        return '`' . $name . '`=' . (int) $value;
    }
    
    /**
     * @param string $name
     * @param mixed $value
     * @return string
     */
    protected function getSqlConditionBool($name, $value)
    {
        return '`' . $name . '`=' . (int) (bool) $value;
    }
    
    /**
     * @param string $name
     * @param mixed $value
     * @return string
     */
    protected function getSqlConditionSlist($name, $value)
    {
        return '`' . $name . "`='" . $this->db->addEscape($value) . "'";
    }
    
    /**
     * @param string $name
     * @param mixed $value
     * @return string
     */
    protected function getSqlConditionText($name, $value)
    {
        return '`' . $name . "` LIKE '%" . $this->db->addEscape($value) . "%'";
    }
    
    /**
     * @param string $table = ''
     * @return string
     */
    protected function getItemsSqlCondition($table = '')
    {
        $sql = '';
        if (!is_null($this->params->filterName) 
        && !is_null($this->params->filterValue) 
        && !is_null($field = $this->getField($this->params->filterName))) {
            $sql =  ' WHERE ' . 
                    ('' != $this->primaryFilter ? $this->primaryFilter . ' AND ' : '') . 
                    $this->getSqlFieldPrefix($table);
            switch ($field['Type']) {
                case 'int':
                case 'list':
                case 'mlist':
                    if ('' != $this->params->filterValue) {
                        $sql .= $this->getSqlConditionInt($this->params->filterName, $this->params->filterValue);
                    } else {
                        $sql = ('' != $this->primaryFilter ? substr($sql, 0, -5) : '');
                    }
                    break;
                case 'bool':
                    if ('' != $this->params->filterValue) {
                        $sql .= $this->getSqlConditionBool($this->params->filterName, $this->params->filterValue);
                    } else {
                        $sql = ('' != $this->primaryFilter ? substr($sql, 0, -5) : '');
                    }
                    break;
                case 'slist':
                    $sql .= $this->getSqlConditionSlist($this->params->filterName, $this->params->filterValue);
                    break;
                case 'text':
                case 'mediumtext':
                case 'bigtext':
                case 'combo':
                default:
                    $method = 'getSqlCondition' . ucfirst($field['Type']);
                    if (method_exists($this, $method)) {
                        $sql .= $this->$method($this->params->filterName, $this->params->filterValue);
                    } else {
                        $sql .= $this->getSqlConditionText($this->params->filterName, $this->params->filterValue);
                    }
            }
        } else if ('' != $this->primaryFilter) {
            $sql = ' WHERE ' . $this->primaryFilter;
        }
        return $sql;
    }
    
    /**
     * @param string $table = ''
     * @return string
     */
    protected function getItemsSqlSorting($table = '')
    {
        $sql = $this->defaultSort;
        if (!is_null($this->params->sortField) 
        && !is_null($field = $this->getField($this->params->sortField))) {
            $sql = $this->getSqlFieldPrefix($table) . '`' . $field['Name'] . '`';
            if (!is_null($this->params->sortDirection) 
            && 0 == strcasecmp('DESC', $this->params->sortDirection)) {
                $sql .= ' DESC';
            }
        }
        return '' != $sql ? ' ORDER BY ' . $sql : '';
    }
    
    /**
     * @return string
     */
    protected function getItemsSqlLimits()
    {
        return  ' LIMIT ' . (($this->params->page - 1) * $this->params->pageSize) . 
                ', ' . $this->params->pageSize;
    }
    
    /**
     * @param string $table = ''
     * @return string
     */
    protected function getItemsSqlFields($table = '')
    {
        $table = $this->getSqlFieldPrefix($table);
        $fields = '';
        foreach ($this->fields as $field) {
            if ($field['Show'] 
            && (!array_key_exists('External', $field) || !$field['External'])) {
                $fields .= $table . '`' . $field['Name'] . '`,';
            }
        }
        $fields .= $table . '`' . $this->itemIdField . '` AS itemid';
        if ('' != $this->itemDisabledField) {
            $fields .=  ',' . ($this->itemDisabledFieldInvert ? ' NOT ' : '') . 
                        $table . '`' . $this->itemDisabledField . '` AS disabled';
        }
        return  $fields;
    }
    
    /**
     * Init $items and $itemsCount vars.
     */
    protected function setItems()
    {
        $sql = $this->getItemsSqlBase() . $this->getItemsSqlCondition();
        $this->itemsCount = $this->db->getValue('SELECT COUNT(*) AS Cnt' . $sql, 'Cnt');
        if (0 == $this->itemsCount) {
            $this->items = array();
            return;
        }
        
        $sql =  'SELECT ' . $this->getItemsSqlFields() . 
                $sql . 
                $this->getItemsSqlSorting() . 
                $this->getItemsSqlLimits();
        $this->items = $this->db->getItems($sql);
        if (null === $this->items) {
            $this->items = array();
        }
    }
    
    /**
     * @return array
     */
    public function getItems()
    {
        if (is_null($this->items)) {
            $this->setItems();
        }
        return $this->items;
    }
    
    /**
     * @return int
     */
    public function getItemsCount()
    {
        if (is_null($this->itemsCount)) {
            $this->setItems();
        }
        return $this->itemsCount;
    }
    
    /**
     * Use only for settings to retrieve DB Id by current SectionId.
     * @return int
     */
    protected function getItemIdBySectionId()
    {
        $sql =  'SELECT `' . $this->itemIdField . '`' . 
                ' FROM `' . C_DB_TABLE_PREFIX . $this->itemsTable . '`' . 
                ' WHERE SectionId=' . $this->params->sectionId;
        return $this->db->getValue($sql, $this->itemIdField);
    }
    
    /**
     * @return array
     */
    public function getItem()
    {
        static $item = null;
        if (null === $item) {
            if (0 != $this->params->itemId) {
                $fields = array_keys($this->getFieldsValues());
                if (0 == count($fields)) {
                    return array();
                }
                $fieldsExtra = '';
                if ('' != $this->itemIdField && in_array($this->itemIdField, $fields)) {
                    $fieldsExtra .= ',`' . $this->itemIdField . '` AS itemid';
                }
                if ('' != $this->itemDisabledField && in_array($this->itemDisabledField, $fields)) {
                    $fieldsExtra .= ',`' . $this->itemDisabledField . '` AS disabled';
                }
                $sql =  'SELECT `' . implode('`,`', $fields) . '`' . $fieldsExtra . 
                        ' FROM `' . C_DB_TABLE_PREFIX . $this->itemsTable . '`' . 
                        ' WHERE `' . $this->itemIdField . '`=' . $this->params->itemId;
                $item = $this->db->getItem($sql);
                if (null === $item) {
                    return array();
                }
            } else {
                $item = $this->getEmptyItem();
            }
        }
        return $item;
    }
   
    /**
     * @return array
     */
    protected function getFieldsValues()
    {
        $values = array();
        foreach ($this->fields as $field) {
            if (!array_key_exists('External', $field) || !$field['External']) {
                $values[$field['Name']] = $field['Value'];
            }
        }
        return $values;
    }
    
    /**
     * @return array
     */
    protected function getEmptyItem()
    {
        $item = $this->getFieldsValues();
        if ('' != $this->itemIdField && array_key_exists($this->itemIdField, $item)) {
            $item['itemid'] = $item[$this->itemIdField];
        }
        if ('' != $this->itemDisabledField && array_key_exists($this->itemDisabledField, $item)) {
            $item['disabled'] = $item[$this->itemDisabledField];
        }
        return $item;
    }
    
    /**
     * Get sections with the same muduleid as current section.
     * @param bool $nc = false
     * @return array
     */
    public function getSections($nc = false)
    {
        if (!$nc && !is_null($this->sections)) {
            return $this->sections;
        }
        $section = $this->core->getSection($this->params->sectionId, 'moduleid');
        $items = $this->core->getModuleSections($section['moduleid']);
        foreach ($items as &$item) {
            $item = array('Value' => $item['id'], 'Title' => $item['indic']);
        }
        $this->sections = $items;
        return $this->sections;
    }
    
    /**
     * Check if field is required and field value is set.
     * @param array $field
     * @return bool
     */
    protected function checkFormFieldRequired(array $field)
    {
        if (!array_key_exists('Required', $field)) {
            return true;
        }
        if (!$field['Required']) {
            return true;
        }
        if (!isset($_POST[$field['Name']])) {
            return false;
        }
        $val = $_POST[$field['Name']];
        if (!is_scalar($val)) {
            return true;
        }
        return '' != $_POST[$field['Name']] 
            && (!$this->tools->isInt($_POST[$field['Name']]) || 0 != $_POST[$field['Name']]);
    }
    
    /**
     * Check if field value is set.
     * @param array $field
     * @return bool
     */
    protected function checkFormFieldExists(array $field)
    {
        return isset($_POST[$field['Name']]) || 'bool' == $field['Type'] || 'subform' == $field['Type'];
    }
    
    /**
     * Check if field must be skipped.
     * @param array $field
     * @param bool $update = false
     * @return bool
     */
    protected function fieldSkipped(array $field, $update = false)
    {
        return !$field['Edit'] || ($update && isset($field['Unchange']) && $field['Unchange']);
    }
    
    /**
     * Get, check, cast and collect form field data.
     * @param array $field
     * @return array<'Type', 'Value'>|null
     */
    protected function getFormFieldData(array $field)
    {
        switch ($field['Type']) {
            case 'int':
            case 'list':
            case 'rlist':
                if (!$this->checkFormFieldRequired($field)) {
                    $this->result = 'Required field `' . $field['Name'] . '` not set';
                    return null;
                }
                return array('Type' => $field['Type'], 'Value' => (int) $_POST[$field['Name']]);
            case 'mlist':
                if (!$this->checkFormFieldRequired($field)) {
                    $this->result = 'Required field `' . $field['Name'] . '` not set';
                    return null;
                }
                if (is_array($_POST[$field['Name']])) {
                    if (!$this->tools->isArrayOfIntegers($_POST[$field['Name']])) {
                        $this->result = 'Request error: field `' . $field['Name'] . '` has not correct value(s)';
                        return null;
                    }
                    return array('Type' => $field['Type'], 'Value' => $_POST[$field['Name']]);
                } else {
                    return array('Type' => $field['Type'], 'Value' => (int) $_POST[$field['Name']]);
                }
            case 'bool':
                if (isset($_POST[$field['Name']])) {
                    return array('Type' => $field['Type'], 'Value' => (int) $_POST[$field['Name']]);
                } else {
                    return array('Type' => $field['Type'], 'Value' => 0);
                }
            //case 'slist':
            //case 'mslist':
            default:
                if (!$this->checkFormFieldRequired($field)) {
                    $this->result = 'Required field `' . $field['Name'] . '` not set';
                    return null;
                }
                return array('Type' => $field['Type'], 'Value' => $_POST[$field['Name']]);
        }
    }
    
    /**
     * Get, check, cast and collect form data into array.
     * @param array $fields
     * @param bool $update = false
     * @return array|null
     */
    protected function collectFormData(array $fields, $update = false)
    {
        $data = array();
        foreach ($fields as $field) {
            if ($this->fieldSkipped($field, $update)) {
                continue;
            }
            if (!$this->checkFormFieldExists($field)) {
                $this->result = 'Request error: field `' . $field['Name'] . '` not set';
                return null;
            }
            if ('subform' != $field['Type']) {
                $fieldData = $this->getFormFieldData($field);
                if (null === $fieldData) {
                    return null;
                }
                $data[$field['Name']] = $fieldData;
            } else {
                if (isset($field['Schema'])) {
                    $obj = $this->getFieldSchema($field);
                } else if (isset($field['Model'])) {
                    $obj = $this->getFieldModel($field);
                } else {
                    continue;
                }
                $subFields = $obj->getFields();
                unset($obj);
                if (is_array($subFields) && 0 < count($subFields)) {
                    $subData = $this->collectFormData($subFields, $update);
                    $subDataSimple = array();
                    foreach ($subData as $key => $val) {
                        $subDataSimple[$key] = $val['Value'];
                    }
                    $data[$field['Name']] = array('Type' => $field['Type'], 'Value' => json_encode($subDataSimple));
                }
            }
        }
        return $data;
    }
    
    /**
     * Assemble INSERT SQL query
     * @param array $data
     * @return string
     */
    protected function getItemSqlInsert(array $data)
    {
        $fields = '';
        $values = '';
        foreach ($data as $name => $field) {
            $fields .= '`' . $name . '`,';
            switch ($field['Type']) {
                case 'int':
                case 'bool':
                case 'list':
                    $values .= $field['Value'] . ',';
                    break;
                case 'mlist':
                    $values .= "'" . (is_array($field['Value']) ? implode(',', $field['Value']) : $field['Value']) . "',";
                    break;
                default:
                    $values .= "'" . $this->db->addEscape($field['Value']) . "',";
            }
        }
        if ('' == $fields || '' == $values) {
            return '';
        }
        return  'INSERT INTO `' . C_DB_TABLE_PREFIX . $this->itemsTable . '`' . 
                ' (' . substr($fields, 0, -1) . ')' . 
                ' VALUES(' . substr($values, 0, -1) . ')';
    }
    
    /**
     * Assemble UPDATE SQL query
     * @param array $data
     * @return string
     */
    protected function getItemSqlUpdate(array $data)
    {
        $fields = '';
        foreach ($data as $name => $field) {
            switch ($field['Type']) {
                case 'int':
                case 'bool':
                case 'list':
                    $fields .= '`' . $name . '`=' . $field['Value'] . ',';
                    break;
                case 'mlist':
                    $fields .= '`' . $name . "`='" . (is_array($field['Value']) ? implode(',', $field['Value']) : $field['Value']) . "',";
                    break;
                default:
                    $fields .= '`' . $name . "`='" . $this->db->addEscape($field['Value']) . "',";
            }
        }
        if ('' == $fields) {
            return '';
        }
        return  'UPDATE `' . C_DB_TABLE_PREFIX . $this->itemsTable . '`' . 
                ' SET ' . substr($fields, 0, -1) . 
                ' WHERE `' . $this->itemIdField . '`=' . $this->params->itemId;
    }
    
    /**
     * Prepare SQL query
     * @param array $data
     * @param bool $insert = true
     * @return string
     */
    protected function prepareSql(array $data, $insert = true)
    {
        if ($insert) {
            return $this->getItemSqlInsert($data);
        } else {
            return $this->getItemSqlUpdate($data);
        }
    }
    
    /**
     * @return string
     */
    protected function getItemSqlDelete()
    {
        return  'DELETE FROM `' . C_DB_TABLE_PREFIX . $this->itemsTable . '`' . 
                ' WHERE `' . $this->itemIdField . '`=' . $this->params->itemId;
    }
    
    /**
     * @return string
     */
    protected function getItemSqlDisable()
    {
        return  'UPDATE `' . C_DB_TABLE_PREFIX . $this->itemsTable . '`' . 
                ' SET `' . $this->itemDisabledField . '`=' . ($this->itemDisabledFieldInvert ? '0' : '1') . 
                ' WHERE `' . $this->itemIdField . '`=' . $this->params->itemId;
    }
    
    /**
     * @return string
     */
    protected function getItemSqlEnable()
    {
        return  'UPDATE `' . C_DB_TABLE_PREFIX . $this->itemsTable . '`' . 
                ' SET `' . $this->itemDisabledField . '`=' . ($this->itemDisabledFieldInvert ? '1' : '0') . 
                ' WHERE `' . $this->itemIdField . '`=' . $this->params->itemId;
    }
    
    /**
     * @return bool
     */
    protected function checkItemId()
    {
        return null !== $this->params->itemId;
    }
    
    /**
     * @param string $action
     * @return bool
     */
    protected function checkActionSupported($action)
    {
        switch ($action) {
            case 'insert':
            case 'update':
                return (0 == $this->params->itemId && $this->canCreateItems) 
                    || (0 != $this->params->itemId && $this->canUpdateItems && '' != $this->itemIdField);
            case 'disable':
            case 'enable':
                return '' != $this->itemDisabledField && '' != $this->itemIdField;
            case 'delete':
                return $this->canDeleteItems && '' != $this->itemIdField;
        }
        return false;
    }
    
    /**
     * Check(s) before insert item.
     * @return bool
     */
    protected function checkBeforeInsert()
    {
        return true;
    }
    
    /**
     * Check(s) before update item.
     * @return bool
     */
    protected function checkBeforeUpdate()
    {
        return true;
    }
    
    /**
     * Check(s) before delete item.
     * @return bool
     */
    protected function checkBeforeDelete()
    {
        return true;
    }
    
    /**
     * Check(s) before disable item.
     * @return bool
     */
    protected function checkBeforeDisable()
    {
        return true;
    }
    
    /**
     * Check(s) before enable item.
     * @return bool
     */
    protected function checkBeforeEnable()
    {
        return true;
    }
    
    /**
     * Action(s) before insert item.
     */
    protected function actionBeforeInsert()
    {
        $this->actionBeforeAll();
    }
    
    /**
     * Action(s) before delete item.
     */
    protected function actionBeforeDelete()
    {
        $this->actionBeforeAll();
    }
    
    /**
     * Action(s) before update item.
     */
    protected function actionBeforeUpdate()
    {
        $this->actionBeforeChange();
    }
    
    /**
     * Action(s) before disable item.
     */
    protected function actionBeforeDisable()
    {
        $this->actionBeforeChange();
    }
    
    /**
     * Action(s) before enable item.
     */
    protected function actionBeforeEnable()
    {
        $this->actionBeforeChange();
    }
    
    /**
     * Action(s) before all actions which can change item (update, disable, enable).
     */
    protected function actionBeforeChange()
    {
        $this->actionBeforeAll();
    }
    
    /**
     * Action(s) before all actions types of item.
     */
    protected function actionBeforeAll()
    {
        
    }
    
    /**
     * Action(s) after insert item.
     * @return bool
     */
    protected function actionAfterInsert()
    {
        return $this->actionAfterAll();
    }
    
    /**
     * Action(s) after delete item.
     * @return bool
     */
    protected function actionAfterDelete()
    {
        return $this->actionAfterAll();
    }
    
    /**
     * Action(s) after update item.
     * @return bool
     */
    protected function actionAfterUpdate()
    {
        return $this->actionAfterChange();
    }
    
    /**
     * Action(s) after disable item.
     * @return bool
     */
    protected function actionAfterDisable()
    {
        return $this->actionAfterChange();
    }
    
    /**
     * Action(s) after enable item.
     * @return bool
     */
    protected function actionAfterEnable()
    {
        return $this->actionAfterChange();
    }
    
    /**
     * Action(s) after all actions which can change item (update, disable, enable).
     * @return bool
     */
    protected function actionAfterChange()
    {
        return $this->actionAfterAll();
    }
    
    /**
     * Action(s) after all actions types of item.
     * @return bool
     */
    protected function actionAfterAll()
    {
        return true;
    }
    
    /**
     * Update item data.
     * @return bool
     */
    public function update()
    {
        if (!$this->checkItemId()) {
            $this->result = 'Request error: param `itemid` not set';
            return false;
        }
        if (!$this->checkActionSupported('update')){
            $this->result = 'Action not supported';
            return false;
        }
        
        if (0 == $this->params->itemId) {
            if (!$this->checkBeforeInsert()) {
                $this->result = 'Action check failed';
                return false;
            }
        } else {
            if (!$this->checkBeforeUpdate()) {
                $this->result = 'Action check failed';
                return false;
            }
        }
        
        $data = $this->collectFormData($this->fields, 0 != $this->params->itemId);
        if (null === $data) {
            return false;
        }
        $sql = $this->prepareSql($data, 0 == $this->params->itemId);
        if (0 == $this->params->itemId) {
            $this->actionBeforeInsert();
        } else {
            $this->actionBeforeUpdate();
        }
        if ($this->db->query($sql)) {
            $this->result = 'updated';
            if (0 == $this->params->itemId) {
                $this->lastInsertedId = $this->db->getLastInsertedId();
                return $this->actionAfterInsert();
            } else {
                return $this->actionAfterUpdate();
            }
        } else {
            $this->result = 'DB error: ' . $this->db->getError();
            return false;
        }
    }
    
    /**
     * Delete item
     * @return bool
     */
    public function delete()
    {
        if (!$this->checkItemId()) {
            $this->result = 'Request error: param `itemid` not set';
            return false;
        }
        if (!$this->checkActionSupported('delete')) {
            $this->result = 'Action not supported';
            return false;
        }
        if (!$this->checkBeforeDelete()) {
            $this->result = 'Action check failed';
            return false;
        }
        $this->actionBeforeDelete();
        if ($this->db->query($this->getItemSqlDelete())) {
            $this->result = 'deleted';
            return $this->actionAfterDelete();
        } else {
            $this->result = 'DB error: ' . $this->db->getError();
            return false;
        }
    }
    
    /**
     * Disable item (set flag disabled)
     * @return bool
     */
    public function disable()
    {
        if (!$this->checkItemId()) {
            $this->result = 'Request error: param `itemid` not set';
            return false;
        }
        if (!$this->checkActionSupported('disable')){
            $this->result = 'Action not supported';
            return false;
        }
        if (!$this->checkBeforeDisable()) {
            $this->result = 'Action check failed';
            return false;
        }
        $this->actionBeforeDisable();
        if ($this->db->query($this->getItemSqlDisable())) {
            $this->result = 'disabled';
            return $this->actionAfterDisable();
        } else {
            $this->result = 'DB error: ' . $this->db->getError();
            return false;
        }
    }
    
    /**
     * Enable item (unset flag disabled)
     * @return bool
     */
    public function enable()
    {
        if (!$this->checkItemId()) {
            $this->result = 'Request error: param `itemid` not set';
            return false;
        }
        if (!$this->checkActionSupported('enable')){
            $this->result = 'Action not supported';
            return false;
        }
        if (!$this->checkBeforeEnable()) {
            $this->result = 'Action check failed';
            return false;
        }
        $this->actionBeforeEnable();
        if ($this->db->query($this->getItemSqlEnable())) {
            $this->result = 'enabled';
            return $this->actionAfterEnable();
        } else {
            $this->result = 'DB error: ' . $this->db->getError();
            return false;
        }
    }
    
    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }
    
    /**
     * @return bool
     */
    public function isCanCreateItems()
    {
        return $this->canCreateItems;
    }
    
    /**
     * @return bool
     */
    public function isCanUpdateItems()
    {
        return $this->canUpdateItems;
    }
    
    /**
     * @return bool
     */
    public function isCanDeleteItems()
    {
        return $this->canDeleteItems;
    }
    
    /**
     * @return int|null
     */
    public function getLastInsertedId()
    {
        return $this->lastInsertedId;
    }
}
