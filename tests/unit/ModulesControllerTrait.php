<?php
trait ModulesControllerTrait
{
    public function getModuleParamsStruct()
    {
        return $this->moduleParamsStruct;
    }
    protected function modelAction(&$model)
    {
        if (null !== $this->params->action) {
            throw new \Exception('modelAction: ' . $this->params->action);
        }
    }
    protected function getView(&$model)
    {
        return new class() {
            public function render()
            {
                throw new \Exception('render');
            }
        };
    }
}
