<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\CoreTools;

/**
 * Module level controller
 */
class Controller extends \Ufocms\AdminModules\Controller //implements IController
{
    public function dispatch()
    {
        if ('backup' == $this->params->subModule) {
            $this->setModel('ModelBackup');
            $this->modelAction();
            $this->setView();
            $this->renderView(
                'templates/tools/backup.php', 
                'UIBackup', 
                '?' . (is_null($this->params->coreModule) ? '' : $this->config->paramsNames['coreModule'] .  '=' . $this->params->coreModule . '&' . $this->config->paramsNames['subModule'] . '=backup')
            );
        } else if ('update' == $this->params->subModule) {
            $this->setModel('ModelUpdate');
            $this->modelAction();
            $this->setView();
            $this->renderView(
                'templates/tools/update.php', 
                'UIUpdate', 
                '?' . (is_null($this->params->coreModule) ? '' : $this->config->paramsNames['coreModule'] .  '=' . $this->params->coreModule . '&' . $this->config->paramsNames['subModule'] . '=update')
            );
        } else {
            $this->setView();
            $this->renderView('templates/tools/index.php');
        }
    }
}
