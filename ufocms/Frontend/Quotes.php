<?php
/**
 * @copyright
 */

namespace Ufocms\Frontend;

/**
 * Класс цитат.
 */
class Quotes
{
    /**
     * @var Debug
     */
    protected $debug = null;
    
    /**
     * @var Db
     */
    protected $db = null;
    
    /**
     * @param Db &$db
     * @param Debug &$debug = null
     */
    public function __construct(&$db, &$debug = null)
    {
        $this->debug    =& $debug;
        $this->db       =& $db;
    }
    
    /**
     * @param int $groupId
     * @return int|null
     */
    protected function getRandId($groupId)
    {
        $sql = 'SELECT id FROM ' . C_DB_TABLE_PREFIX . 'quotes' . 
               ' WHERE disabled=0 AND groupid=' . $groupId;
        $arr = $this->db->getValues($sql, 'id');
        if (null === $arr || 0 == count($arr)) {
            return null;
        }
        return $arr[array_rand($arr)];
    }
    
    /**
     * @param int $groupId
     * @return string
     */
    public function get($groupId)
    {
        if (null === $id = $this->getRandId($groupId)) {
            return '';
        }
        
        $sql = 'SELECT quote FROM ' . C_DB_TABLE_PREFIX . 'quotes WHERE id=' . $id;
        if (null !== $quote = $this->db->getValue($sql, 'quote')) {
            return $quote;
        } else {
            return '';
        }
    }
}
