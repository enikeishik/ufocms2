<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\Shop;

/**
 * Widget class
 */
class Widget extends \Ufocms\AdminModules\Widget
{
    /**
     * @see parent
     */
    protected function init()
    {
        parent::init();
        $this->singleSource = true;
        $this->sourceDepends = true;
    }

    /**
     * @see parent
     */
    protected function setFields()
    {
        $this->fields = array(
            array('Type' => 'list',     'Name' => 'CategoryId',     'Value' => 0,       'Title' => 'Категория',                 'Edit' => true,     'Items' => 'getCategories'),
            array('Type' => 'bool',     'Name' => 'CatChildren',    'Value' => true,    'Title' => 'Исп. дочерние категории',   'Edit' => true),
            array('Type' => 'int',      'Name' => 'ItemsStart',     'Value' => 0,       'Title' => 'Пропустить эл-ов',          'Edit' => true),
            array('Type' => 'int',      'Name' => 'ItemsCount',     'Value' => 5,       'Title' => 'Вывести эл-ов',             'Edit' => true),
            array('Type' => 'list',     'Name' => 'SortOrder',      'Value' => 0,       'Title' => 'Сортировка',                'Edit' => true,     'Items' => 'getSort'),
        );
    }
    
    /**
     * @return array
     */
    protected function getCategories()
    {
        $all = ['Value' => 0, 'Title' => 'Все категории'];
        if (0 == $this->moduleParams['SrcSections']) {
            return array($all);
        }
        $container = $this->core->getContainer([
            'debug'     => &$this->debug, 
            'config'    => &$this->config, 
            'params'    => &$this->params, 
            'db'        => &$this->db, 
            'core'      => &$this->core, 
        ]);
        $catObj = new ModelCategories($container);
        $categories = $catObj->getCategories($this->moduleParams['SrcSections']);
        unset($catObj);
        array_shift($categories);
        return array_merge(array($all), $categories);
    }
    
    /**
     * @return array
     */
    protected function getSort()
    {
        return array(
            array('Value' => 0, 'Title' => 'По порядку, по возрастанию'),
            array('Value' => 1, 'Title' => 'По порядку, по убыванию'),
            array('Value' => 2, 'Title' => 'По дате, по убыванию'),
            array('Value' => 3, 'Title' => 'По дате, по возрастанию'),
            array('Value' => 4, 'Title' => 'По названию, по возрастанию'),
            array('Value' => 5, 'Title' => 'По названию, по убыванию'),
            array('Value' => 6, 'Title' => 'По количеству просмотров, по убыванию'),
            array('Value' => 7, 'Title' => 'По количеству просмотров, по возрастанию'),
        );
    }
}
