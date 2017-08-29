<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\Mainpage;

/**
 * Main module model
 */
class Model extends \Ufocms\Modules\Model //implements IModel
{
    public function getItem()
    {
        if (null !== $this->item) {
            return $this->item;
        }
        $sql = 'SELECT * FROM ' . C_DB_TABLE_PREFIX . 'mainpage WHERE id=1';
        $this->item = $this->db->getItem($sql);
        return $this->item;
    }
}
