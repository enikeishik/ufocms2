<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\Votings;

/**
 * News module model class
 */
class ModelAnswers extends \Ufocms\AdminModules\Model
{
    const ERR_VOTING_UNSET = 'Voting is unset';
    
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
        $this->itemsTable = 'votings_answers';
        $this->itemDisabledField = '';
        $this->defaultSort = 'OrderNumber';
        $this->votingId = isset($_GET['votingid']) ? (int) $_GET['votingid'] : 0;
        if (0 == $this->votingId) {
            throw new \Exception(self::ERR_VOTING_UNSET);
        }
        $this->primaryFilter .= ' AND VotingId=' . $this->votingId;
        
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
    
    protected function setItems()
    {
        $section = $this->core->getSection();
        $sectionPath = $section['path'];
        unset($section);
        parent::setItems();
        foreach ($this->items as &$item) {
            $item['path'] = $sectionPath . $item['Id'];
        }
        unset($item);
    }
    
    protected function setFields()
    {
        $this->fields = array(
            array('Type' => 'int',          'Name' => 'Id',                 'Value' => 0,                           'Title' => 'id',                        'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => false),
            array('Type' => 'list',         'Name' => 'SectionId',          'Value' => $this->params->sectionId,    'Title' => 'Раздел',                    'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => true,     'Required' => true,     'Items' => 'getSections',   'Unchange' => true),
            array('Type' => 'list',         'Name' => 'VotingId',           'Value' => 0,                           'Title' => 'Голосование',               'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => true,     'Required' => true,     'Items' => 'getVotings',    'Unchange' => true),
            array('Type' => 'int',          'Name' => 'OrderNumber',        'Value' => 0,                           'Title' => 'Порядок',                   'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => true),
            array('Type' => 'int',          'Name' => 'VotesCnt',           'Value' => 0,                           'Title' => 'Голосов',                   'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => false),
            array('Type' => 'text',         'Name' => 'Title',              'Value' => '',                          'Title' => 'Заголовок',                 'Filter' => true,   'Show' => true,     'Sort' => true,     'Edit' => true,     'Required' => true,     'Autofocus' => true),
            array('Type' => 'image',        'Name' => 'Image',              'Value' => '',                          'Title' => 'Картинка',                  'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => true),
            array('Type' => 'bigtext',      'Name' => 'Description',        'Value' => '',                          'Title' => 'Описание',                  'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => true),
        );
    }
    
    /**
     * @return array
     */
    public function getVotings()
    {
        $sql =  'SELECT Id AS Value, Title' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'votings' . 
                ' WHERE Id=' . $this->votingId;
        return $this->db->getItems($sql);
    }
}
