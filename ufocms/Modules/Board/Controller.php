<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\Board;

/**
 * Module level controller
 */
class Controller extends \Ufocms\Modules\Controller //implements IController
{
    protected function modelAction(&$model)
    {
        parent::modelAction($model);
        if (2 == $this->params->actionId) {
            $model->add();
        }
    }
}
