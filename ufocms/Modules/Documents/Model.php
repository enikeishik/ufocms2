<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\Documents;

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
        $sql = 'SELECT * FROM ' . C_DB_TABLE_PREFIX . 'documents WHERE SectionId=' . $this->params->sectionId;
        $this->item = $this->db->getItem($sql);
        return $this->item;
    }
}
