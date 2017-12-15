<?php
/**
 * UFOCMS v2 Content Management System
 * 
 * @copyright   Copyright (C) 2005 - 2017 Enikeishik <enikeishik@gmail.com>. All rights reserved.
 * @author      Enikeishik <enikeishik@gmail.com>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Ufocms\Backend;

/**
 * Structure containing application parameters defined by user
 */
class Params extends \Ufocms\Frontend\Params
{
    /**
     * @var string
     */
    public $coreModule = null;
    
    /**
     * @var string
     */
    public $subModule = null;
    
    /**
     * @var bool
     */
    public $actionUnsafe = null;
}
