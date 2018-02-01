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
 * Db wrap
 */
class Db extends \Ufocms\Frontend\Db
{
    /**
     * @var Audit
     */
    protected $audit = null;
    
    /**
     * @param Audit &$audit
     * @param \Ufocms\Frontend\Debug &$debug = null
     * @throws \Exception
     */
    public function __construct(Audit &$audit, \Ufocms\Frontend\Debug &$debug = null)
    {
        $this->audit =& $audit;
        $this->debug =& $debug;
        parent::__construct($this->debug);
    }
    
    /**
     * @see parent
     */
    public function query($query, $resultmode = null)
    {
        if (C_DB_READONLY 
        && 0 !== stripos($query, 'SELECT ') 
        && 0 !== stripos($query, 'SET NAMES ') 
        && 0 !== stripos($query, 'SHOW TABLES')) {
            $this->audit->record($query);
            $this->audit->record($this->getError());
            if (null === $this->debug) {
                $this->generatedError = 'Readonly mode for database is on';
                return false;
            }
            $this->debug->trace($query);
            $this->debug->trace();
            return true; //for not break execution
        }
        
        if (null === $this->debug) {
            $this->audit->record($query);
            $result = parent::query($query);
            if (0 != $this->errno) {
                $this->audit->record($this->error);
            }
            return $result;
        }
        
        $this->audit->record($query);
        $this->debug->trace($query);
        $reflection = new \ReflectionClass($this);
        $grandparent = $reflection->getParentClass()->getParentClass()->getName();
        unset($reflection);
        $result = $grandparent::query($query);
        if (0 == $this->errno) {
            $this->debug->trace();
        } else {
            $this->audit->record($this->error);
            $this->debug->trace(null, $this->errno, $this->error);
        }
        return $result;
    }
}
