<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\CoreRoles;

/**
 * Core [admin] roles permissions schema class
 */
class SchemaPermissions extends \Ufocms\AdminModules\Schema
{
    /**
     * Распаковка контейнера.
     */
    protected function unpackContainer()
    {
        
    }
    
    /**
     * @see parent
     */
    protected function setFields()
    {
        $this->fields = array(
            array('Type' => 'bool',     'Name' => 'create',     'Value' => false,   'Title' => 'Создание',          'Filter' => false,   'Show' => true,     'Sort' => false,     'Edit' => true),
            array('Type' => 'bool',     'Name' => 'edit',       'Value' => false,   'Title' => 'Редактирование',    'Filter' => false,   'Show' => true,     'Sort' => false,     'Edit' => true),
            array('Type' => 'bool',     'Name' => 'disable',    'Value' => false,   'Title' => 'Отключение',        'Filter' => false,   'Show' => true,     'Sort' => false,     'Edit' => true),
            array('Type' => 'bool',     'Name' => 'enable',     'Value' => false,   'Title' => 'Включение',         'Filter' => false,   'Show' => true,     'Sort' => false,     'Edit' => true),
            array('Type' => 'bool',     'Name' => 'delete',     'Value' => false,   'Title' => 'Удаление',          'Filter' => false,   'Show' => true,     'Sort' => false,     'Edit' => true),
        );
    }
}
