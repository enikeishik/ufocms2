<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\CoreWidgets;

/**
 * Module level controller
 */
class Controller extends \Ufocms\AdminModules\Controller //implements IController
{
    protected function setModuleParamsStruct()
    {
        $this->moduleParamsStruct = array(
            ['Type' => 'int',       'Name' => 'step',           'Value' => 0,       'Default' => 0], 
            ['Type' => 'int',       'Name' => 'TypeId',         'Value' => 0,       'Default' => 0], 
            ['Type' => 'arrint',    'Name' => 'SrcSections',    'Value' => null,    'Default' => null], 
        );
    }
    
    public function dispatch()
    {
        if ('add' == $this->params->action) {
            
            if (0 == $this->moduleParams['step']) {
                $this->setModel();
                $this->setView();
                $this->renderView(null, 'UIAdd1');
            } else if (2 == $this->moduleParams['step']) { //step 2
                $this->setModel();
                $this->setView('ViewAdd2');
                $this->renderView();
            } else { //step 3
                parent::dispatch();
            }
            
        } else if ('edit' == $this->params->action) {
            
            $this->setModel();
            $this->setView();
            $item = $this->model->getItem();
            $moduleId = $this->model->getTypeModuleId($item['TypeId']);
            if (is_array($this->moduleParams['SrcSections'])) {
                $srcSections = '';
                foreach ($this->moduleParams['SrcSections'] as $srcSection) {
                    $srcSections .= '&SrcSections[]=' . $srcSection;
                }
            } else {
                $srcSections = '&SrcSections=' . $this->moduleParams['SrcSections'];
            }
            $this->renderView(
                null, 
                'UIEdit', 
                '&' . $this->config->paramsNames['action'] . '=edit' . 
                    '&' . $this->config->paramsNames['itemId'] . '=' . $this->params->itemId . 
                    '&useSources=' . (0 != $moduleId ? 1 : 0) . 
                    '&useContent=' . $this->model->getWidgetUseContent() . 
                    $srcSections, 
                true
            );
            
        } else {
            
            parent::dispatch();
            
        }
    }
}
