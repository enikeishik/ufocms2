<?php
/**
 * @copyright
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
