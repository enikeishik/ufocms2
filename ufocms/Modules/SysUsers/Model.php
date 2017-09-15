<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\SysUsers;

/**
 * Main module model
 */
class Model extends \Ufocms\Modules\Model //implements IModel
{
    /**
     * @var \Ufocms\Frontend\Users
     */
    protected $users = null;
    
    /**
     * @var string
     */
    protected $from = null;
    
    /**
     * @var string
     */
    protected $error = null;
    
    /**
     * Метки в тексте и теме писем (восстановления пароля).
     * @var array
     */
    protected $messageMarks = array('{SITE}', '{DT}', '{IP}', '{LOGIN}', '{PASSWORD}', '{TITLE}', '{EMAIL}');
    
    protected function init()
    {
        $this->users = $this->core->getUsers();
        $this->from = '/';
        $this->error = '';
        $this->actionResult = array(
            'method'    => '', 
            'referer'   => false, 
            'form'      => false, 
            'human'     => false, 
            'correct'   => false, 
            'db'        => false, 
            'enabled'   => false, 
            'cookie'    => false, 
            'email'     => false, 
        );
        $this->getSettings();
    }
    
    public function getSettings()
    {
        if (null !== $this->settings) {
            return $this->settings;
        }
        
        //TODO: use site params
        $this->settings = array_merge(
            array(
                'IsReferer'         => true, 
                'IsCaptcha'         => true,
                'IsCaptchaOnLogin'  => false,
            ), 
            $this->users->getSettings()
        );
        
        //TODO: use site params
        $site = $this->core->getSite();
        if ($this->settings['IsGlobalAE'] 
        && array_key_exists('SiteEMail', $site)) {
            $this->settings['AdminEmail'] = $site['SiteEMail'];
        }
        if ($this->settings['IsGlobalAEF'] 
        && array_key_exists('SiteEMailFrom', $site)) {
            $this->settings['AdminEmailFrom'] = $site['SiteEMailFrom'];
        }
        
        $this->params->pageSize = $this->settings['PageLength'];
        
        return $this->settings;
    }
    
    public function getItems()
    {
        if (null !== $this->items) {
            return $this->items;
        }
        $sqlBase =  ' FROM ' . C_DB_TABLE_PREFIX . 'users' . 
                    ' WHERE IsDisabled=0 AND IsHidden=0';
        $sql =  'SELECT *' . 
                $sqlBase;
        switch ($this->settings['Orderby']) {
            case 1:
                $sql .= ' ORDER BY Title DESC';
                break;
            case 2:
                $sql .= ' ORDER BY DateCreate';
                break;
            case 3:
                $sql .= ' ORDER BY DateCreate DESC';
                break;
            default:
                $sql .= ' ORDER BY Title';
        }
        $sql .= ' LIMIT ' . (($this->params->page - 1) * $this->params->pageSize) . 
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
        $sql =  'SELECT *' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'users' . 
                ' WHERE Id=' . $this->params->itemId . 
                ' AND IsDisabled=0 AND IsHidden=0';
        $this->item = $this->db->getItem($sql);
        if (null !== $this->item) {
            $this->item = array_merge(
                $this->item, 
                array('Groups' => $this->getUserGroups($this->params->itemId))
            );
        }
        return $this->item;
    }
    
    /**
     * @return array<int GroupId>
     */
    protected function getUserGroups($userId)
    {
        $sql = 'SELECT GroupId' . 
               ' FROM ' . C_DB_TABLE_PREFIX . 'users_groups_relations' . 
               ' WHERE UserId=' . $userId;
        return $this->db->getValues($sql, 'GroupId');
    }
    
