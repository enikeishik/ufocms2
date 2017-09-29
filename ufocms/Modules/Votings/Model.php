<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\Votings;

/**
 * Main module model
 */
class Model extends \Ufocms\Modules\Model //implements IModel
{
    const COOKIE_NAME = 'ufocms_votings_';
    const COOKIE_VALUE = '1';
    const COOKIE_EXPIRE = 3600 * 24 * 999;
    
    /**
     * @var int
     */
    protected $userId = null;
    
    /**
     * @var int
     */
    protected $answerId = null;
    
    /**
     * @see parent
     */
    protected function init()
    {
        parent::init();
        $this->actionResult = [
            'referer'   => false, 
            'cookie'    => false, 
            'ip'        => false, 
            'user'      => false, 
            'ticket'    => false, 
            'captcha'   => false, 
            'data'      => false, 
            'voted'     => false, 
        ];
    }
    
    public function getSettings()
    {
        if (null !== $this->settings) {
            return $this->settings;
        }
        $sql =  'SELECT *' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'votings_settings' . 
                ' WHERE SectionId=' . $this->params->sectionId;
        $this->settings = $this->db->getItem($sql);
        $this->params->pageSize = $this->settings['PageLength'];
        return $this->settings;
    }
    
    /**
     * @return array|null
     */
    public function getItems()
    {
        if (null !== $this->items) {
            return $this->items;
        }
        $now = date('Y-m-d H:i:s');
        $sqlBase =  ' FROM ' . C_DB_TABLE_PREFIX . 'votings' . 
                    ' WHERE SectionId=' . $this->params->sectionId . 
                    ' AND IsDisabled=0' . 
                    ' AND IsClosed=0' . 
                    " AND DateStart<='" . $now . "'";
        //TODO
        $sqlOrder = 'DateStart DESC';
        $sql =  'SELECT *' . 
                $sqlBase;
        $sql .= ' ORDER BY ' . $sqlOrder . 
                ' LIMIT ' . (($this->params->page - 1) * $this->params->pageSize) . 
                    ', ' . $this->params->pageSize;
        $this->itemsCount = $this->db->getValue('SELECT COUNT(*) AS Cnt' . $sqlBase, 'Cnt');
        if (0 < $this->itemsCount) {
            $this->items = $this->db->getItems($sql);
        } else {
            $this->items = array();
        }
        return $this->items;
    }
    
    public function getItem()
    {
        if (null !== $this->item) {
            return $this->item;
        }
        $now = date('Y-m-d H:i:s');
        $sql =  'SELECT *' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'votings' . 
                ' WHERE Id=' . $this->params->itemId . 
                ' AND IsDisabled=0' . 
                " AND DateStart<='" . $now . "'";
        $this->item = $this->db->getItem($sql);
        $this->item['Answers'] = $this->getAnswers();
        return $this->item;
    }
    
    /**
     * @return array|null
     */
    protected function getAnswers()
    {
        $sql =  'SELECT *' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'votings_answers' . 
                ' WHERE VotingId=' . $this->params->itemId . 
                ' ORDER BY OrderNumber';
        return $this->db->getItems($sql);
    }
    
    /**
     * @param int $votingId
     * @return bool
     */
    public function isVoted($votingId)
    {
        return isset($_COOKIE[self::COOKIE_NAME . $votingId]);
    }
    
    /**
     * @return string
     */
    public function getTicket()
    {
        return md5($_SERVER['REMOTE_ADDR'] . (isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '') . date('Ymd'));
    }
    
    /**
     * Model action method.
     */
    public function vote()
    {
        $this->getItem();
        $this->userId = 0;
        $user = $this->core->getUsers()->getCurrent();
        if (null !== $user) {
            $this->userId = $user['Id'];
        }
        unset($user);
        
        if (!$this->checkVoter()) {
            return;
        }
        
        if ($this->saveVote()) {
            $this->setVoted();
        }
    }
    
    protected function setVoted()
    {
        //use path=/ for widgets access
        setcookie(
            self::COOKIE_NAME . $this->params->itemId, 
            self::COOKIE_VALUE, 
            time() + self::COOKIE_EXPIRE, 
            '/'
        );
    }
    
