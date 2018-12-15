<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\SysULogin;

/**
 * Main module model
 */
class Model extends \Ufocms\Modules\Model //implements IModel
{
    /**
     * @var string
     */
    protected $serviceUrl = 'http://ulogin.ru/token.php';
    
    /**
     * @var int
     */
    protected $serviceTimeout = 1;
    
    /**
     * @var string
     */
    protected $from = null;
    
    /**
     * @var string
     */
    protected $error = null;
    
    /**
     * @see parent
     */
    protected function init()
    {
        parent::init();
        $this->from = '/';
        $this->error = '';
        $this->actionResult = array(
            'method'    => false, 
            'data'      => false, 
            'service'   => false, 
        );
    }
    
    /**
     * Процедура сброса билета текущего пользователя.
     */
    public function ulogin()
    {
        if (!isset($_SERVER['REQUEST_METHOD']) || 0 != strcasecmp('POST', $_SERVER['REQUEST_METHOD'])) {
            $this->error = 'Wrong method';
            return false;
        }
        $this->actionResult['method'] = true;
        
        //проверяем наличие входных данных
        if (!array_key_exists('token', $_POST)) {
            $this->error = 'Token data not exists';
            return false;
        }
        $this->actionResult['data'] = true;
        
        $context = stream_context_create(array('http' => array('header' => 'Connection: close', 'timeout' => $this->serviceTimeout)));
        $url = $this->serviceUrl . '?token=' . $_POST['token'] . '&host=' . $_SERVER['HTTP_HOST'];
        if (false === $s = @file_get_contents($url, false, $context)) {
            $this->error = 'Load data failed';
            return false;
        }
        $extUser = @json_decode($s, true);
        if (!is_array($extUser)) {
            $this->error = 'JSON decode failed';
            return false;
        }
        if (array_key_exists('error', $extUser)) {
            $this->error = $extUser['error'];
            return false;
        }
        if (!array_key_exists('identity', $extUser)
        && (!array_key_exists('network', $extUser) || !array_key_exists('uid', $extUser))) {
            $this->error = 'Network and/or identity not set';
            return false;
        }
        //$extUser['identity'] - уникальная строка определяющая конкретного пользователя соц. сети
        //$extUser['network'] - соц. сеть, через которую авторизовался пользователь
        //$extUser['uid'] - идентификатор пользователя в соц. сети
        //$extUser['first_name'] - имя пользователя
        //$extUser['last_name'] - фамилия пользователя
        
        if (array_key_exists('identity', $extUser)) {
            $extUid = $extUser['identity'];
        } else {
            $extUid = $extUser['uid'] . '@' . $extUser['network'];
        }
        $extTitle = '';
        if (array_key_exists('first_name', $extUser)) {
            $extTitle .= $extUser['first_name'] . ' ';
        }
        if (array_key_exists('last_name', $extUser)) {
            $extTitle .= $extUser['last_name'];
        }
        $users = $this->core->getUsers();
        if (null === $user = $users->getExt($extUid)) {
            if (!$users->add(array('ExtUID' => $extUid, 'Title' => trim($extTitle)))) {
                $this->error = 'Create local user for `' . $extUid . '` failed';
                return false;
            }
            if (null === $user = $users->getExt($extUid)) {
                $this->error = 'Local user for `' . $extUid . '` disabled or not approved yet';
                return false;
            }
        } else {
            if (!$users->update($user['Id'], array('Title' => trim($extTitle)))) {
                $this->error = 'Update local user info for `' . $extUid . '` failed';
                return false;
            }
        }
        
        $users->setTicket($user['Id']);
        
        $this->from = isset($_GET['from']) ? $_GET['from'] : '/';
        
        $this->actionResult['service'] = true;
        return true;
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
