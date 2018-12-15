<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\Auctions;

/**
 * Main module view
 */
class View extends \Ufocms\Modules\View //implements ViewInterface
{
    protected function getModuleTemplateEntry()
    {
        if ('login' == $this->moduleParams['requestType']) {
            return '/login.php';
        }
        if ((null !== $this->params->actionId || null !== $this->params->action) 
            && 'xhr' == $this->moduleParams['requestType']
        ) {
            return '/resultjson.php';
        }
        return parent::getModuleTemplateEntry();
    }
}
