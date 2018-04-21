<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\AdminLogin;

/**
 * Module level controller
 */
class Controller extends \Ufocms\AdminModules\Controller //implements IController
{
    public function dispatch()
    {
        $this->setModel();
        if (C_ADMIN_SYS_AUTH && 'adminlogout' != $this->params->action) {
            $this->params->action = 'adminlogin';
        }
        $this->modelAction();
        $this->setView();
        $this->renderView('loginpage');
    }
}
