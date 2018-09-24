<?php
/**
 * UFOCMS v2 Content Management System
 * 
 * @copyright   Copyright (C) 2005 - 2017 Enikeishik <enikeishik@gmail.com>. All rights reserved.
 * @author      Enikeishik <enikeishik@gmail.com>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Ufocms\Modules;

use Ufocms\Frontend\DIObjectInterface;

/**
 * Module level model base class interface
 */
interface ModelInterface extends DIObjectInterface
{
    /**
     * Получение установок модуля.
     * @return array
     */
    public function getSettings();
    
    /**
     * Получение списка элементов.
     * @return array
     */
    public function getItems();
    
    /**
     * Получение общего количества элементов. Значение должно устанавливаться в getItems или аналогичных методах.
     * @return int
     */
    public function getItemsCount();
    
    /**
     * Получение данных текущего элемента.
     * @return array|null
     */
    public function getItem();
    
    /**
     * Получение списка разделов использующих данный модуль.
     * @param bool $nc = false  not using method-cached data
     * @return array
     */
    public function getSections($nc = false);
    
    /**
     * @return mixed
     */
    public function getActionResult();
}
