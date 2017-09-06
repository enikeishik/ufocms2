<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\Oldurls;

/**
 * News module model class
 */
class Model extends \Ufocms\AdminModules\Model
{
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
            $item['path'] = $sectionPath . $item['Url'];
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
            array('Type' => 'text',         'Name' => 'Url',            'Value' => '',                          'Title' => 'URL старый',    'Filter' => true,   'Show' => true,     'Sort' => true,     'Edit' => true,     'Required' => true,     'Autofocus' => true),
            array('Type' => 'text',         'Name' => 'Target',         'Value' => '',                          'Title' => 'URL новый',     'Filter' => true,   'Show' => true,     'Sort' => true,     'Edit' => true,     'Info' => 'При наличии нового URL будет производиться автоматическая переадресация пользователя на этот URL'),
            array('Type' => 'text',         'Name' => 'Title',          'Value' => '',                          'Title' => 'Заголовок',     'Filter' => true,   'Show' => true,     'Sort' => true,     'Edit' => true),
            array('Type' => 'text',         'Name' => 'MetaKeys',       'Value' => '',                          'Title' => 'Ключ.слова',    'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => true),
            array('Type' => 'text',         'Name' => 'MetaDesc',       'Value' => '',                          'Title' => 'SEO описание',  'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => true),
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
                ' FROM ' . C_DB_TABLE_PREFIX . 'oldurls' . 
                ' WHERE SectionId=' . $this->params->sectionId;
        $on = $this->db->getValue($sql, 'MaxOrderNumber');
        return (null !== $on ? $on : 0) + self::ORDERNUMBER_STEP;
    }
    
    /**
     * @see parent
     */
    protected function getFormFieldData0(array $field)
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
