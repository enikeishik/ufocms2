<?php
/**
 * @copyright
 */

namespace Ufocms\Frontend;

/**
 * Abstract implementation of structure
 */
abstract class Struct
{
    /**
     * ����������� ������-��������� ��������� ��������� ���� �������, 
     * ������������� ����������� �������������� �������, 
     * � ������� ����� ������������� ������ ����� ������.
     * ����� ������ ����� �������� �� ������������� �������, 
     * �������-��������� � ������ JSON.
     * ��� ������������ ����� �������� �������������� ���������� 
     * � ���� ����, ������� ������������ ��������� ���� ��-���������.
     *
     * @param mixed $vars = null    ������������� ������ ��� ������-��������� � �������
     * @param bool $cast = true     ��������� ��� ���������� � ������������ � ����� �����
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
     * ��������� �����, ��� ������������ ������������� ���������� ������ (�������) � ���� ������.
     * @return string
     */
    public function __toString()
    {
        return json_encode($this);
    }
    
    /**
     * ������������ ����� ��������� ������ �� ������������� �������-���������.
     * @param Struct $struct        ������-���������, ������ �������� ����� �������������
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
     * ������������ ����� ��������� ������ �� ������������� �������������� ������� (����� ������������� ������ �����).
     * @param array $vars           ������������� ������ � �������
     * @param bool $cast = true     ��������� ��� ���������� � ������������ � ����� �����
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
     * ���������� ������������� ������ �����.
     * @return array($key => $value)
     */
    public function getValues()
    {
        return get_object_vars($this);
    }
    
    /**
     * ���������� ������ ���� �����.
     * @return array
     */
    public function getFields()
    {
        return array_keys($this->getValues());
    }
}
