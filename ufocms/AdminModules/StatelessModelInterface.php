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
interface StatelessModelInterface extends DIObjectInterface
{
    /**
     * Get model of master (when this model is slave).
     * @return Model|null
     */
    public function getMaster();
    
    /**
     * @return mixed
     */
    public function getResult();
}
