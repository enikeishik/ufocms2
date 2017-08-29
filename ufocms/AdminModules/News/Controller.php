<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\News;

/**
 * Module level controller
 */
class Controller extends \Ufocms\AdminModules\Controller //implements IController
{
    public function dispatch()
    {
        if (!is_null($this->params->subModule)) {
            
            if ('import' == $this->params->subModule) {
                
                $this->setModel('ModelImportSettings');
                $this->modelAction();
                $this->setView();
                $this->renderView(
                    '', 
                    'UIImportSettings', 
                    '?' . $this->config->paramsNames['sectionId'] . '=' . $this->params->sectionId . '&' . $this->config->paramsNames['subModule'] . '=import'
                );
                
            } else if ('settings' == $this->params->subModule) {
                
                $this->setModel('ModelSettings');
                $this->modelAction();
                $this->setView();
                //use module UI to display main frame tabs
                $this->renderView(
                    'form', 
                    '', 
                    '?' . $this->config->paramsNames['sectionId'] . '=' . $this->params->sectionId . '&' . $this->config->paramsNames['subModule'] . '=settings'
                );
                
            }
            
        } else {
            
            if ('import' == $this->params->action) {
                
                $this->setModel('ModelImport');
                $this->setView();
                $this->renderView('single', 'UIImport');
                
            } else if ('importitems' == $this->params->action) {
                
                $this->setModel('ModelImportItems');
                $this->setView();
                $this->renderView(
                    'templates/news/single.php', 
                    'UIImportItems', 
                    '?' . $this->config->paramsNames['sectionId'] . '=' . $this->params->sectionId . '&action=importdata'
                );
                
            } else if ('importdata' == $this->params->action) {
                
                $this->setModel('ModelImportItems');
                $this->model->update();
                $this->setView();
                $this->renderView('result');
                
            } else {
                
                $this->setModel();
                $this->modelAction();
                $this->setView();
                $this->renderView();
                
            }
        }
    }
}
