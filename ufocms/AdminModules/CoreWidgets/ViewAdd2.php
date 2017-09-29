<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\CoreWidgets;

/**
 * View class
 */
class ViewAdd2 extends \Ufocms\AdminModules\View
{
    /**
     * @see parent
     */
    public function render($layout = null, $ui = null, $uiParams = null, $append = false)
    {
        //Layout
        $this->layout = $this->getLayout($layout);
        
        //UI
        $typeId = $this->moduleParams['TypeId'];
        $moduleId = $this->model->getTypeModuleId($typeId);
        if (0 == $moduleId) {
            $this->ui = $this->getUI(
                'UIAdd21', 
                $uiParams . 
                    '&' . $this->config->paramsNames['action'] . '=add' . 
                    '&' . $this->config->paramsNames['itemId'] . '=0' . 
                    '&step=2' . 
                    '&TypeId=' . $typeId . 
                    '&useContent=' . $this->model->getWidgetUseContent(), 
                true
            );
        } else {
            if (is_array($this->moduleParams['SrcSections'])) {
                $srcSections = '';
                foreach ($this->moduleParams['SrcSections'] as $srcSection) {
                    $srcSections .= '&SrcSections[]=' . $srcSection;
                }
            } else {
                $srcSections = '&SrcSections=' . $this->moduleParams['SrcSections'];
            }
            $this->ui = $this->getUI(
                'UIAdd22', 
                $uiParams . 
                    '&' . $this->config->paramsNames['action'] . '=add' . 
                    '&' . $this->config->paramsNames['itemId'] . '=0' . 
                    '&step=2' . 
                    '&TypeId=' . $typeId . 
                    $srcSections, 
                true
            );
        }
        
        require_once $this->layout;
    }
}
