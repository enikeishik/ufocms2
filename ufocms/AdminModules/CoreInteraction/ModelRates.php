<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\CoreInteraction;

/**
 * Core interaction rates model class
 */
class ModelRates extends \Ufocms\AdminModules\Model
{
    protected function init()
    {
        parent::init();
        $this->itemsTable = 'interaction_rates';
        $this->itemIdField = 'Id';
        $this->itemDisabledField = 'IsDisabled';
        $this->primaryFilter = '';
        $this->defaultSort = 'ir.DateCreate DESC';
        $this->canCreateItems = false;
        $this->canUpdateItems = false;
        $this->config->registerAction('blacklist');
        $this->config->registerMakeAction('blacklist');
        $this->params->actionUnsafe = false;
    }
    
    protected function setItems()
    {
        $sql = $this->getItemsSqlBase(
            'ir', 
            ' INNER JOIN ' . C_DB_TABLE_PREFIX . 'sections AS s ON ir.SectionId=s.id'
        );
        $sql .= $this->getItemsSqlCondition('ir');
        $this->itemsCount = $this->db->getValue('SELECT COUNT(*) AS Cnt' . $sql, 'Cnt');
        if (0 == $this->itemsCount) {
            $this->items = array();
            return;
        }
        
        $sql =  'SELECT ir.*, ir.Id AS itemid, ir.IsDisabled AS disabled, s.path, s.indic, u.Title AS UserTitle' . 
                $sql . ' LEFT JOIN ' . C_DB_TABLE_PREFIX . 'users AS u ON ir.UserId=u.Id' . 
                $this->getItemsSqlSorting('ir') . 
                $this->getItemsSqlLimits();
        $this->items = $this->db->getItems($sql);
        if (null === $this->items) {
            $this->items = array();
        }
    }
    
    protected function setFields()
    {
        $this->fields = array(
            array('Type' => 'int',          'Name' => 'Id',             'Value' => 0,       'Title' => 'id',            'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => false),
            array('Type' => 'int',          'Name' => 'UserId',         'Value' => 0,       'Title' => 'UserId',        'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => false),
            array('Type' => 'int',          'Name' => 'SectionId',      'Value' => 0,       'Title' => 'SectionId',     'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => false),
            array('Type' => 'int',          'Name' => 'ItemId',         'Value' => 0,       'Title' => 'ItemId',        'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => false),
            array('Type' => 'datetime',     'Name' => 'DateCreate',     'Value' => '',      'Title' => 'Дата/время',    'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => false),
            array('Type' => 'text',         'Name' => 'IP',             'Value' => '',      'Title' => 'IP',            'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => false),
            array('Type' => 'bigtext',      'Name' => 'Info',           'Value' => '',      'Title' => 'Информация',    'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => false,    'Raw' => true),
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
        $this->updateRelations($item['UserId'], $item['SectionId'], $item['ItemId']);
        return true;
    }
    
    /**
     * @param int|null $userId = null
     * @param int|null $sectionId = null
     * @param int|null $itemId = null
     */
    protected function updateRelations($userId, $sectionId, $itemId)
    {
        $statistic = new Statistic($this->db, $this->core, $this->debug);
        
        //обновляем статистику материала
        $ret1 = $statistic->updateItemStat($sectionId, $itemId, 1);
        
        //обновляем статистику пользователя
        $ret2 = true;
        if (0 != $userId) {
            $ret2 = $statistic->updateUserStat($userId, 1);
        }
    }
    
    public function blacklist()
    {
        $sql =  'SELECT IP FROM ' . C_DB_TABLE_PREFIX . 'interaction_rates' . 
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
