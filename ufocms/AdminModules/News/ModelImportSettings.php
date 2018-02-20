<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\News;

/**
 * News module model class
 */
class ModelImportSettings extends \Ufocms\AdminModules\Model
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
        $this->config->registerAction('resetguid');
        $this->config->registerMakeAction('resetguid');
    }
    
    protected function setFields()
    {
        $this->fields = array(
            array('Type' => 'int',      'Name' => 'Id',         'Value' => 0,                           'Title' => 'id',                    'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => false),
            array('Type' => 'int',      'Name' => 'SectionId',  'Value' => $this->params->sectionId,    'Title' => 'Раздел',                'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => true,     'Items' => 'getSections'),
            array('Type' => 'int',      'Name' => 'ItemsShow',  'Value' => 20,                          'Title' => 'Количество элементов',  'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => true,     'Required' => true),
            array('Type' => 'text',     'Name' => 'Title',      'Value' => '',                          'Title' => 'Заголовок',             'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => true,     'Required' => true),
            array('Type' => 'text',     'Name' => 'Url',        'Value' => '',                          'Title' => 'URL',                   'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => true,     'Required' => true),
            array('Type' => 'text',     'Name' => 'LastGuid',   'Value' => '',                          'Title' => 'Последний GUID',        'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'text',     'Name' => 'ItemAuthor', 'Value' => '',                          'Title' => 'Авторство',             'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => true),
        );
    }
    
    public function resetguid()
    {
        $sql =  'UPDATE ' . C_DB_TABLE_PREFIX . 'news_import' . 
                " SET LastGuid=''" . 
                ' WHERE Id=' . $this->params->itemId;
        if ($this->db->query($sql)) {
            $this->result = 'GUID resetted';
        } else {
            $this->result = 'DB error: ' . $this->db->getError();
        }
    }
}
