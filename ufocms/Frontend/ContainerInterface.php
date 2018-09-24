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
 * Интерфейс контейнера, хранит значения и ссылки на объекты.
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
interface ContainerInterface //extends \Psr\Container\ContainerInterface
{
    /**
     * Конструктор.
     * @param array $vars = null    массив ссылок на объекты
     */
    public function __construct(array $vars = null);
    
    /**
     * Link property with reference.
     * @param string $property
     * @param object $reference
     */
    public function setByRef($property, &$reference);
    
    /**
     * Gets reference to property.
     * @param string $property
     * @return mixed
     */
    public function &getRef($property);
    
    /**
     * Sets property with value.
     * @param string $property
     * @param mixed $value
     */
    public function set($property, $value);
    
    /**
     * Finds an entry of the container by its identifier and returns it.
     * @param string $property
     * @return mixed
     */
    public function get($property);
    
    /**
     * Returns true if the container can return an entry for the given identifier.
     * Returns false otherwise.
     * @param string $property
     * @return bool
     */
    public function has($property);
}
