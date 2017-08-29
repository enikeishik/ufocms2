<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\Shop;

/**
 * Module level controller
 */
class Controller extends \Ufocms\AdminModules\Controller //implements IController
{
    public function dispatch()
    {
        if (null !== $this->params->subModule) {
            
            switch ($this->params->subModule) {
                case 'categories':
                    $this->setModel('ModelCategories');
                    $this->modelAction();
                    $this->setView();
                    $this->renderView(
                        in_array($this->params->action, $this->config->actionsForm) ? 'form' : 'tree', 
                        'UICategories', 
                        '?' . $this->config->paramsNames['sectionId'] . '=' . $this->params->sectionId . '&' . $this->config->paramsNames['subModule'] . '=' . $this->params->subModule
                    );
                    break;
                
                case 'orders':
                    $this->setModel('ModelOrders');
                    $this->modelAction();
                    $this->setView();
                    $this->renderView(
                        in_array($this->params->action, $this->config->actionsForm) ? 'form' : 'single', 
                        'UIOrders', 
                        '?' . $this->config->paramsNames['sectionId'] . '=' . $this->params->sectionId . '&' . $this->config->paramsNames['subModule'] . '=' . $this->params->subModule
                    );
                    break;
                
                case 'settings':
                    $this->setModel('ModelSettings');
                    $this->modelAction();
                    $this->setView();
                    //use module UI to display main frame tabs
                    $this->renderView(
                        'form', 
                        '', 
                        '?' . $this->config->paramsNames['sectionId'] . '=' . $this->params->sectionId . '&' . $this->config->paramsNames['subModule'] . '=settings'
                    );
                    break;
            }
            
        } else if (null !== $this->params->action) {
            
            switch ($this->params->action) {
                case 'import':
                    $this->setModel('ModelImport');
                    if (!isset($_GET['step'])) {
                        $this->setView('ViewImport');
                        $this->renderView('form', 'UIImportForm');
                    } else {
                        $this->modelAction();
                        $this->setView('ViewImport');
                        $this->renderView();
                    }
                    break;
                    
                case 'export':
                    $this->setModel('ModelExport');
                    $this->setView('ViewExport');
                    $this->renderView();
                    break;
                    
                default:
                    $this->setModel();
                    $this->modelAction();
                    $this->setView();
                    $this->renderView();
            }
            
        } else {
            $this->setModel();
            $this->modelAction();
            $this->setView();
            $this->renderView();
            
        }
    }
}
