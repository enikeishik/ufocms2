<?php
/**
 * UFOCMS v2 Content Management System
 * 
 * @copyright   Copyright (C) 2005 - 2017 Enikeishik <enikeishik@gmail.com>. All rights reserved.
 * @author      Enikeishik <enikeishik@gmail.com>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Ufocms\AdminModules;

/**
 * Base AdminWidget class
 */
class AdminWidget
{
    /**
     * @var \Ufocms\Frontend\Debug
     */
    protected $debug = null;
    
    /**
     * Ссылка на объект конфигурации.
     * @var \Ufocms\Backend\Config
     */
    protected $config = null;
    
    /**
     * @var \Ufocms\Backend\Db
     */
    protected $db = null;
    
    /**
     * @var \Ufocms\Backend\Core
     */
    protected $core = null;
    
    /**
     * Constructor.
     * @param \Ufocms\Backend\Config &$config
     * @param \Ufocms\Backend\Db &$db
     * @param \Ufocms\Backend\Core &$core
     * @param \Ufocms\Frontend\Debug &$debug = null
     */
    public function __construct(&$config, &$db, &$core, &$debug = null)
    {
        $this->config =& $config;
        $this->db     =& $db;
        $this->core   =& $core;
        $this->debug  =& $debug;
    }
    
    /**
     * Render AdminWidget.
     */
    public function render()
    {
        
    }
}