    /**
     * @return bool
     */
    protected function saveVote()
    {
        $sql =  'INSERT INTO ' . C_DB_TABLE_PREFIX . 'votings_log' . 
                ' (UserId, SectionId, VotingId, AnswerId, DateCreate, IP, UA)' . 
                ' VALUES(' . 
                    $this->userId . ', ' . 
                    $this->params->sectionId . ', ' . 
                    $this->params->itemId . ', ' . 
                    $this->answerId . ', ' . 
                    'NOW(), ' . 
                    "INET_ATON('" . $this->db->addEscape($_SERVER['REMOTE_ADDR']) . "'), " . 
                    "'" . (isset($_SERVER['HTTP_USER_AGENT']) ? $this->db->addEscape($_SERVER['HTTP_USER_AGENT']) : '') . "'" . 
                ')';
        if (!$this->db->query($sql)) {
            return false;
        }
        $sql =  'UPDATE ' . C_DB_TABLE_PREFIX . 'votings_answers' . 
                ' SET VotesCnt=VotesCnt+1' . 
                ' WHERE Id=' . $this->answerId;
        if (!$this->db->query($sql)) {
            return false;
        }
        $sql =  'UPDATE ' . C_DB_TABLE_PREFIX . 'votings' . 
                ' SET VotesCnt=VotesCnt+1' . 
                ' WHERE Id=' . $this->params->itemId;
        if (!$this->db->query($sql)) {
            return false;
        }
        $this->actionResult['voted'] = true;
        return true;
    }
    
    /**
     * @return bool
     */
    protected function checkVoter()
    {
        if ($this->item['CheckReferer']) {
            if (!$this->checkReferer()) {
                return false;
            }
        }
        $this->actionResult['referer'] = true;
        
        if ($this->item['CheckCookie']) {
            if (!$this->checkCookie()) {
                return false;
            }
        }
        $this->actionResult['cookie'] = true;
        
        if ($this->item['CheckIP']) {
            if (!$this->checkIP()) {
                return false;
            }
        }
        $this->actionResult['ip'] = true;
        
        if ($this->item['CheckUser']) {
            if (!$this->checkUser()) {
                return false;
            }
        }
        $this->actionResult['user'] = true;
        
        if ($this->item['CheckTicket']) {
            if (!$this->checkTicket()) {
                return false;
            }
        }
        $this->actionResult['ticket'] = true;
        
        if ($this->item['CheckCaptcha']) {
            if (!$this->tools->getCaptcha()->check()) {
                return false;
            }
        }
        $this->actionResult['captcha'] = true;
        
        if (!$this->checkData()) {
            return false;
        }
        $this->actionResult['data'] = true;
        
        return true;
    }
    
    /**
     * @return bool
     */
    protected function checkReferer()
    {
        return
            isset($_SERVER['HTTP_REFERER'])
            && false !== strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST']);
    }
    
    /**
     * @return bool
     */
    protected function checkCookie()
    {
        return !$this->isVoted($this->params->itemId);
    }
    
    /**
     * @return bool
     */
    protected function checkIP()
    {
        $sql =  'SELECT COUNT(*) AS Cnt' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'votings_log' . 
                ' WHERE VotingId=' . $this->params->itemId . 
                " AND IP=INET_ATON('" . $this->db->addEscape($_SERVER['REMOTE_ADDR']) . "')";
        return 0 == $this->db->getValue($sql, 'Cnt');
    }
    
    /**
     * @return bool
     */
    protected function checkUser()
    {
        if (0 == $this->userId) {
            return false;
        }
        $sql =  'SELECT COUNT(*) AS Cnt' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'votings_log' . 
                ' WHERE UserId=' . $this->userId . 
                ' AND VotingId=' . $this->params->itemId;
        return 0 == $this->db->getValue($sql, 'Cnt');
    }
    
    /**
     * @return bool
     */
    protected function checkTicket()
    {
        return isset($_POST['ticket']) && $_POST['ticket'] == $this->getTicket();
    }
    
    /**
     * @return bool
     */
    protected function checkData()
    {
        //check data existings
        if (!isset($_POST['answer'])) {
            return false;
        }
        $this->answerId = (int) $_POST['answer'];
        //check data correct
        $sql =  'SELECT COUNT(*) AS Cnt' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'votings_answers' . 
                ' WHERE Id=' . $this->answerId . 
                ' AND VotingId=' . $this->params->itemId . 
                ' AND SectionId=' . $this->params->sectionId;
        return 1 == $this->db->getValue($sql, 'Cnt');
    }
}
