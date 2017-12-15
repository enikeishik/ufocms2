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
 * Класс-контейнер, хранит ссылки на объекты.
 * 
 * Используется для передачи подчиненным объектам ссылок 
 * на инициализированные объекты с данными, объекты-структуры, 
 * управляющие и вспомогательные объекты.
 * 
 * Например, объект ядра (Core) создавая объект раздела (Section) 
 * передает ему ссылки на инициализированные в ядре объекты 
 * работы с базой данных, объект конфигурации и др. Объект раздела, 
 * в свою очередь, передает создаваемому им объекту модуля раздела
 * ссылки на эти объекты и ссылку на самого себя, чтобы модуль мог
 * использовать методы и данные раздела в своих целях, либо передать
 * ссылку на объект раздела дальше - объекту шаблона раздела.
 */
class Container //implements ContainerInterface - container-interop
{
    /**
     * Конструктор.
     * @param array $vars = null    массив ссылок на объекты
     */
    public function __construct(array $vars = null)
    {
        if (is_null($vars)) {
            return;
        }
        foreach ($vars as $key => $val) {
            if (is_object($val) || is_array($val)) {
                /*
                 NOT $this->$key =& $val;
                 потому что $val - ссылка и при следующей итерации 
                 будет указывать на другое значение, а в месте с этим 
                 и все предыдущие $this->$key (которые будут 
                 при таком присваивании `=&` синонимами $val) 
                 также будут указывать на новое значение $val;
                 */
                $this->$key =& $vars[$key];
            } else {
                $this->$key = $val;
            }
        }
    }
    
    /**
     * 
     * @param string $property
     * @param object $reference
     */
    public function setByRef($property, &$reference) { $this->$property =& $reference; }
    
    /**
     * 
     * @param string $property
     * @return mixed
     */
    public function &getRef($property) { return $this->$property; }
    
    /**
     * @see ContainerInterface
     */
    public function get($property) { return $this->$property; }
    
    /**
     * @see ContainerInterface
     */
    public function has($property) { return property_exists($this, $property); }
}
