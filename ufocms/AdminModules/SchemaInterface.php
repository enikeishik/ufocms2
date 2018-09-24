<?php
/**
 * UFOCMS v2 Content Management System
 * 
 * @copyright   Copyright (C) 2005 - 2017 Enikeishik <enikeishik@gmail.com>. All rights reserved.
 * @author      Enikeishik <enikeishik@gmail.com>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Ufocms\AdminModules;

use Ufocms\Frontend\DIObjectInterface;

/**
 * Module level model base class interface
 */
interface SchemaInterface extends DIObjectInterface
{
    /**
     * Получение списка полей.
     * @return array
     */
    public function getFields();
    
    /**
     * Получение данных поля по полю или его имени.
     * @param string|array $field
     * @return array|null
     */
    public function getField($field);
    
    /**
     * Получение ссылки на поле по полю или его имени.
     * @param string|array $field
     * @return &array|&null
     */
    public function &getFieldRef($field);
}
