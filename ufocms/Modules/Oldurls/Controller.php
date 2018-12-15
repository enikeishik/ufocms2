<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\Oldurls;

use \Ufocms\Modules\ModelInterface;

/**
 * Module level controller
 */
class Controller extends \Ufocms\Modules\Controller //implements ControllerInterface
{
    protected function setModuleParamsStruct()
    {
        parent::setModuleParamsStruct();
        //set alias value from raw REQUEST_URI, 
        //because it may by part of path
        //or part of GET
        $this->moduleParamsStruct = array_merge($this->moduleParamsStruct, 
            array(
                'alias' => [
                    'type'          => 'string', 
                    'from'          => 'none', 
                    'prefix'        => '', 
                    'additional'    => false, 
                    'value'         => ltrim(substr($_SERVER['REQUEST_URI'], strlen(rtrim($this->params->sectionPath, '/'))), '/'), 
                    'default'       => '', 
                ], 
            )
        );
    }
    
    protected function setPathParams()
    {
        //disable parent method
    }
    
    protected function setGetParams()
    {
        //disable parent method
    }
    
    /**
     * @see parent
     */
    protected function getModuleContext(ModelInterface &$model)
    {
        if (0 != $this->params->itemId) {
            $item = $model->getItem();
            if (null === $item) {
                $this->core->riseError(404, 'Item not exists');
            } else if (isset($item['Target']) && '' != $item['Target']) {
                $this->core->riseError(301, 'Move to current URL', $item['Target']);
            }
            return array(
                'settings'      => $model->getSettings(), 
                'item'          => $item, 
                'items'         => null, 
                'itemsCount'    => $model->getItemsCount(), 
            );
        } else {
            return array(
                'settings'      => $model->getSettings(), 
                'item'          => null, 
                'items'         => $model->getItems(), 
                'itemsCount'    => $model->getItemsCount(), 
            );
        }
    }
}
