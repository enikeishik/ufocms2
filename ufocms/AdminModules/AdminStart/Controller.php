<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\AdminStart;

/**
 * Module level controller
 */
class Controller extends \Ufocms\AdminModules\Controller //implements IController
{
    public function dispatch()
    {
        if (null === $this->db->getItem('SHOW TABLES')) {
            $this->core->riseError(301, 'Installation required', '/install');
        }
        $this->setModel();
        $this->setView();
        $this->renderView('startpage');
    }
}
