<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\Faq;

/**
 * Module level controller
 */
class Controller extends \Ufocms\Modules\Controller //implements ControllerInterface
{
    protected function modelAction(&$model)
    {
        parent::modelAction($model);
        if (2 == $this->params->actionId) {
            $model->add();
        }
    }
}
