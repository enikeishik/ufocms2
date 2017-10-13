<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\Votings;

/**
 * Module level controller
 */
class Controller extends \Ufocms\AdminModules\Controller //implements IController
{
    public function dispatch()
    {
        if (!is_null($this->params->subModule)) {
            
            if ('answers' == $this->params->subModule) {
                
                $votingId = isset($_GET['votingid']) ? (int) $_GET['votingid'] : 0;
                
                if (0 != $votingId) {
                    $this->setModel('ModelAnswers');
                    $this->modelAction();
                    $this->setView();
                    $this->renderView('', 'UIAnswers', '&' . $this->config->paramsNames['subModule'] . '=answers&votingid=' . $votingId, true);
                } else {
                    $this->setModel();
                    $this->modelAction();
                    $this->setView();
                    $this->renderView();
                }
                
            } else if ('votes' == $this->params->subModule) {
                
                $votingId = isset($_GET['votingid']) ? (int) $_GET['votingid'] : 0;
                $this->setModel('ModelVotes');
                $this->modelAction();
                $this->setView();
                $this->renderView('', 'UIVotes', '&' . $this->config->paramsNames['subModule'] . '=votes&votingid=' . $votingId, true);
                
            } else if ('settings' == $this->params->subModule) {
                
                $this->setModel('ModelSettings');
                $this->modelAction();
                $this->setView();
                $this->renderView(
                    'form', 
                    '', 
                    '?' . $this->config->paramsNames['sectionId'] . '=' . $this->params->sectionId . '&' . $this->config->paramsNames['subModule'] . '=settings'
                );
                
            }
            
        } else {
            
            $this->setModel();
            $this->modelAction();
            $this->setView();
            $this->renderView();
            
        }
    }
}
