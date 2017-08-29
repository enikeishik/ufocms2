<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\CoreInteraction;

/**
 * Module level controller
 */
class Controller extends \Ufocms\AdminModules\Controller //implements IController
{
    public function dispatch()
    {
        if ('blacklist' == $this->params->subModule) {
            $this->setModel('ModelBlacklist');
            $this->modelAction();
            $this->setView();
            $this->renderView(
                'list', 
                'UIBlacklist', 
                '?' . (is_null($this->params->coreModule) ? '' : $this->config->paramsNames['coreModule'] .  '=' . $this->params->coreModule . '&' . $this->config->paramsNames['subModule'] . '=blacklist')
            );
        } else if ('rates' == $this->params->subModule) {
            $this->setModel('ModelRates');
            $this->modelAction();
            $this->setView();
            $this->renderView(
                'list', 
                'UIRates', 
                '?' . (is_null($this->params->coreModule) ? '' : $this->config->paramsNames['coreModule'] .  '=' . $this->params->coreModule . '&' . $this->config->paramsNames['subModule'] . '=rates')
            );
        } else if ('commentrates' == $this->params->subModule) {
            $this->params->commentId = isset($_GET['commentid']) ? (int) $_GET['commentid'] : 0;
            $this->setModel('ModelCommentRates');
            $this->modelAction();
            $this->setView();
            $this->renderView(
                'list', 
                'UICommentRates', 
                '?' . (is_null($this->params->coreModule) ? '' : $this->config->paramsNames['coreModule'] .  '=' . $this->params->coreModule . '&' . $this->config->paramsNames['subModule'] . '=commentrates&commentid=' . $this->params->commentId)
            );
        } else {
            $this->setModel();
            $this->modelAction();
            $this->setView();
            $this->renderView();
        }
    }
}
