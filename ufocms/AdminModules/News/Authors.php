<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\News;

/**
 * Module helpful functionality.
 */
trait Authors
{
    /**
     * Get (distinct) authors.
     * @param bool $withEmpty = false
     * @return array
     */
    protected function getAuthors($withEmpty = false)
    {
        static $authors = null;
        if (null === $authors) {
            $sql =  'SELECT DISTINCT Author' . 
                    ' FROM ' . C_DB_TABLE_PREFIX . 'news' . 
                    ' ORDER BY Author';
            $authors = $this->db->getItems($sql);
            if (null === $authors) {
                return [];
            }
            foreach ($authors as &$item) {
                $item = [
                    'Value' => $item['Author'], 
                    'Title' => $item['Author']
                ];
            }
            unset($item);
        }
        return $withEmpty ? array_merge(
            [['Value' => '', 'Title' => '']], $authors
        ): $authors;
    }
}