    /**
     * Процедура аутентификации и авторизации пользователя.
     */
    public function login()
    {
        $this->actionResult['method'] = 'login';
        
        //проверяем корректность источника
        if ($this->settings['IsReferer']) {
            if (!isset($_SERVER['HTTP_REFERER'])) {
                return;
            }
            if (false === strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST'])) {
                return;
            }
        }
        $this->actionResult['referer'] = true;
        
        //проверяем наличие отправленных полей формы
        $formFields = $this->users->getFormFields();
        if (!isset($_POST[$formFields['login']]) 
        || !isset($_POST[$formFields['password']])) {
            $this->error = 'Login or password unset';
            return;
        }
        $this->actionResult['form'] = true;
        
        //проверяем что отправил форму человек
        if ($this->settings['IsCaptchaOnLogin']) {
            if (!$this->tools->getCaptcha()->check()) {
                return;
            }
        }
        $this->actionResult['human'] = true;
        
        //проверяем корректность данных
        $login = htmlspecialchars(substr($_POST[$formFields['login']], 0, 255));
        if ('' == $login || $login != $_POST[$formFields['login']]) {
            $this->error = 'Login parameter contains bad characters';
            return;
        }
        $password = substr($_POST[$formFields['password']], 0, 255);
        if ('' == $password || $password != $_POST[$formFields['password']]) {
            $this->error = 'Password parameter too long';
            return;
        }
        
        //проверяем наличие пользователя с такими логином и паролем
        $sql =  'SELECT Id, IsDisabled FROM ' . C_DB_TABLE_PREFIX . 'users' . 
                " WHERE Login='" . $this->db->addEscape($login) . "'" . 
                " AND Password='" . $this->db->addEscape($password) . "'";
        $item = $this->db->getItem($sql);
        if (null === $item) {
            $this->error = 'Login or password incorrect';
            return;
        }
        $this->actionResult['correct'] = true;
        
        //проверяем не заблокирован ли пользователь
        if ($item['IsDisabled']) {
            $this->error = 'User blocked';
            return;
        }
        $this->actionResult['enabled'] = true;
        
        //устанавливаем сеансовый билет
        if (!$this->users->setTicket($item['Id'])) {
            return;
        }
        $this->actionResult['db'] = true;
        $this->actionResult['cookie'] = true;
        
        if (isset($_POST[$formFields['from']]) 
        && $this->tools->isPath($_POST[$formFields['from']], false)) {
            $this->from = $_POST[$formFields['from']];
        }
    }
    
    /**
     * Процедура сброса билета текущего пользователя.
     */
    public function logout()
    {
        $this->actionResult['method'] = 'logout';
        
        //проверяем корректность источника
        if ($this->settings['IsReferer']) {
            if (!isset($_SERVER['HTTP_REFERER'])) {
                return;
            }
            if (false === strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST'])) {
                return;
            }
        }
        $this->actionResult['referer'] = true;
        $this->actionResult['form'] = true;
        $this->actionResult['human'] = true;
        $this->actionResult['enabled'] = true;
        
        $currentUser = $this->users->getCurrent();
        if (null === $currentUser) {
            $this->error = 'Current user not set';
            return;
        }
        $this->actionResult['correct'] = true;
        
        //сбрасываем сеансовый билет
        if (!$this->users->unsetTicket($currentUser['Id'])) {
            return;
        }
        $this->actionResult['db'] = true;
        $this->actionResult['cookie'] = true;
        
        $formFields = $this->users->getFormFields();
        if (isset($_POST[$formFields['from']]) 
        && $this->tools->isPath($_POST[$formFields['from']], false)) {
            $this->from = $_POST[$formFields['from']];
        }
    }
    
