<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\Votings;

/**
 * News module model class
 */
class ModelVotes extends \Ufocms\AdminModules\Model
{
    /**
     * @var int
     */
    protected $votingId = null;
    
    /**
     * @see parent
     */
    protected function init()
    {
        parent::init();
        $this->itemsTable = 'votings_log';
        $this->itemDisabledField = '';
        $this->defaultSort = 'DateCreate DESC';
        $this->canCreateItems = false;
        $this->canUpdateItems = false;
        $this->canDeleteItems = false;
        $this->params->itemId = 0;
        $this->votingId = isset($_GET['votingid']) ? (int) $_GET['votingid'] : 0;
        if (0 != $this->votingId) {
            $this->primaryFilter .= ' AND VotingId=' . $this->votingId;
        }
        
        //generating master object
        $params = clone $this->params;
        $params->itemId = $this->votingId;
        $container = $this->core->getContainer([
            'debug'         => &$this->debug, 
            'config'        => &$this->config, 
            'params'        => $params, 
            'db'            => &$this->db, 
            'core'          => &$this->core, 
            'module'        => &$this->module, 
            'tools'         => &$this->tools, 
            'moduleParams'  => &$this->moduleParams, 
        ]);
        $this->master = new Model($container);
    }
    
    /**
     * @see parent
     */
    protected function setFields()
    {
        $this->fields = array(
            array('Type' => 'int',          'Name' => 'Id',             'Value' => 0,   'Title' => 'id',                'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => false),
            array('Type' => 'int',          'Name' => 'UserId',         'Value' => 0,   'Title' => 'Пользователь',      'Filter' => true,   'Show' => true,     'Sort' => true,     'Edit' => false,    'Class' => 'small'),
            array('Type' => 'int',          'Name' => 'SectionId',      'Value' => 0,   'Title' => 'Раздел',            'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => false,    'Class' => 'small'),
            array('Type' => 'int',          'Name' => 'VotingId',       'Value' => 0,   'Title' => 'Вопрос',            'Filter' => true,   'Show' => true,     'Sort' => true,     'Edit' => false,    'Class' => 'small'),
            array('Type' => 'int',          'Name' => 'AnswerId',       'Value' => 0,   'Title' => 'Ответ',             'Filter' => true,   'Show' => true,     'Sort' => true,     'Edit' => false,    'Class' => 'small'),
            array('Type' => 'datetime',     'Name' => 'DateCreate',     'Value' => '',  'Title' => 'Дата создания',     'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => false,    'Class' => 'small'),
            array('Type' => 'ip',           'Name' => 'IP',             'Value' => '',  'Title' => 'IP',                'Filter' => true,   'Show' => true,     'Sort' => true,     'Edit' => false,    'Class' => 'small'),
            array('Type' => 'text',         'Name' => 'UA',             'Value' => '',  'Title' => 'UserAgent',         'Filter' => true,   'Show' => true,     'Sort' => true,     'Edit' => false,    'Class' => 'xsmall'),
        );
    }
    
    /**
     * @see parent
     */
    protected function getItemsSqlFields($table = '')
    {
        return 'Id,UserId,VotingId,AnswerId,DateCreate,INET_NTOA(IP) AS IP,UA';
    }
    
    /**
     * @param string $name
     * @param mixed $value
     * @return string
     */
    protected function getSqlConditionIp($name, $value)
    {
        if ('' == $value) {
            return '`' . $name . "`";
        }
        return '`' . $name . "`=INET_ATON('" . $this->db->addEscape($value) . "')";
    }
}
