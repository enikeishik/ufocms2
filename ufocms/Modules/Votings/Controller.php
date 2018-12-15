<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\Votings;

use \Ufocms\Modules\ModelInterface;

/**
 * Module level controller
 */
class Controller extends \Ufocms\Modules\Controller //implements ControllerInterface
{
    protected function setModuleParamsStruct()
    {
        parent::setModuleParamsStruct();
        $this->moduleParamsStruct = array_merge($this->moduleParamsStruct, 
            array(
                'results'  => ['type' => 'bool',   'from' => 'path',   'prefix' => 'results',   'additional' => true,  'value' => null, 'default' => false], 
            )
        );
    }
    
    protected function getModuleContext(ModelInterface &$model)
    {
        if (0 == $this->params->itemId) {
            return parent::getModuleContext($model);
        }
        
        $context = parent::getModuleContext($model);
        $item = $context['item'] ? : $model->getItem();
        
        return array_merge(
            $context, 
            array(
                'ticket'        => $model->getTicket(), 
                'showForm'      => $model->isShowForm($item, $this->moduleParams), 
                'showResults'   => $model->isShowResults($item, $this->moduleParams), 
            )
        );
    }
}
