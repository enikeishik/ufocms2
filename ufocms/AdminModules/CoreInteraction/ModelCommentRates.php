<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\CoreInteraction;

/**
 * Core interaction comment rates model class
 */
class ModelCommentRates extends \Ufocms\AdminModules\Model
{
    /**
     * @see parent
     */
    protected function init()
    {
        parent::init();
        $this->itemsTable = 'interaction_comments_rates';
        $this->itemIdField = 'Id';
        $this->itemDisabledField = 'IsDisabled';
        $this->primaryFilter = '';
        $this->defaultSort = 'r.DateCreate DESC';
        $this->canCreateItems = false;
        $this->canUpdateItems = false;
        $this->config->registerAction('blacklist');
        $this->config->registerMakeAction('blacklist');
        $this->params->actionUnsafe = false;
    }
    
    /**
     * @see parent
     */
    protected function setItems()
    {
        $sql = $this->getItemsSqlBase(
            'r', 
            ' INNER JOIN ' . C_DB_TABLE_PREFIX . 'interaction_comments AS c ON r.CommentId=c.Id'
        );
        $where = $this->getItemsSqlCondition('r');
        $where = '' == $where ? ' WHERE r.CommentId=' . $this->params->commentId : $where . ' AND r.CommentId=' . $this->params->commentId;
        $this->itemsCount = $this->db->getValue('SELECT COUNT(*) AS Cnt' . $sql . $where, 'Cnt');
        if (0 == $this->itemsCount) {
            $this->items = array();
            return;
        }
        
        $sql =  'SELECT r.*, r.Id AS itemid, r.IsDisabled AS disabled, c.CommentText, u.Title AS UserTitle' . 
                $sql . ' LEFT JOIN ' . C_DB_TABLE_PREFIX . 'users AS u ON r.UserId=u.Id' . 
                $where . 
                $this->getItemsSqlSorting('r') . 
                $this->getItemsSqlLimits();
        $this->items = $this->db->getItems($sql);
        if (null === $this->items) {
            $this->items = array();
        }
    }
    
    /**
     * @see parent
     */
    protected function setFields()
    {
        $this->fields = array(
            array('Type' => 'int',          'Name' => 'Id',             'Value' => 0,       'Title' => 'id',            'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => false),
            array('Type' => 'int',          'Name' => 'UserId',         'Value' => 0,       'Title' => 'UserId',        'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => false),
            array('Type' => 'int',          'Name' => 'SectionId',      'Value' => 0,       'Title' => 'SectionId',     'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => false),
            array('Type' => 'int',          'Name' => 'CommentId',      'Value' => 0,       'Title' => 'CommentId',     'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => false),
            array('Type' => 'datetime',     'Name' => 'DateCreate',     'Value' => '',      'Title' => 'Дата/время',    'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => false),
            array('Type' => 'text',         'Name' => 'IP',             'Value' => '',      'Title' => 'IP',            'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => false),
            array('Type' => 'bigtext',      'Name' => 'Info',           'Value' => '',      'Title' => 'Информация',    'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => false,    'Raw' => true),
            array('Type' => 'text',         'Name' => 'CommentText',    'Value' => '',      'Title' => 'Комментарий',   'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => false,    'External' => true),
            array('Type' => 'text',         'Name' => 'UserTitle',      'Value' => '',      'Title' => 'Пользователь',  'Filter' => true,   'Show' => true,     'Sort' => true,     'Edit' => false,    'External' => true),
            array('Type' => 'int',          'Name' => 'Rate',           'Value' => '',      'Title' => 'Оценка',        'Filter' => true,   'Show' => true,     'Sort' => true,     'Edit' => false),
            array('Type' => 'bool',         'Name' => 'IsDisabled',     'Value' => false,   'Title' => 'Отключен',      'Filter' => true,   'Show' => true,     'Sort' => true,     'Edit' => true),
        );
    }
    
    /**
     * @see parent
     */
    protected function actionBeforeAll()
    {
        $this->getItem(); //cache current item data
    }
    
    /**
     * @see parent
     */
    protected function actionAfterAll()
    {
        $item = $this->getItem(); //get cached before delete data
        $this->updateRelations($item['UserId'], $item['CommentId']);
        return true;
    }
    
    /**
     * @param int|null $userId = null
     * @param int|null $sectionId = null
     * @param int|null $itemId = null
     */
    protected function updateRelations($userId, $commentId)
    {
        $statistic = new Statistic($this->db, $this->core, $this->debug);
        
        //обновляем статистику комментария
        $ret1 = $statistic->updateCommentRating($commentId);
        
        //обновляем статистику пользователя
        $ret2 = true;
        if (0 != $userId) {
            $ret2 = $statistic->updateUserStat($userId, 2);
        }
        
        //обновляем статистику пользователя-автора комментария данной отметки
        $sql =  'SELECT UserId' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'interaction_comments' . 
                ' WHERE Id=' . $commentId;
        $commentUserId = $this->db->getValue($sql, 'UserId');
        $ret3 = true;
        if (0 != $commentUserId) {
            $ret3 = $statistic->updateUserStat($commentUserId, -1);
        }
    }
    
    public function blacklist()
    {
        $sql =  'SELECT IP FROM ' . C_DB_TABLE_PREFIX . 'interaction_comments_rates' . 
                ' WHERE Id=' . $this->params->itemId;
        $item = $this->db->getItem($sql);
        if (!is_null($item)) {
            $sql = 'INSERT INTO ' . C_DB_TABLE_PREFIX . 'interaction_blacklist' . 
                   ' (DateCreate, IP)' . 
                   " VALUES(NOW(), '" . $this->db->addEscape($item['IP']) . "')";
            if ($this->db->query($sql)) {
                $this->result = 'added to black list';
            } else {
                $this->result = 'DB error: ' . $this->db->getError();
            }
        } else {
            $this->result = 'Error: item not exists';
        }
    }
}
