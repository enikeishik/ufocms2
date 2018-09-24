<?php
trait AdminModulesModelTrait
{
    public function setFields(array $fields = null)
    {
        parent::setFields();
        if (null !== $fields) {
            $this->fields = array_merge($this->fields, $fields);
        }
    }
    public function getTestModel($var = null)
    {
        if (null === $var) {
            return ['Result' => 'getTestModel'];
        }
        return ['var' => $var];
    }
    public function getTestSchema()
    {
        return ['Result' => 'getTestSchema'];
    }
    public function getTestItems()
    {
        return ['Result' => 'getTestItems'];
    }
    public function getTestMethod($var = null)
    {
        if (null === $var) {
            return ['Result' => 'getTestMethod'];
        }
        return ['var' => $var];
    }
    public function getEmptyItem()
    {
        return parent::getEmptyItem();
    }
}
