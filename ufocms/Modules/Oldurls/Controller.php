<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\Oldurls;

/**
 * Module level controller
 */
class Controller extends \Ufocms\Modules\Controller //implements IController
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
}
