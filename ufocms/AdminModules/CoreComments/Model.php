<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\CoreComments;

/**
 * Core comments model class
 */
class Model extends \Ufocms\AdminModules\Model
{
    protected function init()
    {
        parent::init();
        $this->itemsTable = 'comments';
        $this->itemIdField = 'id';
        $this->itemDisabledField = 'disabled';
        $this->primaryFilter = '';
        $this->defaultSort = 'dtm DESC';
        $this->canCreateItems = false;
        $this->config->registerAction('blacklist');
        $this->config->registerMakeAction('blacklist');
        $this->config->registerAction('globalblacklist');
        $this->config->registerMakeAction('globalblacklist');
        $this->params->actionUnsafe = false;
    }
    
    protected function setFields()
    {
        $this->fields = array(
            array('Type' => 'int',          'Name' => 'id',             'Value' => 0,       'Title' => 'id',            'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => false),
            array('Type' => 'datetime',     'Name' => 'dtm',            'Value' => '',      'Title' => 'Дата/время',    'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => false),
            array('Type' => 'slist',        'Name' => 'url',            'Value' => '',      'Title' => 'URL',           'Filter' => true,   'Show' => true,     'Sort' => true,     'Edit' => false,    'Items' => 'getUrls'),
            array('Type' => 'text',         'Name' => 'ip',             'Value' => '',      'Title' => 'IP',            'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => false),
            array('Type' => 'bigtext',      'Name' => 'info',           'Value' => '',      'Title' => 'Информация',    'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => false,    'Raw' => true),
            array('Type' => 'bigtext',      'Name' => 'comment',        'Value' => '',      'Title' => 'Комментарий',   'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'text',         'Name' => 'comment_sign',   'Value' => '',      'Title' => 'Подпись',       'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'text',         'Name' => 'comment_email',  'Value' => '',      'Title' => 'Email',         'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'text',         'Name' => 'comment_url',    'Value' => '',      'Title' => 'Url',           'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'bigtext',      'Name' => 'answer',         'Value' => '',      'Title' => 'Ответ',         'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'text',         'Name' => 'answer_sign',    'Value' => '',      'Title' => 'Подпись отв.',  'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'text',         'Name' => 'answer_email',   'Value' => '',      'Title' => 'Email отв.',    'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'text',         'Name' => 'answer_url',     'Value' => '',      'Title' => 'Url отв.',      'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'int',          'Name' => 'rate',           'Value' => '',      'Title' => 'Оценка',        'Filter' => true,   'Show' => true,     'Sort' => true,     'Edit' => true,     'Items' => $this->getRatings()),
            array('Type' => 'bool',         'Name' => 'disabled',       'Value' => false,   'Title' => 'Отключен',      'Filter' => true,   'Show' => true,     'Sort' => true,     'Edit' => true),
        );
    }
    
    protected function getRatings()
    {
        return array(
            array('Value' => -1, 'Title' => -1), 
            array('Value' => 1, 'Title' => 1), 
            array('Value' => 2, 'Title' => 2), 
            array('Value' => 3, 'Title' => 3), 
            array('Value' => 4, 'Title' => 4), 
            array('Value' => 5, 'Title' => 5), 
        );
    }
    
    protected function getUrls()
    {
        $sql =  'SELECT DISTINCT url' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'comments' . 
                ' ORDER BY url';
        $items = $this->db->getItems($sql);
        foreach ($items as &$item) {
            $item = array('Value' => $item['url'], 'Title' => $item['url']);
        }
        unset($item);
        return $items;
    }
    
    /**
     * @see parent
     */
    protected function actionAfterChange()
    {
        $this->recalcRating($this->getItemUrl($this->params->itemId));
        return true;
    }
    
    /**
     * @see parent
     */
    protected function actionAfterDelete()
    {
        $this->recalcRating($this->getItemUrl($this->params->itemId));
        return true;
    }
    
    public function blacklist()
    {
        $sql =  'SELECT url,ip FROM ' . C_DB_TABLE_PREFIX . 'comments' . 
                ' WHERE id=' . $this->params->itemId;
        $item = $this->db->getItem($sql);
        if (!is_null($item)) {
            $sql = 'INSERT INTO ' . C_DB_TABLE_PREFIX . 'comments_blacklist' . 
                   ' (url, ip)' . 
                   " VALUES('" . $this->db->addEscape($item['url']) . "', '" . $this->db->addEscape($item['ip']) . "')";
            if ($this->db->query($sql)) {
                $this->result = 'added to black list';
                return true;
            } else {
                $this->result = 'DB error: ' . $this->db->getError();
                return false;
            }
        } else {
            $this->result = 'Error: item not exists';
            return false;
        }
    }
    
    public function globalblacklist()
    {
        $sql =  'SELECT ip FROM ' . C_DB_TABLE_PREFIX . 'comments' . 
                ' WHERE id=' . $this->params->itemId;
        $ip = $this->db->getValue($sql, 'ip');
        if (!is_null($ip)) {
            $sql = 'INSERT INTO ' . C_DB_TABLE_PREFIX . 'comments_blacklist' . 
                   ' (url, ip)' . 
                   " VALUES('*', '" . $this->db->addEscape($ip) . "')";
            if ($this->db->query($sql)) {
                $this->result = 'added to global black list';
                return true;
            } else {
                $this->result = 'DB error: ' . $this->db->getError();
                return false;
            }
        } else {
            $this->result = 'Error: item not exists';
            return false;
        }
    }
    
    protected function getItemUrl($itemId)
    {
        $sql =  'SELECT url FROM ' . C_DB_TABLE_PREFIX . 'comments' . 
                ' WHERE id=' . $itemId;
        return $this->db->getValue($sql, 'url');
    }
    
    protected function recalcRating($url)
    {
        if (is_null($url)) {
            return;
        }
        
        //check if rating exists
        $sql =  'SELECT COUNT(*) AS cnt FROM ' . C_DB_TABLE_PREFIX . 'comments_rating' . 
                " WHERE url='" . $this->db->addEscape($url) . "'";
        $cnt = $this->db->getValue($sql, 'cnt');
        if (is_null($cnt)) {
            return;
        }
        
        //get rating info
        $sql =  'SELECT SUM(rate) AS rsm, COUNT(*) AS cnt FROM ' . C_DB_TABLE_PREFIX . 'comments' . 
                " WHERE disabled=0 AND rate>0 AND url='" . $this->db->addEscape($url) . "'";
        $item = $this->db->getItem($sql);
        if (is_null($cnt)) {
            return;
        }
        
        if (0 < $item['cnt']) { //update rating if exists
            if (0 < $cnt) {
                $sql =  'UPDATE ' . C_DB_TABLE_PREFIX . 'comments_rating ' . 
                        "SET val='" . $item['rsm'] / $item['cnt'] . "', cnt=" . $item['cnt'] . 
                        " WHERE url='" . $this->db->addEscape($url) . "'";
            } else {
                $sql =  'INSERT INTO ' . C_DB_TABLE_PREFIX . 'comments_rating ' . 
                        "(url,val,cnt)" . 
                        " VALUES('" . $this->db->addEscape($url) . "','" . $item['rsm'] / $item['cnt'] . "'," . $item['cnt'] . ")";
            }
        } else { //or delete rating if no rating data more
            $sql =  'DELETE FROM ' . C_DB_TABLE_PREFIX . 'comments_rating ' . 
                    " WHERE url='" . $this->db->addEscape($url) . "'";
        }
        $this->db->query($sql);
    }
}
