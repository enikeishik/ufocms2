<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\Documents;

/**
 * Main module model
 */
class Insertion extends \Ufocms\Modules\Insertion //implements IInsertion
{
    protected function getItems()
    {
        //вывод вставок
        //получаем тело документа
        $sql =  'SELECT body FROM ' . C_DB_TABLE_PREFIX . 'documents' . 
                ' WHERE SectionId=' . $this->data['SourceId'];
        $item = $this->db->getItem($sql);
        if (null !== $item) {
            $item = array_merge(
                $item, 
                array(
                    'ItemsOutput'           => 1, 
                    'InsertionItemNumber'   => 1, 
                )
            );
            return array($item);
        } else {
            return null;
        }
    }
}
