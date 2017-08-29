<?php
/**
 * @copyright
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
