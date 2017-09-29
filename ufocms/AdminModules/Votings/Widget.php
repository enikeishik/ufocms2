<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\Votings;

/**
 * Widget class
 */
class Widget extends \Ufocms\AdminModules\Widget
{
    /**
     * @see parent
     */
    protected function init()
    {
        parent::init();
        $this->sourceDepends = true;
    }
    
    /**
     * @see parent
     */
    protected function setFields()
    {
        $this->fields = array(
            array('Type' => 'list',  'Name' => 'VotingId',  'Value' => 0,   'Title' => 'Опрос',    'Edit' => true,     'Items' => 'getVotings'),
        );
    }
    
    /**
     * @return array
     */
    protected function getVotings()
    {
        $all = ['Value' => 0, 'Title' => 'Выберите раздел-источник'];
        if (0 == $this->moduleParams['SrcSections']) {
            return array($all);
        }
        
        $now = date('Y-m-d H:i:s');
        if (is_array($this->moduleParams['SrcSections'])) {
            $sql =  'SELECT Id AS Value, Title' . 
                    ' FROM ' . C_DB_TABLE_PREFIX . 'votings' . 
                    ' WHERE SectionId IN (' . implode(',', $this->moduleParams['SrcSections']) . ')' . 
                    ' AND IsDisabled=0' . 
                    ' AND IsClosed=0' . 
                    " AND DateStart<='" . $now . "'";
        } else {
            $sql =  'SELECT Id AS Value, Title' . 
                    ' FROM ' . C_DB_TABLE_PREFIX . 'votings' . 
                    ' WHERE SectionId=' . $this->moduleParams['SrcSections'] . 
                    ' AND IsDisabled=0' . 
                    ' AND IsClosed=0' . 
                    " AND DateStart<='" . $now . "'";
        }
        return $this->db->getItems($sql);
    }
}
