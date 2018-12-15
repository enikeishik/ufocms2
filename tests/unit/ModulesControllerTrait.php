<?php
trait ModulesControllerTrait
{
    public function getGetModuleContext()
    {
        $model = $this->getModel();
        return $this->getModuleContext($model);
    }
    public function getModuleParamsStruct()
    {
        return $this->moduleParamsStruct;
    }
    protected function getModel()
    {
        if (empty($this->module) || empty($this->module['Model'])) {
            return null;
        }
        if (0 == strcasecmp('stdClass', $this->module['Model'])) {
            $container = new \Ufocms\Frontend\Container([]);
            return new class($container) extends \Ufocms\Modules\Model {
                public function getSettings()
                {
                    return [];
                }
                public function getActionResult()
                {
                    return null;
                }
                public function getItems()
                {
                    return [];
                }
                public function getItemsCount()
                {
                    return 0;
                }
                public function getItem()
                {
                    return null;
                }
            };
        } else {
            return parent::getModel();
        }
    }
    protected function modelAction(&$model)
    {
        if (null !== $this->params->action) {
            throw new \Exception('modelAction: ' . $this->params->action);
        }
    }
    protected function getView(\Ufocms\Modules\ModelInterface &$model, array $context, string $layout)
    {
        return new class() {
            public function render()
            {
                throw new \Exception('render');
            }
        };
    }
    public function getLayout()
    {
        return parent::getLayout();
    }
}
