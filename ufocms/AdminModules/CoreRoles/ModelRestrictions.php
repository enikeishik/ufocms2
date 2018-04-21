<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\CoreRoles;

/**
 * Core [admin] roles restrictions model class
 */
class ModelRestrictions extends ModelBase
{
    /**
     * @see parent
     */
    protected function init()
    {
        parent::init();
        $this->itemsTable = 'users_roles_restrictions';
        $this->itemIdField = 'RoleId';
        $this->itemDisabledField = '';
        $this->primaryFilter = '';
        $this->defaultSort = 'Id';
        $this->params->itemId = $this->roleId;
    }
    
    /**
     * @see parent
     */
    protected function setFields()
    {
        $this->fields = array(
            array('Type' => 'int',      'Name' => 'Id',             'Value' => 0,               'Title' => 'id',                'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => false),
            array('Type' => 'list',     'Name' => 'RoleId',         'Value' => $this->roleId,   'Title' => 'Роль',              'Filter' => true,   'Show' => true,     'Sort' => true,     'Edit' => true,     'Unchange' => true,             'Items' => 'getRoles'),
            array('Type' => 'mslist',   'Name' => 'CoreModules',    'Value' => '',              'Title' => 'Модули ядра',       'Filter' => true,   'Show' => true,     'Sort' => false,    'Edit' => true,     'Items' => 'getCoreModules'),
            array('Type' => 'mslist',   'Name' => 'Modules',        'Value' => '',              'Title' => 'Модули разделов',   'Filter' => true,   'Show' => true,     'Sort' => false,    'Edit' => true,     'Items' => 'getModules'),
            array('Type' => 'mslist',   'Name' => 'Sections',       'Value' => '',              'Title' => 'Разделы',           'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true,     'Items' => 'getAllSections'),
        );
    }
    
    /**
     * @return array
     */
    protected function getCoreModules()
    {
        $arr = [
            ['Value' => 'none', 'Title' => 'запретить все'], 
            ['Value' => '', 'Title' => 'нет ограничений'], 
        ];
        foreach ($this->config->coreModules as $id => $item) {
            $arr[] = ['Value' => $id, 'Title' => strip_tags($item['Title'])];
        }
        return $arr;
    }
    
    /**
     * @return array
     */
    protected function getModules()
    {
        $sql =  'SELECT muid AS Value, mname AS Title' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'modules' . 
                ' ORDER BY mname';
        return array_merge(
            [
                ['Value' => 'none', 'Title' => 'запретить все'], 
                ['Value' => '', 'Title' => 'нет ограничений'], 
            ], 
            $this->db->getItems($sql) ?: []
        );
    }
    
    /**
     * @return array
     */
    protected function getAllSections()
    {
        $items = $this->core->getSections('id,levelid,indic,isenabled');
        foreach ($items as &$item) {
            $item = [
                'Value'     => $item['id'], 
                'Title'     => str_pad('', ($item['levelid'] + 1) * 4, '.', STR_PAD_LEFT) . $item['indic'], 
                'IsEnabled' => $item['isenabled']
            ];
        }
        unset($item);
        return array_merge(
            [
                ['Value' => 'none', 'Title' => 'запретить все'], 
                ['Value' => '',     'Title' => 'нет ограничений'], 
                ['Value' => -1,     'Title' => 'Главная страница'], 
            ], 
            $items
        );
    }
    
    /**
     * @see parent
     */
    protected function prepareSql(array $data, $insert = true)
    {
        $data = array_merge(
            array('RoleId' => array('Type' => 'int', 'Value' => $this->params->itemId)), 
            $data
        );
        
        $sql =  'SELECT COUNT(*) AS Cnt' . 
                ' FROM ' . C_DB_TABLE_PREFIX . $this->itemsTable . 
                ' WHERE ' . $this->itemIdField . '=' . $this->params->itemId;
        $exists = 0 != $this->db->getValue($sql, 'Cnt');
        if (!$exists) {
            return $this->getItemSqlInsert($data);
        } else {
            return $this->getItemSqlUpdate($data);
        }
    }
}
