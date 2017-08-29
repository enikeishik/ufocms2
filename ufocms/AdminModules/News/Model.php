<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\News;

/**
 * News module model class
 */
class Model extends \Ufocms\AdminModules\Model
{
    /**
     * @see parent
     */
    protected function init()
    {
        parent::init();
        $this->itemDisabledField = 'IsHidden';
        $this->defaultSort = 'DateCreate DESC';
    }
    
    protected function setItems()
    {
        parent::setItems();
        $section = $this->core->getSection();
        $sectionPath = $section['path'];
        unset($section);
        foreach ($this->items as &$item) {
            $item['path'] = $sectionPath . $item['Id'] . '/';
        }
        unset($item);
    }
    
    protected function setFields()
    {
        $this->fields = array(
            array('Type' => 'int',          'Name' => 'Id',             'Value' => 0,                           'Title' => 'id',            'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => false),
            array('Type' => 'list',         'Name' => 'SectionId',      'Value' => $this->params->sectionId,    'Title' => 'Раздел',        'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => true,     'Required' => true,     'Items' => 'getSections'),
            array('Type' => 'datetime',     'Name' => 'DateCreate',     'Value' => date('Y-m-d H:i:s'),         'Title' => 'Дата создания', 'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => true,     'Required' => true,     'Class' => 'small'),
            array('Type' => 'text',         'Name' => 'Title',          'Value' => '',                          'Title' => 'Заголовок',     'Filter' => true,   'Show' => true,     'Sort' => true,     'Edit' => true,     'Required' => true,     'Autofocus' => true),
            array('Type' => 'combo',        'Name' => 'Author',         'Value' => '',                          'Title' => 'Автор',         'Filter' => true,   'Show' => true,     'Sort' => false,    'Edit' => true,     'Class' => 'small',     'Items' => 'getAuthors'),
            array('Type' => 'image',        'Name' => 'Icon',           'Value' => '',                          'Title' => 'Картинка',      'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => true),
            array('Type' => 'mediumtext',   'Name' => 'Announce',       'Value' => '',                          'Title' => 'Анонс',         'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => true),
            array('Type' => 'bigtext',      'Name' => 'Body',           'Value' => '',                          'Title' => 'Текст',         'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => true),
            array('Type' => 'bool',         'Name' => 'IsHidden',       'Value' => false,                       'Title' => 'Скрыто',        'Filter' => true,   'Show' => false,    'Sort' => false,    'Edit' => true,     'Items' => array(array('Value' => 1, 'Title' => 'Скрыто'), array('Value' => 0, 'Title' => 'Открыто'))),
            array('Type' => 'bool',         'Name' => 'IsRss',          'Value' => true,                        'Title' => 'RSS',           'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => true),
            /* deprecated array('Type' => 'bool',         'Name' => 'IsTimered',      'Value' => false,                       'Title' => 'Отсрочка',      'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => true), */
        );
    }
    
    protected function getAuthors()
    {
        static $authors = null;
        if (null === $authors) {
            $sql =  'SELECT DISTINCT Author' . 
                    ' FROM ' . C_DB_TABLE_PREFIX . 'news' . 
                    ' ORDER BY Author';
            $authors = $this->db->getItems($sql);
            foreach ($authors as &$item) {
                $item = array('Value' => $item['Author'], 'Title' => $item['Author']);
            }
            unset($item);
        }
        return $authors;
    }
}
