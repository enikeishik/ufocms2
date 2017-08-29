<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\Mainpage;

/**
 * Module level controller
 */
class Controller extends \Ufocms\AdminModules\Controller //implements IController
{
    public function dispatch()
    {
        $this->setModel();
        $this->modelAction();
        $this->setView();
        $this->renderView('form');
    }
}
