<?php
/**
 * @copyright
 */

namespace Ufocms\Frontend;

/**
 * Users functionality and data
 */
class Users
{
    /**
     * Куки для хранения идентификатора пользователя, чтобы не проходить процедуру авторизации каждый раз.
     * @var array
     */
    protected $cookieTicket = array(
        'name' => 'ufo_users_ticket', 
        'lifetime' => 2592000
    );
    
    /**
     * Куки для хранения отметки входа, используется при подсчете количества заходов пользователя на сайт.
     * @var array
     */
    protected $cookieEntry = array(
        'name' => 'ufo_users_entry', 
        'lifetime' => 1200
    );
    
    /**
     * Список доп. полей в БД которые можно заполнять.
     * @var array
     */
    protected $dbFields = array('IsHidden', 'ExtUID', 'Login', 'Password', 'Title', 'Image', 'Email', 'Description');
    
    /**
     * Имена полей форм (входа, выхода, регистрации, восстановления пароля).
     * @var array
     */
    protected $formFields = array(
        'login'     => 'login', 
        'password'  => 'password', 
        'from'      => 'from', 
        'email'     => 'email', 
        'title'     => 'title'
    );
    
    /**
     * @var Debug
     */
    protected $debug = null;
    
    /**
     * @var Config
     */
    protected $config = null;
    
    /**
     * @var array
     */
    protected $params = null;
    
    /**
     * @var Db
     */
    protected $db = null;
    
    /**
     * Users functional settings
     * @var array
     */
    protected $settings = null;
    
    /**
     * Current user data
     * @var array
     */
    protected $user = null;
    
    /**
     * @param Config &$config
     * @param array &$params
     * @param Db &$db
     * @param Debug &$debug = null
     */
    public function __construct(&$config, &$params, &$db, &$debug = null)
    {
        $this->config =& $config;
        $this->params =& $params;
        $this->db =& $db;
        $this->debug =& $debug;
    }
    
    /**
     * Init $settings field
     */
    protected function setSettings()
    {
        if (null !== $this->settings) {
            return;
        }
        $sql =  'SELECT *' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'users_params';
        $this->settings = $this->db->getItem($sql);
    }
    
    /**
     * Return users functional settings
     * @return array
     */
    public function getSettings()
    {
        if (null === $this->settings) {
            $this->setSettings();
        }
        return $this->settings;
    }
    
    /**
     * @return array|null
     */
    public function getCurrent()
    {
        return $this->get();
    }
    
    /**
     * Init $user field
     */
    protected function setCurrent()
    {
        if (isset($_COOKIE[$this->cookieTicket['name']]) 
        && '' != $_COOKIE[$this->cookieTicket['name']]) {
            $sql =  'SELECT *' . 
                    ' FROM ' . C_DB_TABLE_PREFIX . 'users' . 
                    ' WHERE IsDisabled=0' . 
                    " AND Ticket='" . $this->db->addEscape(substr($_COOKIE[$this->cookieTicket['name']], 0, 255)) . "'";
            $this->user = $this->db->getItem($sql);
            if (null !== $this->user) {
                $this->incEntryCounter($this->user['Id']);
            }
        }
    }
    
    /**
     * Увеличивает на единицу счетчик количества заходов пользователя на сайт.
     * @param int $userId
     */
    protected function incEntryCounter($userId)
    {
        if (isset($_COOKIE[$this->cookieEntry['name']])) {
            return;
        }
        $sql =  'UPDATE ' . C_DB_TABLE_PREFIX . 'users' . 
                ' SET EntryCounter=EntryCounter+1' . 
                ' WHERE Id=' . $userId;
        if ($this->db->execQuery($sql)) {
            setcookie($this->cookieEntry['name'], '1', time() + $this->cookieEntry['lifetime'], '/');
        }
    }
    
    /**
     * @param int $userId = 0
     * @return array|null
     */
    public function get($userId = 0)
    {
        if (0 == $userId) {
            if (null === $this->user) {
                $this->setCurrent();
            }
            return $this->user;
        } else {
            $sql =  'SELECT *' . 
                    ' FROM ' . C_DB_TABLE_PREFIX . 'users' . 
                    ' WHERE IsDisabled=0' . 
                    ' AND Id=' . $userId;
            return $this->db->getItem($sql);
        }
    }
    
