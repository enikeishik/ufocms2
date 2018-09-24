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
 * Module level controller base class interface
 */
interface ControllerInterface extends DIObjectInterface
{
    /**
     * Диспетчер контроллера, создает объекты модели и представления, выполняет действие модели и генерацию вывода представления.
     */
    public function dispatch();
}
