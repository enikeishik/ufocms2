<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\News;

/**
 * News module import model class
 */
class ModelImport extends \Ufocms\AdminModules\Model
{
    /**
     * @see parent
     */
    protected function init()
    {
        parent::init();
        $this->itemsTable = 'news_import';
        $this->itemDisabledField = '';
        $this->defaultSort = 'Title';
    }
    
    protected function setItems()
    {
        $sql =  'SELECT *, Id AS itemid' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'news_import' . 
                ' WHERE SectionId=' . $this->params->sectionId . 
                ' ORDER BY Title';
        $this->items = $this->db->getItems($sql);
        $this->itemsCount = count($this->items);
    }
    
    protected function setFields()
    {
        //this model call as action, so filters can not be used
        $this->fields = array(
            array('Type' => 'int',      'Name' => 'Id',         'Value' => 0,   'Title' => 'id',                    'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => false),
            array('Type' => 'int',      'Name' => 'ItemsShow',  'Value' => 0,   'Title' => 'Количество элементов',  'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => false),
            array('Type' => 'text',     'Name' => 'Title',      'Value' => '',  'Title' => 'Заголовок',             'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => false),
            array('Type' => 'text',     'Name' => 'Url',        'Value' => '',  'Title' => 'URL',                   'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => false),
            array('Type' => 'text',     'Name' => 'LastGuid',   'Value' => '',  'Title' => 'Последний GUID',        'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => false),
            array('Type' => 'text',     'Name' => 'ItemAuthor', 'Value' => '',  'Title' => 'Авторство',             'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => false),
        );
    }
    
    public function getItem()
    {
        return null;
    }
    
    public function update()
    {
        $this->result = 'Not supported yet';
    }
    
    public function delete()
    {
        $this->result = 'Not supported yet';
    }
}
