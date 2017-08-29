<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\SysSitemap;

/**
 * Main module model
 */
class Model extends \Ufocms\Modules\Model //implements IModel
{
    public function getItems()
    {
        if (null !== $this->items) {
            return $this->items;
        }
        $sql =  'SELECT * FROM ' . C_DB_TABLE_PREFIX . 'sections' . 
                ' WHERE isenabled<>0 AND inmap<>0' . 
                ' ORDER BY mask';
        $this->items = $this->db->getItems($sql);
        return $this->items;
    }
}
