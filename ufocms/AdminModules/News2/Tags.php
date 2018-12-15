<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\News2;

class Tags
{
    /**
     * @var \Ufocms\Backend\Db
     */
    protected $db = null;
    
    /**
     * Constructor
     * @param \Ufocms\Backend\Db &$db
     */
    public function __construct(&$db)
    {
        $this->db =& $db;
    }
    
    /**
     * @return array
     */
    public function get()
    {
        $sql =  'SELECT Id,Tag' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'news2_tags' . 
                ' ORDER BY Tag';
        return $this->db->getItems($sql);
    }
    
    /**
     * @return array
     */
    public function getItemTags($itemId)
    {
        $sql =  'SELECT t.Id,t.Tag' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'news2_tags AS t' . 
                ' INNER JOIN ' . C_DB_TABLE_PREFIX . 'news2_nt AS n' . 
                ' ON t.Id=n.TagId' . 
                ' WHERE n.ItemId=' . $itemId . 
                ' ORDER BY Tag';
        return $this->db->getItems($sql, 'Id');
    }
    
    /**
     * @param string $tag
     * @return int|null
     */
    function getId($tag)
    {
        $sql = 'SELECT Id' . 
               ' FROM ' . C_DB_TABLE_PREFIX . 'news2_tags' . 
               " WHERE LOWER(Tag)='" . $this->db->addEscape(mb_strtolower($tag)) . "'";
        return $this->db->getValue($sql, 'Id');
    }
    
    /**
     * @param int $itemId
     * @param array $tagsIds
     */
    function unbind($itemId, array $tagsIds)
    {
        $sql = 'DELETE FROM ' . C_DB_TABLE_PREFIX . 'news2_nt' . 
               ' WHERE ItemId=' . $itemId . ' AND TagId IN (' . implode(',', $tagsIds) . ')';
        $this->db->query($sql);
    }
    
    /**
     * @param int $itemId
     * @param array $tagsIds
     */
    function bind($itemId, array $tagsIds)
    {
        $sql = 'INSERT INTO ' . C_DB_TABLE_PREFIX . 'news2_nt' . 
               ' (ItemId, TagId) VALUES';
        $val = '';
        foreach ($tagsIds as $tagId) {
            $val .= ',(' . $itemId . ', ' . $tagId . ')';
        }
        $sql = $sql . substr($val, 1);
        $this->db->query($sql);
    }
    
    /**
     * @param string $tag
     * @return int|string
     */
    function add($tag)
    {
        $sql = 'INSERT INTO ' . C_DB_TABLE_PREFIX . 'news2_tags' . 
               ' (Tag)' . 
               " VALUES('" . $this->db->addEscape($tag) . "')";
        $this->db->query($sql);
        return $this->db->getLastInsertedId();
    }
}
