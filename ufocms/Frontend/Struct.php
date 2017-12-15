<?php
/**
 * UFOCMS v2 Content Management System
 * 
 * @copyright   Copyright (C) 2005 - 2017 Enikeishik <enikeishik@gmail.com>. All rights reserved.
 * @author      Enikeishik <enikeishik@gmail.com>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Ufocms\Frontend;

/**
 * Abstract implementation of structure
 */
abstract class Struct
{
    /**
     * Конструктор класса-структуры позволяет загрузить поля данными, 
     * передаваемыми посредством ассоциативного массива, 
     * в котором ключи соответствуют именам полей класса.
     * Также данные можно получить из произвольного объекта, 
     * объекта-структуры и строки JSON.
     * При присваивании полям значения предварительно приводятся 
     * к типу поля, которое определяется значением поля по-умолчанию.
     *
     * @param mixed $vars = null    ассоциативный массив или объект-структура с данными
     * @param bool $cast = true     приводить тип переменных в соответствие с типом полей
     */
    public function __construct($vars = null, $cast = true)
    {
        if (is_array($vars)) {
            $this->setValues($vars, $cast);
        } else if (is_object($vars)) {
            if (is_a($vars, __CLASS__)) {
                $this->setFields($vars);
            } else {
                $this->setValues(get_object_vars($vars), $cast);
            }
        } else if (is_string($vars)) {
            $this->setValues(json_decode($vars, true), $cast);
        }
    }
    
    /**
     * Волшебный метод, для формирования представления экземпляра класса (объекта) в виде строки.
     * @return string
     */
    public function __toString()
    {
        return json_encode($this);
    }
    
    /**
     * Присваивание полям структуры данных из передаваемого объекта-структуры.
     * @param Struct $struct        объект-структура, данные которого нужно импортировать
     */
    public function setFields(Struct $struct)
    {
        $vars = get_object_vars($struct);
        foreach ($vars as $key => $val) {
            if (property_exists($this, $key)) {
                $this->$key = $val;
            }
        }
    }
    
    /**
     * Присваивание полям структуры данных из передаваемого ассоциативного массива (ключи соответствуют именам полей).
     * @param array $vars           ассоциативный массив с данными
     * @param bool $cast = true     приводить тип переменных в соответствие с типом полей
     */
    public function setValues(array $vars, $cast = true)
    {
        if ($cast) {
            foreach ($vars as $key => $val) {
                if (property_exists($this, $key)) {
                    if (is_int($this->$key)) {
                        $this->$key = (int) $val;
                    } else if (is_string($this->$key)) {
                        $this->$key = (string) $val;
                    } else if (is_bool($this->$key)) {
                        $this->$key = (bool) $val;
                    } else if (is_float($this->$key)) {
                        $this->$key = (float) $val;
                    } else {
                        $this->$key = $val;
                    }
                }
            }
        } else {
            foreach ($vars as $key => $val) {
                if (property_exists($this, $key)) {
                    $this->$key = $val;
                }
            }
        }
    }
    
    /**
     * Возвращает ассоциативный массив полей.
     * @return array($key => $value)
     */
    public function getValues()
    {
        return get_object_vars($this);
    }
    
    /**
     * Возвращает массив имен полей.
     * @return array
     */
    public function getFields()
    {
        return array_keys($this->getValues());
    }
}