    /**
     * Процедура регистрации нового пользователя.
     */
    public function register()
    {
        $this->actionResult['method'] = 'register';
        
        //проверяем корректность источника
        if ($this->settings['IsReferer']) {
            if (!isset($_SERVER['HTTP_REFERER'])) {
                return;
            }
            if (false === strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST'])) {
                return;
            }
        }
        $this->actionResult['referer'] = true;
        
        $formFields = $this->users->getFormFields();
        
        //проверяем наличие отправленных полей формы
        if (!isset($_POST[$formFields['login']]) 
        || !isset($_POST[$formFields['password']]) 
        || !isset($_POST[$formFields['password'] . '2']) 
        || !isset($_POST[$formFields['email']])) {
            $this->error = 'Login, password or email unset';
            return;
        }
        $this->actionResult['form'] = true;
        
        //проверяем что отправил форму человек
        if ($this->settings['IsCaptcha']) {
            if (!$this->tools->getCaptcha()->check()) {
                return;
            }
        }
        $this->actionResult['human'] = true;
        
        //проверяем корректность данных
        $login = htmlspecialchars(substr($_POST[$formFields['login']], 0, 255));
        if ('' == $login || $login != $_POST[$formFields['login']]) {
            $this->error = 'Login parameter contains bad characters';
            return;
        }
        if ($this->users->loginExists($login)) {
            $this->error = 'Login already exists';
            return;
        }
        
        $password = substr($_POST[$formFields['password']], 0, 255);
        if ('' == $password || $password != $_POST[$formFields['password']]) {
            $this->error = 'Password parameter too long';
            return;
        }
        if ($password != $_POST[$formFields['password'] . '2']) {
            $this->error = 'Password and password2 parameters not the same';
            return;
        }
        
        if (isset($_POST[$formFields['title']])) {
            $title = htmlspecialchars(substr($_POST[$formFields['title']], 0, 255));
        } else {
            $title = $login;
        }
        
        $email = substr($_POST[$formFields['email']], 0, 255);
        if ('' == $email || !$this->tools->isEmail($email)) {
            $this->error = 'Email parameter not correct';
            return;
        }
        $this->actionResult['correct'] = true;
        $this->actionResult['enabled'] = true;
        
        //TODO: may be use $this->users->add ?
        $sql =  'INSERT INTO ' . C_DB_TABLE_PREFIX . 'users' . 
                ' (DateCreate,IsDisabled,Login,Password,Title,Email)' . 
                ' VALUES(NOW(),' . (int) $this->settings['IsModerated'] . ',' . 
                "'" . $this->db->addEscape($login) . "'," . 
                "'" . $this->db->addEscape($password) . "'," . 
                "'" . $this->db->addEscape($title) . "'," . 
                "'" . $email . "'" . ')';
        if (!$this->db->query($sql)) {
            $this->error = 'DB error';
            return;
        }
        $this->actionResult['db'] = true;
    }
    
    /**
     * Процедура высылки пароля на email зарегистрированного пользователя.
     */
    public function recover()
    {
        $this->actionResult['method'] = 'recover';
        
        //проверяем корректность источника
        if ($this->settings['IsReferer']) {
            if (!isset($_SERVER['HTTP_REFERER'])) {
                return;
            }
            if (false === strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST'])) {
                return;
            }
        }
        $this->actionResult['referer'] = true;
        
        $formFields = $this->users->getFormFields();
        
        if (!isset($_POST[$formFields['login']])) {
            $this->error = 'Login unset';
            return;
        }
        $this->actionResult['form'] = true;
        
        //проверяем что отправил форму человек
        if ($this->settings['IsCaptcha']) {
            if (!$this->tools->getCaptcha()->check()) {
                return;
            }
        }
        $this->actionResult['human'] = true;
        
        $login = htmlspecialchars(substr($_POST[$formFields['login']], 0, 255));
        if ('' == $login || $login != $_POST[$formFields['login']]) {
            $this->error = 'Login parameter contains bad characters';
            return;
        }
        $this->actionResult['correct'] = true;
        $this->actionResult['enabled'] = true;
        
        $sql =  'SELECT Password,Title,Email FROM ' . C_DB_TABLE_PREFIX . 'users' . 
                " WHERE Login='" . $this->db->addEscape($login) . "'";
        if (null === $item = $this->db->getItem($sql)) {
            $this->error = 'Login not exists';
            return;
        }
        $this->actionResult['db'] = true;
        
        $values = array(
            isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME'], 
            date('Y.m.d H:i:s'), 
            isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'IP not set', 
            $login, 
            $item['Password'], 
            $item['Title'], 
            $item['Email']
        );
        $this->actionResult['email'] = 
            $this->tools->getMessenger($this->config)->sendEmail(
                $item['Email'], 
                str_replace($this->messageMarks, $values, $this->settings['RecoverySubject']), 
                str_replace($this->messageMarks, $values, $this->settings['RecoveryMessage'])
            );
    }
    
    /**
     * Возвращает путь страницы, с которой была отправлена форма.
     * @return string
     */
    public function getFrom()
    {
        return $this->from;
    }
    
    /**
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }
}
