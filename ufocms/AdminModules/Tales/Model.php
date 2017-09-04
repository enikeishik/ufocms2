<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\Tales;

/**
 * News module model class
 */
class Model extends \Ufocms\AdminModules\Model
{
    use Aliases;
    
    const ORDERNUMBER_STEP = 10;
    
    /**
     * @see parent
     */
    protected function init()
    {
        parent::init();
        $this->itemDisabledField = 'IsHidden';
        $this->defaultSort = 'OrderNumber';
    }
    
    protected function setItems()
    {
        $section = $this->core->getSection();
        $sectionPath = $section['path'];
        unset($section);
        parent::setItems();
        foreach ($this->items as &$item) {
            $item['path'] = $sectionPath . $item['Id'];
        }
        unset($item);
    }
    
    protected function setFields()
    {
        $this->fields = array(
            array('Type' => 'int',          'Name' => 'Id',             'Value' => 0,                           'Title' => 'id',            'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => false),
            array('Type' => 'int',          'Name' => 'UserId',         'Value' => 0,                           'Title' => 'userId',        'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => false),
            array('Type' => 'list',         'Name' => 'SectionId',      'Value' => $this->params->sectionId,    'Title' => 'Раздел',        'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => true,     'Required' => true,     'Items' => 'getSections'),
            array('Type' => 'int',          'Name' => 'OrderNumber',    'Value' => $this->getMaxOrderNumber(),  'Title' => 'Порядк.номер',  'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => true),
            array('Type' => 'datetime',     'Name' => 'DateCreate',     'Value' => date('Y-m-d H:i:s'),         'Title' => 'Дата создания', 'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => true,     'Required' => true,     'Class' => 'small'),
            array('Type' => 'datetime',     'Name' => 'DateView',       'Value' => '',                          'Title' => 'Дата просм.',   'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => false,    'Class' => 'small'),
            array('Type' => 'text',         'Name' => 'Url',            'Value' => '',                          'Title' => 'URL',           'Filter' => true,   'Show' => true,     'Sort' => true,     'Edit' => true,     'Required' => true),
            array('Type' => 'text',         'Name' => 'Title',          'Value' => '',                          'Title' => 'Заголовок',     'Filter' => true,   'Show' => true,     'Sort' => true,     'Edit' => true,     'Required' => true,     'Autofocus' => true),
            array('Type' => 'text',         'Name' => 'MetaKeys',       'Value' => '',                          'Title' => 'Ключ.слова',    'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => true,     'Required' => false),
            array('Type' => 'text',         'Name' => 'MetaDesc',       'Value' => '',                          'Title' => 'SEO описание',  'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => true,     'Required' => false),
            array('Type' => 'combo',        'Name' => 'Author',         'Value' => '',                          'Title' => 'Автор',         'Filter' => true,   'Show' => false,    'Sort' => false,    'Edit' => true,     'Items' => 'getAuthors'),
            array('Type' => 'image',        'Name' => 'Icon',           'Value' => '',                          'Title' => 'Картинка',      'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => true),
            array('Type' => 'mediumtext',   'Name' => 'Announce',       'Value' => '',                          'Title' => 'Анонс',         'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => true),
            array('Type' => 'bigtext',      'Name' => 'Body',           'Value' => '',                          'Title' => 'Текст',         'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => true),
            array('Type' => 'int',          'Name' => 'ViewedCnt',      'Value' => 0,                           'Title' => 'Просмотров',    'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => false),
            array('Type' => 'bool',         'Name' => 'IsHidden',       'Value' => false,                       'Title' => 'Скрыто',        'Filter' => true,   'Show' => false,    'Sort' => false,    'Edit' => true,     'Items' => array(array('Value' => 1, 'Title' => 'Скрыто'), array('Value' => 0, 'Title' => 'Открыто'))),
        );
    }
    
    /**
     * @return int
     */
    protected function getMaxOrderNumber()
    {
        $sql =  'SELECT MAX(OrderNumber) AS MaxOrderNumber' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'tales' . 
                ' WHERE SectionId=' . $this->params->sectionId;
        $on = $this->db->getValue($sql, 'MaxOrderNumber');
        return (null !== $on ? $on : 0) + self::ORDERNUMBER_STEP;
    }
    
    /**
     * @return array
     */
    protected function getAuthors()
    {
        static $authors = null;
        if (null === $authors) {
            $sql =  'SELECT DISTINCT Author' . 
                    ' FROM ' . C_DB_TABLE_PREFIX . 'tales' . 
                    ' ORDER BY Author';
            $authors = $this->db->getItems($sql);
            foreach ($authors as &$item) {
                $item = array('Value' => $item['Author'], 'Title' => $item['Author']);
            }
            unset($item);
        }
        return $authors;
    }
    
    /**
     * @return string
     */
    public function getAliases()
    {
        $sql =  'SELECT Url AS Alias FROM ' . C_DB_TABLE_PREFIX . 'tales' . 
                ' WHERE SectionId=' . $this->params->sectionId;
        if (0 != $this->params->itemId) {
            $sql .= ' AND Id!=' . $this->params->itemId;
        }
        return $this->db->getValues($sql, 'Alias');
    }
    
    /**
     * @see parent
     */
    protected function getFormFieldData(array $field)
    {
        if ('Url' == $field['Name']) {
            $aliases = $this->getAliases();
            $alias = $this->getUnicAlias($this->getAliasFromText($_POST['Url']), $aliases);
            unset($aliases);
            return array('Type' => $field['Type'], 'Value' => $alias);
        }
        return parent::getFormFieldData($field);
    }
}
