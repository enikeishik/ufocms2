<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\AdminLogin;

/**
 * Stateless model class
 */
class Model extends \Ufocms\AdminModules\StatelessModel
{
    /**
     * @see parent
     */
    protected function init()
    {
        $this->config->registerAction('adminlogin');
        $this->config->registerMakeAction('adminlogin');
        $this->config->registerAction('adminlogout');
        $this->config->registerMakeAction('adminlogout');
    }
    
    /**
     * Make login of administrator.
     */
    public function adminlogin()
    {
        if (C_ADMIN_SYS_AUTH) {
            if (0 != $userId = $this->authentication($_SERVER['REMOTE_USER'])) {
                if ($this->authorization($userId)) {
                    $this->core->getUsers()->setTicket($userId);
                    $this->core->riseError(302, 'Authentication and authorization passed', '#');
                }
            }
            $this->adminlogout();
            
        } else {
            if (isset($_POST['login']) && isset($_POST['password'])) {
                if (0 != $userId = $this->authentication($_POST['login'], $_POST['password'])) {
                    if ($this->authorization($userId)) {
                        $this->core->getUsers()->setTicket($userId);
                        $this->core->riseError(302, 'Authentication and authorization passed', '#');
                    }
                }
            }
            
        }
    }
    
    /**
     * Make logout of administrator.
     */
    public function adminlogout()
    {
        $this->core->getUsers()->unsetTicket($this->core->getUsers()->getCurrent()['Id']);
        if (C_ADMIN_SYS_AUTH) {
            $this->core->riseError(401, 'Logout');
        } else {
            $this->core->riseError(302, 'Authentication required', '?rnd=' . time());
        }
    }
    
    /**
     * @param string $login
     * @param string $password = null
     * @return int
     */
    protected function authentication($login, $password = null)
    {
        //проверяем наличие пользователя с такими логином и паролем
        $sql =  'SELECT Id, IsDisabled FROM ' . C_DB_TABLE_PREFIX . 'users' . 
                " WHERE Login='" . $this->db->addEscape($login) . "'" . 
                (!is_null($password) ? " AND Password='" . $this->db->addEscape($password) . "'" : '');
        $item = $this->db->getItem($sql);
        if (null === $item) {
            $this->result = 'Login or password incorrect';
            return 0;
        }
        
        //проверяем не заблокирован ли пользователь
        if ($item['IsDisabled']) {
            $this->result = 'User blocked';
            return 0;
        }
        
        return (int) $item['Id'];
    }
    
    /**
     * @param int $userId
     * @return bool
     */
    protected function authorization($userId)
    {
        $sql =  'SELECT COUNT(*) AS Cnt' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'users_roles_relations AS urr' . 
                ' INNER JOIN ' . C_DB_TABLE_PREFIX . 'users_roles AS ur ON urr.RoleId=ur.Id' . 
                ' WHERE urr.UserId=' . $userId;
        $cnt = $this->db->getValue($sql, 'Cnt');
        if (0 < $cnt) {
            return true;
        } else {
            $this->result = 'User has no assigned roles';
            return false;
        }
    }
}
