<?php
/**
 * UFOCMS v2 Content Management System
 * 
 * @copyright   Copyright (C) 2005 - 2017 Enikeishik <enikeishik@gmail.com>. All rights reserved.
 * @author      Enikeishik <enikeishik@gmail.com>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Ufocms\AdminModules;

use Ufocms\Frontend\DIObject;

/**
 * Base stateless model class
 */
abstract class StatelessModel extends DIObject
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
     * @var \Ufocms\Backend\Params
     */
    protected $params = null;
    
    /**
     * @var \Ufocms\Backend\Db
     */
    protected $db = null;
    
    /**
     * @var \Ufocms\Backend\Core
     */
    protected $core = null;
    
    /**
     * @var array
     */
    protected $module = null;
    
    /**
     * Availabe module level parameters
     * @var array
     */
    protected $moduleParams = null;
    
    /**
     * @var \Ufocms\Frontend\Tools
     */
    protected $tools = null;
    
    /**
     * @var Model
     */
    protected $master = null;
    
    /**
     * @var mixed
     */
    protected $result = null;
    
    /**
     * Распаковка контейнера.
     */
    protected function unpackContainer()
    {
        $this->debug =& $this->container->getRef('debug');
        $this->config =& $this->container->getRef('config');
        $this->params =& $this->container->getRef('params');
        $this->db =& $this->container->getRef('db');
        $this->core =& $this->container->getRef('core');
        $this->tools =& $this->container->getRef('tools');
        $this->module =& $this->container->getRef('module');
        $this->moduleParams =& $this->container->getRef('moduleParams');
    }
    
    /**
     * Get model of master (when this model is slave).
     * @return Model
     */
    public function getMaster()
    {
        return $this->master;
    }
    
    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }
}
