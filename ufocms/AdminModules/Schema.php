<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules;

use Ufocms\Frontend\DIObject;

/**
 * Base class for structured data, have property $fields - array of some configurable fields and methods for work with it.
 */
abstract class Schema extends DIObject
{
    /**
     * @var array
     */
    protected $fields = null;
    
    /**
     * @var null
     */
    protected $nullField = null;
    
    /**
     * Инициализация объекта. Переобпределяется в потомках по необходимости.
     */
    protected function init()
    {
        $this->setFields();
    }
    
    /**
     * Определение списка полей.
     */
    protected function setFields()
    {
        $this->fields = array(
            
        );
    }
    
    /**
     * Получение списка полей.
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }
    
    /**
     * Получение ссылки на поле по полю или его имени.
     * @param string|array $field
     * @return &array|null
     */
    public function &getFieldRef($field)
    {
        if (is_string($field)) {
            $fieldName = $field;
        } else if (is_array($field) && array_key_exists('Name', $field)) {
            $fieldName = $field['Name'];
        } else {
            return $this->nullField;
        }
        //make link on field, it is necessary to change Items value 
        //from method name to array of field items
        $field = null;
        $finded = false;
        foreach ($this->fields as &$field) {
            if ($field['Name'] == $fieldName) {
                $finded = true;
                break;
            }
        }
        if (!$finded || !is_array($field)) {
            return $this->nullField;
        }
        return $field;
    }
    
    /**
     * Получение данных поля по полю или его имени.
     * @param string|array $field
     * @return array|null
     */
    public function getField($field)
    {
        if (is_array($field)) {
            return $field;
        } else if (is_string($field)) {
            $fieldName = $field;
            foreach ($this->fields as $field) {
                if ($field['Name'] == $fieldName) {
                    return $field;
                }
            }
        }
        return null;
    }
    
    /**
     * Выполнение метода, указанного в атрибуте поля и возвращение его результата.
     * @param string|array $field
     * @param string $attribute
     * @param mixed $argument = null
     * @return mixed
     */
    public function getFieldMethodResult($field, $attribute, $argument = null)
    {
        $field = $this->getField($field);
        if (!array_key_exists($attribute, $field)) {
            return null;
        }
        $method = $field[$attribute];
        if (method_exists($this, $method)) {
            if (null === $argument) {
                return $this->$method();
            } else {
                return $this->$method($argument);
            }
        } else {
            return null;
        }
    }
    
    /**
     * Выполнение метода, указанного в атрибуте поля и возвращение его результата, с сохранением результата в атрибуте поля.
     * @param string|array $field
     * @param string $attribute
     * @param mixed $argument = null
     * @return mixed
     */
    public function getFieldMethodStoredResult($field, $attribute, $argument = null)
    {
        $field =& $this->getFieldRef($field);
        if (is_null($field) || !array_key_exists($attribute, $field)) {
            return null;
        }
        if (!is_string($field[$attribute])) {
            return $field[$attribute];
        }
        $method = $field[$attribute];
        if (method_exists($this, $method)) {
            if (null === $argument) {
                $result = $this->$method();
            } else {
                $result = $this->$method($argument);
            }
            $field[$attribute] = $result;
            return $result;
        } else {
            return null;
        }
    }
}
