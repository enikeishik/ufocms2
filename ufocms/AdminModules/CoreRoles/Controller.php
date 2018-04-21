<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\CoreRoles;

/**
 * Module level controller
 */
class Controller extends \Ufocms\AdminModules\Controller //implements IController
{
    public function dispatch()
    {
        $roleId = isset($_GET['roleid']) ? (int) $_GET['roleid'] : 0;
        switch ($this->params->subModule) {
            case 'restrictions':
                $this->setModel('ModelRestrictions');
                $this->modelAction();
                $this->setView();
                $this->renderView('form', 'UIRestrictions', '&' . $this->config->paramsNames['subModule'] . '=' . $this->params->subModule . '&roleid=' . $roleId, true);
                break;
                
            case 'permsmods':
                $this->setModel('ModelPermsMods');
                $this->modelAction();
                $this->setView();
                $this->renderView('', 'UIPermissions', '&' . $this->config->paramsNames['subModule'] . '=' . $this->params->subModule . '&roleid=' . $roleId, true);
                break;
                
            case 'permscore':
                $this->setModel('ModelPermsCore');
                $this->modelAction();
                $this->setView();
                $this->renderView('', 'UIPermissions', '&' . $this->config->paramsNames['subModule'] . '=' . $this->params->subModule . '&roleid=' . $roleId, true);
                break;
                
            default:
                $this->setModel();
                $this->modelAction();
                $this->setView();
                $this->renderView();
        }
    }
}