    /**
     * @param string $extUid
     * @return array|null
     */
    public function getExt($extUid)
    {
        $sql =  'SELECT *' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'users' . 
                ' WHERE IsDisabled=0' . 
                " AND ExtUID='" . $this->db->addEscape($extUid) . "'";
        return $this->db->getItem($sql);
    }
    
    /**
     * Запись сеансового билета в базу и куки.
     * @param int $userId
     * @return bool
     */
    public function setTicket($userId)
    {
        $ticket = md5(mt_rand() . time());
        $sql =  'UPDATE ' . C_DB_TABLE_PREFIX . 'users' . 
                " SET DateLogin=NOW(), Ticket='" . $ticket . "'" . 
                ' WHERE Id=' . $userId;
        if (!$this->db->execQuery($sql)) {
            return false;
        }
        
        if (
            !setcookie(
                $this->cookieTicket['name'], 
                $ticket, 
                time() + $this->cookieTicket['lifetime'], 
                '/'
            )
        ) {
            $this->unsetTicket($userId);
            return false;
        }
        return true;
    }
    
    /**
     * Сброс сеансового билета в базе и куках.
     * @param int $userId
     * @return bool
     */
    public function unsetTicket($userId)
    {
        $sql =  'UPDATE ' . C_DB_TABLE_PREFIX . 'users' . 
                " SET Ticket=''" . 
                ' WHERE Id=' . $userId;
        if (!$this->db->execQuery($sql)) {
            return false;
        }
        
        setcookie($this->cookieTicket['name'], '', time() - 1000000, '/');
        return true;
    }
    
    /**
     * Добавление нового пользователя.
     * @param array<name => value> $fields
     * @return bool
     */
    public function add(array $fields)
    {
        $addFields = '';
        $addValues = '';
        $loginSet = false;
        $passwordSet = false;
        $extUidSet = false;
        foreach ($this->dbFields as $field) {
            if (array_key_exists($field, $fields)) {
                $addFields .= ',`' . $field . '`';
                $addValues .= ",'" . $this->db->addEscape($fields[$field]) . "'";
                if ('Login' == $field) {
                    $loginSet = true;
                }
                if ('Password' == $field) {
                    $passwordSet = true;
                }
                if ('ExtUID' == $field) {
                    $extUidSet = true;
                }
            }
        }
        if (!$extUidSet && (!$loginSet || !$passwordSet)) {
            return false;
        }
        $sql =  'INSERT INTO ' . C_DB_TABLE_PREFIX . 'users' . 
                ' (DateCreate,IsDisabled' . $addFields . ')' . 
                ' VALUES(NOW(),' . (int) $this->settings['IsModerated'] . 
                $addValues . ')';
        return $this->db->execQuery($sql);
    }
    
    /**
     * Обновление данных пользователя.
     * @param int $id
     * @param array<name => value> $fields
     * @return bool
     */
    public function update($id, array $fields)
    {
        $sql = '';
        foreach ($this->dbFields as $field) {
            if (array_key_exists($field, $fields)) {
                $sql .= ',`' . $field . "`='" . $this->db->addEscape($fields[$field]) . "'";
            }
        }
        if ('' == $sql) {
            return false;
        }
        $sql =  'UPDATE ' . C_DB_TABLE_PREFIX . 'users' . 
                ' SET ' . substr($sql, 1) . 
                ' WHERE Id=' . $id;
        return $this->db->execQuery($sql);
    }
    
    /**
     * Проверка наличия логина.
     * @param string $login
     * @return bool
     */
    public function loginExists($login)
    {
        $sql =  'SELECT COUNT(*) AS Cnt FROM ' . C_DB_TABLE_PREFIX . 'users' . 
                " WHERE Login='" . $this->db->addEscape($login) . "'";
        return 0 < $this->db->getValue($sql, 'Cnt');
    }
    
    /**
     * Получение названия и времени жизни куков сеансового билета.
     * @return array
     */
    public function getCookieTicket()
    {
        return $this->cookieTicket;
    }
    
    /**
     * Получение названия и времени жизни куков подсчета количества заходов.
     * @return array
     */
    public function getCookieEntry()
    {
        return $this->cookieEntry;
    }
    
    /**
     * Получение списка имен полей форм.
     * @return array
     */
    public function getFormFields()
    {
        return $this->formFields;
    }
}
