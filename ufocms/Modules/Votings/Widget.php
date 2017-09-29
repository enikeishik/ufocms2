<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\Votings;

/**
 * Widget class
 */
class Widget extends \Ufocms\Modules\Widget
{
    /**
     * @see parent
     */
    protected function setContext()
    {
        parent::setContext();
        
        $items = array();
        if (is_array($this->params)) {
            $item = $this->getItem();
        }
        
        $container = $this->core->getContainer([
            'module'        => null, 
            'params'        => null, 
            'db'            => &$this->db, 
            'core'          => &$this->core, 
            'debug'         => &$this->debug, 
            'config'        => &$this->config, 
            'tools'         => &$this->tools, 
            'moduleParams'  => &$this->moduleParams, 
        ]);
        $model = new Model($container);
        $container = $this->core->getContainer([
            'module'        => null, 
            'params'        => null, 
            'db'            => &$this->db, 
            'core'          => &$this->core, 
            'debug'         => &$this->debug, 
            'config'        => &$this->config, 
            'tools'         => &$this->tools, 
            'moduleParams'  => &$this->moduleParams, 
            'model'         => &$model, 
        ]);
        $view = new View($container);
        
        $this->context = array_merge(
            $this->context, 
            array(
                'item'          => $item, 
                'ticket'        => $model->getTicket(), 
                'showForm'      => $view->isShowForm($item, ['results' => false]), 
                'showResults'   => $view->isShowResults($item, ['results' => false]), 
            )
        );
    }
    
    /**
     * @return array|null
     */
    protected function getItem()
    {
        $now = date('Y-m-d H:i:s');
        //different SQLs because JOIN required TEMP table
        if (false === strpos($this->srcSections, ',')) {
            $section = $this->core->getSection((int) $this->srcSections, 'path,indic');
            $sql =  'SELECT Id,DateStart,DateStop,IsClosed,AnswersSeparate,CheckCaptcha,ResultsDisplay,' . 
                    'VotesCnt,Title,Image,Description,' . 
                    "'" . $section['path'] . "' AS path,'" . $this->db->addEscape($section['indic']) . "' AS indic" . 
                    ' FROM ' . C_DB_TABLE_PREFIX . 'votings AS i' . 
                    ' WHERE i.Id=' . $this->params['VotingId'] . 
                        ' i.SectionId=' . (int) $this->srcSections . 
                        ' AND i.IsDisabled=0' . 
                        " AND i.DateStart<='" . $now . "'";
            unset($section);
        } else {
            $sql =  'SELECT i.Id,i.DateStart,i.DateStop,i.IsClosed,i.AnswersSeparate,i.CheckCaptcha,' . 
                    'i.ResultsDisplay,i.VotesCnt,i.Title,i.Image,i.Description,' . 
                    's.path,s.indic' . 
                    ' FROM ' . C_DB_TABLE_PREFIX . 'votings AS i' . 
                    ' INNER JOIN ' . C_DB_TABLE_PREFIX . 'sections AS s ON i.SectionId=s.id' . 
                    ' WHERE i.Id=' . $this->params['VotingId'] . 
                        ' AND i.SectionId IN (' . $this->srcSections . ')' . 
                        ' AND i.IsDisabled=0' . 
                        " AND i.DateStart<='" . $now . "'";
        }
        $item = $this->db->getItem($sql);
        $item['Answers'] = $this->getAnswers($item['Id']);
        return $item;
    }
    
    /**
     * @return array|null
     */
    protected function getAnswers($votingId)
    {
        $sql =  'SELECT *' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'votings_answers' . 
                ' WHERE VotingId=' . $votingId . 
                ' ORDER BY OrderNumber';
        return $this->db->getItems($sql);
    }
}
