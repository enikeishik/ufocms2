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
 * Module level view base class interface
 */
interface ViewInterface extends DIObjectInterface
{
    /**
     * Отрисовка представления.
     * @param string $layout = null
     * @param string $ui = null
     * @param string $uiParams = null
     * @param bool $uiParamsAppend = false
     */
    public function render($layout = null, $ui = null, $uiParams = null, $uiParamsAppend = false);
    
    /**
     * Получение объекта виджета.
     * @param string $module
     * @return AdminWidget
     */
    public function adminWidget($module);
}