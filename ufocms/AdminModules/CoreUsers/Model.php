<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\CoreUsers;

/**
 * Core users model class
 */
class Model extends \Ufocms\AdminModules\Model
{
    /**
     * User related groups (ids).
     * @var array
     */
    protected $groups = array();
    
    /**
     * @see parent
     */
    protected function init()
    {
        parent::init();
        $this->itemsTable = 'users';
        $this->primaryFilter = '';
        $this->defaultSort = 'Title';
    }
    
    protected function setFields()
    {
        $this->fields = array(
            array('Type' => 'int',          'Name' => 'Id',             'Value' => 0,                       'Title' => 'id',                'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => false),
            array('Type' => 'datetime',     'Name' => 'DateCreate',     'Value' => date('Y-m-d H:i:s'),     'Title' => 'Зарегистрирован',   'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => false,    'Required' => true,     'Class' => 'small'),
            array('Type' => 'datetime',     'Name' => 'DateLogin',      'Value' => date('Y-m-d H:i:s'),     'Title' => 'Последний вход',    'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => false,    'Class' => 'small'),
            array('Type' => 'int',          'Name' => 'EntryCounter',   'Value' => 0,                       'Title' => 'Заходов',           'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => false),
            array('Type' => 'bool',         'Name' => 'IsDisabled',     'Value' => false,                   'Title' => 'Отключен',          'Filter' => true,   'Show' => false,    'Sort' => false,    'Edit' => true,     'Items' => array(array('Value' => 1, 'Title' => 'Отключен'), array('Value' => 0, 'Title' => 'Включен'))),
            array('Type' => 'bool',         'Name' => 'IsHidden',       'Value' => false,                   'Title' => 'Скрыт',             'Filter' => true,   'Show' => false,    'Sort' => false,    'Edit' => true,     'Items' => array(array('Value' => 1, 'Title' => 'Скрыт'), array('Value' => 0, 'Title' => 'Открыт'))),
            array('Type' => 'text',         'Name' => 'Ticket',         'Value' => '',                      'Title' => 'Ticket',            'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => true),
            array('Type' => 'text',         'Name' => 'ExtUID',         'Value' => '',                      'Title' => 'Внешний UID',       'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => true),
            array('Type' => 'text',         'Name' => 'Login',          'Value' => '',                      'Title' => 'Логин',             'Filter' => true,   'Show' => true,     'Sort' => true,     'Edit' => true),
            array('Type' => 'text',         'Name' => 'Password',       'Value' => '',                      'Title' => 'Пароль',            'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'text',         'Name' => 'Title',          'Value' => '',                      'Title' => 'Отображаемое имя',  'Filter' => true,   'Show' => true,     'Sort' => true,     'Edit' => true),
            array('Type' => 'image',        'Name' => 'Image',          'Value' => '',                      'Title' => 'Изображение',       'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'text',         'Name' => 'Email',          'Value' => '',                      'Title' => 'Email',             'Filter' => true,   'Show' => true,     'Sort' => true,     'Edit' => true),
            array('Type' => 'bigtext',      'Name' => 'Description',    'Value' => '',                      'Title' => 'Описание',          'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'mlist',        'Name' => 'Groups',         'Value' => 'getUserGroupsId',       'Title' => 'Группы',            'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true,     'Items' => 'getGroups', 'External' => true),
        );
    }
    
    /**
     * @return array
     */
    protected function getGroups()
    {
        $sql =  'SELECT Id AS Value, Title' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'users_groups' . 
                ' ORDER BY Title';
        return array_merge(
            array(array('Value' => 0, 'Title' => '')), 
            $this->db->getItems($sql)
        );
    }
    
    /**
     * @param int $userId
     * @return array<int>
     */
    public function getUserGroups($userId)
    {
        static $items = null;
        if (null === $items) {
            $sql =  'SELECT u.Id, g.Title' . 
                    ' FROM ' . C_DB_TABLE_PREFIX . 'users AS u' . 
                    ' INNER JOIN ' . C_DB_TABLE_PREFIX . 'users_groups_relations AS r ON u.Id=r.UserId' . 
                    ' INNER JOIN ' . C_DB_TABLE_PREFIX . 'users_groups AS g ON g.Id=r.GroupId';
            $itms = $this->db->getItems($sql);
            foreach ($itms as $itm) {
                $items[$itm['Id']][] = $itm['Title'];
            }
            unset($itms);
        }
        if (isset($items[$userId])) {
            return $items[$userId];
        } else {
            return array();
        }
    }
    
    /**
     * @param int $userId
     * @return array<int>
     */
    public function getUserGroupsId($userId)
    {
        $sql =  'SELECT GroupId' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'users_groups_relations' . 
                ' WHERE UserId=' . $userId;
        return $this->db->getValues($sql, 'GroupId');
    }
    
    /**
     * Update user relations (groups).
     */
    protected function updateRelations()
    {
        $groupsNew = $this->groups;
        
        if (0 != $this->params->itemId) {
            $groupsExists = $this->getUserGroupsId($this->params->itemId);
            $groupsDelete = array();
            foreach ($groupsExists as $e) {
                if (!in_array($e, $groupsNew)) {
                    $groupsDelete[] = $e;
                }
            }
            if (0 < count($groupsDelete)) {
                $sql =  'DELETE FROM ' . C_DB_TABLE_PREFIX . 'users_groups_relations' . 
                        ' WHERE UserId=' . $this->params->itemId . 
                        ' AND GroupId IN(' . implode(',', $groupsDelete) . ')';
                $this->db->query($sql);
            }
            
            $groupsInsert = array();
            foreach ($groupsNew as $n) {
                if (!in_array($n, $groupsExists)) {
                    $groupsInsert[] = $n;
                }
            }
            if (0 < count($groupsInsert)) {
                $sql =  'INSERT INTO ' . C_DB_TABLE_PREFIX . 'users_groups_relations' . 
                        ' (UserId,GroupId) VALUES ';
                $s = '';
                foreach ($groupsInsert as $n) {
                    $s .= ',(' . $this->params->itemId . ',' . $n . ')';
                }
                $sql .= substr($s, 1);
                $this->db->query($sql);
            }
        } else {
            $sql =  'INSERT INTO ' . C_DB_TABLE_PREFIX . 'users_groups_relations' . 
                    ' (UserId,GroupId) VALUES ';
            $s = '';
            foreach ($groupsNew as $n) {
                $s .= ',(' . $this->lastInsertedId . ',' . $n . ')';
            }
            $sql .= substr($s, 1);
            $this->db->query($sql);
        }
    }
    
    /**
     * @see parent
     */
    protected function collectFormData(array $fields, $update = false)
    {
        $data = parent::collectFormData($fields, $update);
        $this->groups = $data['Groups']['Value'];
        unset($data['Groups']);
        return $data;
    }
    
    /**
     * @see parent
     */
    protected function actionAfterInsert()
    {
        $this->updateRelations();
    }
    
    /**
     * @see parent
     */
    protected function actionAfterUpdate()
    {
        $this->updateRelations();
    }
    
    /**
     * @see parent
     */
    protected function actionAfterDelete()
    {
        $sql =  'DELETE FROM ' . C_DB_TABLE_PREFIX . 'users_groups_relations' . 
                ' WHERE UserId=' . $this->params->itemId;
        return $this->db->query($sql);
    }
}
