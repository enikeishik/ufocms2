<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\News;

/**
 * News module model class
 */
class ModelImportItems extends \Ufocms\AdminModules\News\Model
{
    /**
     * @see parent
     */
    protected function init()
    {
        parent::init();
        $this->itemsTable = '';
        $this->itemIdField = '';
        $this->itemDisabledField = '';
        $this->canUpdateItems = false;
        $this->canDeleteItems = false;
    }
    
    protected function setItems()
    {
        $sql =  'SELECT Url, ItemAuthor' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'news_import' . 
                ' WHERE Id=' . $this->params->itemId;
        $item = $this->db->getItem($sql);
        if (is_null($item)) {
            $this->items = array();
            $this->itemsCount = count($this->items);
            return;
        }
        $url = $item['Url'];
        $author = $item['ItemAuthor'];
        unset($item);
        
        $rssReader = new RssReader($url);
        $this->items = $rssReader->getItems();
        $this->itemsCount = count($this->items);
        unset($rssReader);
        $lastGuid = null;
        foreach ($this->items as &$item) {
            if (null === $lastGuid) {
                if (isset($item['guid'])) {
                    $lastGuid = trim($item['guid']);
                } else if (isset($item['link'])) {
                    $lastGuid = trim($item['link']);
                }
            }
            $item['Publicate'] = 0;
            $item['SectionId'] = $this->params->sectionId;
            $item['DateCreate'] = date('Y-m-d H:i:s', strtotime($item['pubDate']));
            unset($item['pubDate']);
            $item['Title'] = $item['title'];
            unset($item['title']);
            $item['Author'] = $author;
            $item['Icon'] = '';
            $item['Announce'] = $item['description'];
            unset($item['description']);
            $item['Body'] = $item['yandex:full-text'];
            unset($item['yandex:full-text']);
            $item['IsRss'] = 1;
            /* deprecated $item['IsTimered'] = 0; */
        }
        unset($item);
        
        $this->updateLastGuid($lastGuid);
    }
    
    /**
     * @param string $lastGuid 
     */
    protected function updateLastGuid($lastGuid)
    {
        $sql =  'UPDATE ' . C_DB_TABLE_PREFIX . 'news_import' . 
                " SET LastGuid='" . $this->db->addEscape($lastGuid) . "'" . 
                ' WHERE Id=' . $this->params->itemId;
        $this->db->query($sql);
    }
    
    /**
     * @return string|null
     */
    public function getLastGuid()
    {
        $sql =  'SELECT LastGuid' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'news_import' . 
                ' WHERE Id=' . $this->params->itemId;
        return $this->db->getValue($sql, 'LastGuid');
    }
    
    protected function setFields()
    {
        $this->fields = array(
            array('Type' => 'bool',         'Name' => 'Publicate',      'Value' => false,                       'Title' => 'Опубликовать',          'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'list',         'Name' => 'SectionId',      'Value' => $this->params->sectionId,    'Title' => 'Раздел',                'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true,     'Items' => 'getSections'),
            array('Type' => 'datetime',     'Name' => 'DateCreate',     'Value' => '',                          'Title' => 'Дата/время',            'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'text',         'Name' => 'Title',          'Value' => '',                          'Title' => 'Заголовок',             'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'combo',        'Name' => 'Author',         'Value' => '',                          'Title' => 'Автор',                 'Filter' => true,   'Show' => true,     'Sort' => false,    'Edit' => true,     'Items' => 'getAuthors'),
            array('Type' => 'image',        'Name' => 'Icon',           'Value' => '',                          'Title' => 'Картинка',              'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => true),
            array('Type' => 'mediumtext',   'Name' => 'Announce',       'Value' => '',                          'Title' => 'Анонс',                 'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'bigtext',      'Name' => 'Body',           'Value' => '',                          'Title' => 'Текст',                 'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'bool',         'Name' => 'IsRss',          'Value' => true,                        'Title' => 'Отображать в RSS',      'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            /* deprecated array('Type' => 'bool',         'Name' => 'IsTimered',      'Value' => false,                       'Title' => 'Отсрочка публикации',   'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true), */
        );
    }
    
    public function getItem()
    {
        return null;
    }
    
    public function update()
    {
        if (is_null($this->fields)) {
            $this->setFields();
        }
        $items = array();
        $itemsCount = isset($_POST['itemscount']) ? (int) $_POST['itemscount'] : 0;
        $fields = '';
        $valueaAll = '';
        $importCount = 0;
        for ($i = 1; $i <= $itemsCount; $i++) {
            if (!isset($_POST['Import' . $i]) || !$_POST['Import' . $i]) {
                continue;
            }
            $fields = '';
            $values = '';
            foreach ($this->fields as $field) {
                if (!$field['Edit']) {
                    continue;
                }
                $fieldName = $field['Name'] . $i;
                if (!isset($_POST[$fieldName]) && 'bool' != $field['Type']) {
                    $this->result = 'Request error: field `' . $fieldName . '` not set';
                    return;
                }
                switch ($field['Type']) {
                    case 'bool':
                        break;
                    case 'int':
                    case 'list':
                        $fields .= '`' . $field['Name'] . '`,';
                        $values .= (int) $_POST[$fieldName] . ',';
                        break;
                    default:
                        $fields .= '`' . $field['Name'] . '`,';
                        $values .= "'" . $this->db->addEscape($_POST[$fieldName]) . "',";
                }
            }
            $valueaAll .= '(' . substr($values, 0, -1) . ',' . (isset($_POST['Publicate' . $i]) ? '0' : '1') . ',1),';
            $importCount++;
        }
        if (0 < $importCount) {
            $sql =  'INSERT INTO ' . C_DB_TABLE_PREFIX . 'news' . 
                    ' (' . substr($fields, 0, -1) . ',IsHidden,IsRss)' . 
                    ' VALUES ' . substr($valueaAll, 0, -1);
            if ($this->db->query($sql)) {
                $this->result = 'updated';
                if (0 == $this->params->itemId) {
                    $this->lastInsertedId = $this->db->getLastInsertedId();
                }
            } else {
                $this->result = 'DB error: ' . $this->db->getError();
            }
        } else {
            $this->result = 'Nothing to import';
        }
    }
}
