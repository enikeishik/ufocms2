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
 * Module level view base class interface
 */
interface WidgetInterface extends DIObjectInterface
{
    /**
     * Генерация вывода.
     */
    public function render();
}
