<?php
/**
 * UFOCMS v2 Content Management System
 * 
 * @copyright   Copyright (C) 2005 - 2017 Enikeishik <enikeishik@gmail.com>. All rights reserved.
 * @author      Enikeishik <enikeishik@gmail.com>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Ufocms\AdminModules;

use Ufocms\Frontend\DIObject;

/**
 * Base UI constructs
 */
class UI extends DIObject
{
    /**
     * @var \Ufocms\Frontend\Debug
     */
    protected $debug = null;
    
    /**
     * Ссылка на объект конфигурации.
     * @var \Ufocms\Backend\Config
     */
    protected $config = null;
    
    /**
     * @var \Ufocms\Backend\Params
     */
    protected $params = null;
    
    /**
     * @var \Ufocms\Backend\Core
     */
    protected $core = null;
    
    /**
     * @var array
     */
    protected $module = null;
    
    /**
     * Availabe module level parameters
     * @var array
     */
    protected $moduleParams = null;
    
    /**
     * @var \Ufocms\Frontend\Tools
     */
    protected $tools = null;
    
    /**
     * @var Model
     */
    protected $model  = null;
    
    /**
     * @var string
     */
    protected $layout = '';
    
    /**
     * @var string
     */
    protected $basePath = '';
    
    /**
     * @var array
     */
    protected $basePathItems = array();
    
    /**
     * @var array
     */
    protected $section = null;
    
    /**
     * @var array
     */
    protected $mainTabs = array();
    
    /**
     * @var array
     */
    protected $itemFuncs = array();
    
    /**
     * Распаковка контейнера.
     */
    protected function unpackContainer()
    {
        $this->debug =& $this->container->getRef('debug');
        $this->config =& $this->container->getRef('config');
        $this->params =& $this->container->getRef('params');
        $this->core =& $this->container->getRef('core');
        $this->tools =& $this->container->getRef('tools');
        $this->module =& $this->container->getRef('module');
        $this->moduleParams =& $this->container->getRef('moduleParams');
        $this->model =& $this->container->getRef('model');
        $this->layout = $this->container->get('layout');
        $this->basePath = $this->container->get('basePath');
        //TODO: remove to init and make parent::init() in children
        parse_str(('' != $this->basePath && '?' == $this->basePath[0] ? substr($this->basePath, 1) : $this->basePath), $this->basePathItems);
    }
    
    /**
     * Инициализация объекта. Переобпределяется в потомках по необходимости.
     */
    protected function init()
    {
        $this->section = $this->core->getSection();
        if (is_null($this->section)) {
            $site = $this->core->getSite();
            $this->section = array('path' => '/', 'title' => (isset($site['SiteTitle']) && '' != $site['SiteTitle']['PValue'] ? $site['SiteTitle']['PValue'] : 'UFO CMS'));
            unset($site);
        }
    }
    
    /**
     * Отрисовка ссылок страниц.
     * @param int $page
     * @param int $pagesCount
     * @param int $pagesShow
     * @param int $pageStart
     * @param int $pageStop
     * @param bool $morePages
     * @return string
     */
    protected function paginationPages($page, $pagesCount, $pagesShow, $pageStart, $pageStop, $morePages)
    {
        $s = '';
        if ($page > 1) {
            $s .= ' <a href="' . $this->basePath . '&' . $this->config->paramsNames['page'] . '=' . ($page - 1) . '">&laquo; Предыдущая</a> &nbsp; ';
        }
        if ($page > $pagesShow) {
            $s .= ' <a href="' . $this->basePath . '&' . $this->config->paramsNames['page'] . '=' . ($pageStart - 1) . '">&lt;&lt;</a> ';
        }
        for ($i = $pageStart; $i <= $pageStop; $i++) {
            if ($i != $page) {
                $s .= '<a href="' . $this->basePath . '&' . $this->config->paramsNames['page'] . '=' . $i . '">' . $i . '</a> ';
            } else {
                $s .= '<b>' . $i . '</b> ';
            }
        }
        if ($morePages) {
            $s .= ' <a href="' . $this->basePath . '&' . $this->config->paramsNames['page'] . '=' . ($pageStop + 1) . '">&gt;&gt;</a> ';
        }
        if ($page < $pagesCount) {
            $s .= ' &nbsp; <a href="' . $this->basePath . '&' . $this->config->paramsNames['page'] . '=' . ($page + 1) . '">Следующая &raquo;</a> ';
        }
        return $s;
    }
    
    /**
     * Отрисовка выбора размера страниц.
     * @param int $pageSize
     * @return string
     */
    protected function paginationPageSize($pageSize)
    {
        $s =    'Выводить по <select onchange="location.href=\'' . $this->basePath . '&' . $this->config->paramsNames['page'] . '=1&' . $this->config->paramsNames['pageSize'] . '=\' + this.value">' . 
                '<option value="1"' . (1==$pageSize?' selected="selected"':'') . '>1</option>' . 
                '<option value="2"' . (2==$pageSize?' selected="selected"':'') . '>2</option>' . 
                '<option value="3"' . (3==$pageSize?' selected="selected"':'') . '>3</option>' . 
                '<option value="4"' . (4==$pageSize?' selected="selected"':'') . '>4</option>' . 
                '<option value="5"' . (5==$pageSize?' selected="selected"':'') . '>5</option>' . 
                '<option value="10"' . (10==$pageSize?' selected="selected"':'') . '>10</option>' . 
                '<option value="20"' . (20==$pageSize?' selected="selected"':'') . '>20</option>' . 
                '<option value="30"' . (30==$pageSize?' selected="selected"':'') . '>30</option>' . 
                '<option value="40"' . (40==$pageSize?' selected="selected"':'') . '>40</option>' . 
                '<option value="50"' . (50==$pageSize?' selected="selected"':'') . '>50</option>' . 
                '<option value="100"' . (100==$pageSize?' selected="selected"':'') . '>100</option>' . 
                '<option value="200"' . (200==$pageSize?' selected="selected"':'') . '>200</option>' . 
                '<option value="300"' . (300==$pageSize?' selected="selected"':'') . '>300</option>' . 
                '<option value="400"' . (400==$pageSize?' selected="selected"':'') . '>400</option>' . 
                '<option value="500"' . (500==$pageSize?' selected="selected"':'') . '>500</option>' . 
                '<option value="1000"' . (1000==$pageSize?' selected="selected"':'') . '>1000</option>' . 
                '</select> записей';
        return $s;
    }
    
    /**
     * Формирование постраничной навигации.
     * @return string
     * @todo: define pagesShow in config
     */
    public function pagination()
    {
        $count = $this->model->getItemsCount();
        if (0 == $count) {
            return '';
        }
        $page = $this->params->page;
        $pageSize = $this->params->pageSize;
        
        $pagesShow = 10;
        $pagesCount = (int) floor(($count - 1) / $pageSize) + 1;
        
        $pageStart = (floor(($page - 1) / $pagesShow) * $pagesShow + 1);
        $morePages  = (floor($pagesCount / $pagesShow) - floor(($page - 1) / $pagesShow)) >= 1;
        if ($morePages) {
            $pageStop = (floor(($page - 1) / $pagesShow) * $pagesShow + $pagesShow);
        } else {
            $pageStop = $pagesCount;
        }
        
        $s = '<table class="pages">' . 
                '<tr><td>Всего записей: ' . $count;
        if ($count > $pageSize) {
            $s .= ' | Страницы: ' . $this->paginationPages($page, $pagesCount, $pagesShow, $pageStart, $pageStop, $morePages);
        }
        $s .=   '</td><td align="right">' . $this->paginationPageSize($pageSize) . '</td></tr></table>';
        return $s;
    }
    
    /**
     * @param array $field
     * @param array $items
     * @param string $basePathFields
     * @return string
     */
    protected function filterByFieldItems(array $field, array $items, $basePathFields)
    {
        $s = '';
        $s .=   '<td><form action="' . $this->basePath . '" method="get">' . 
                '<div>Значения поля «' . htmlspecialchars($field['Title']) . '»</div>' . 
                $basePathFields . 
                '<input type="hidden" name="' . $this->config->paramsNames['action'] . '" value="filter">' . 
                '<input type="hidden" name="' . $this->config->paramsNames['page'] . '" value="1">' . 
                '<input type="hidden" name="filtername" value="' . $field['Name'] . '">';
        if ('slist' == $field['Type'] && (!isset($field['ItemsDefault']) || !$field['ItemsDefault'])) {
            //for lists with string type values we have to reset filter instead send empty string
            //because in model values of fields with type 'list' considered as strings
            $s .= '<select name="filtervalue" onchange="if(0==this.options.selectedIndex){this.form.elements[\'filtername\'].value=\'\'}else{this.form.elements[\'filtername\'].value=this.form.elements[\'filtername\'].defaultValue}">';
        } else {
            $s .= '<select name="filtervalue">';
        }
        if (!isset($field['ItemsDefault']) || !$field['ItemsDefault']) {
            $s .= '<option value="">(все значения)</option>';
        }
        foreach ($items as $item) {
            if ($this->params->filterName != $field['Name'] || '' == $this->params->filterValue || $this->params->filterValue != $item['Value']) {
                $s .= '<option value="' . htmlspecialchars($item['Value']) . '">' . htmlspecialchars($item['Title']) . '</option>';
            } else {
                $s .= '<option value="' . htmlspecialchars($item['Value']) . '" selected>' . htmlspecialchars($item['Title']) . '</option>';
            }
        }
        $s .=   '</select>' . 
                '<input type="submit" value="&gt;">' . 
                '</form></td>';
        return $s;
    }
    
    /**
     * Отрисовка фильтра по типу поля int.
     * @param array $field
     * @param string $basePathFields
     * @return string
     */
    protected function filterByTypeInt(array $field, $basePathFields)
    {
        $s =    '<form action="' . $this->basePath . '" method="get">' . 
                '<div>Искать в поле «' . $field['Title'] . '»</div>' . 
                $basePathFields . 
                '<input type="hidden" name="' . $this->config->paramsNames['action'] . '" value="filter">' . 
                '<input type="hidden" name="' . $this->config->paramsNames['page'] . '" value="1">' . 
                '<input type="hidden" name="filtername" value="' . $field['Name'] . '">' . 
                '<input type="number" name="filtervalue" value="' . ($this->params->filterName == $field['Name'] ? htmlspecialchars($this->params->filterValue) : '') . 
                    '" maxlength="10">' . 
                '<input type="submit" value="&gt;">' . 
                '</form>';
        return $s;
    }
    
    /**
     * Отрисовка фильтра по типу поля float.
     * @param array $field
     * @param string $basePathFields
     * @return string
     */
    protected function filterByTypeFloat(array $field, $basePathFields)
    {
        $s =    '<form action="' . $this->basePath . '" method="get">' . 
                '<div>Искать в поле «' . $field['Title'] . '»</div>' . 
                $basePathFields . 
                '<input type="hidden" name="' . $this->config->paramsNames['action'] . '" value="filter">' . 
                '<input type="hidden" name="' . $this->config->paramsNames['page'] . '" value="1">' . 
                '<input type="hidden" name="filtername" value="' . $field['Name'] . '">' . 
                '<input type="number" step="0.01" name="filtervalue" value="' . ($this->params->filterName == $field['Name'] ? htmlspecialchars($this->params->filterValue) : '') . 
                    '" maxlength="12">' . 
                '<input type="submit" value="&gt;">' . 
                '</form>';
        return $s;
    }
    
    /**
     * Отрисовка фильтра по типу поля text.
     * @param array $field
     * @param string $basePathFields
     * @return string
     */
    protected function filterByTypeText(array $field, $basePathFields)
    {
        $s =    '<form action="' . $this->basePath . '" method="get">' . 
                '<div>Искать в поле «' . $field['Title'] . '»</div>' . 
                $basePathFields . 
                '<input type="hidden" name="' . $this->config->paramsNames['action'] . '" value="filter">' . 
                '<input type="hidden" name="' . $this->config->paramsNames['page'] . '" value="1">' . 
                '<input type="hidden" name="filtername" value="' . $field['Name'] . '">' . 
                '<input type="text" name="filtervalue" value="' . ($this->params->filterName == $field['Name'] ? htmlspecialchars($this->params->filterValue) : '') . 
                    '" maxlength="255">' . 
                '<input type="submit" value="&gt;">' . 
                '</form>';
        return $s;
    }
    
    /**
     * Отрисовка фильтра по типу поля.
     * @param array $field
     * @param string $basePathFields
     * @return string
     */
    protected function filterByType(array $field, $basePathFields)
    {
        switch ($field['Type']) {
            case 'combo':
            case 'list':
            case 'rlist':
            case 'slist':
            case 'mlist':
            case 'mslist':
            case 'bool':
                $items = $this->model->getFieldItems($field); //$field['Items']
                if (is_null($items) && 'bool' == $field['Type']) {
                    $items = array(array('Value' => 1, 'Title' => 'Скрыто'), array('Value' => 0, 'Title' => 'Открыто'));
                }
                return $this->filterByFieldItems($field, $items, $basePathFields);
                
            case 'text':
            case 'mediumtext':
            case 'bigtext':
                return $this->filterByTypeText($field, $basePathFields);
                
            case 'int':
                return $this->filterByTypeInt($field, $basePathFields);
                
            case 'float':
                return $this->filterByTypeFloat($field, $basePathFields);
                
            default:
                $method = 'filterByType' . ucfirst($field['Type']);
                if (method_exists($this, $method)) {
                    return $this->$method($field, $basePathFields);
                } else {
                    $items = $this->model->getFieldItems($field); //$field['Items']
                    if (!is_null($items)) {
                        return $this->filterByFieldItems($field, $items, $basePathFields);
                    } else {
                        return '<form><div>Not implemented yet</div></form>';
                    }
                }
        }
    }
    
    /**
     * Отрисовка сброса фильтров.
     * @param string $basePathFields
     * @return string
     */
    protected function filtersReset($basePathFields)
    {
        $s =    '<form action="' . $this->basePath . '" method="get">' . 
                $basePathFields . 
                '<input type="hidden" name="' . $this->config->paramsNames['action'] . '" value="filter">' . 
                '<input type="hidden" name="' . $this->config->paramsNames['page'] . '" value="1">' . 
                '<input type="hidden" name="filtername" value="">' . 
                '<input type="hidden" name="filtervalue" value="">' . 
                '<input type="submit" class="reset" value="сброс"></form>';
        return $s;
    }
    
    /**
     * @return string
     */
    public function filters()
    {
        $fields = $this->model->getFields();
        
        $basePathFields = '';
        foreach ($this->basePathItems as $name => $value) {
            $basePathFields .= '<input type="hidden" name="' . $name . '" value="' . $value . '">';
        }
        
        $s = '';
        foreach ($fields as $field) {
            if ($field['Filter']) {
                $s .= '<td>' . $this->filterByType($field, $basePathFields) . '</td>';
            }
        }
        if ('' == $s) {
            return '';
        }
        return  '<table class="filters"><tr>' . 
                $s . '<td>' . $this->filtersReset($basePathFields) . '</td>' . 
                '</tr></table>';
    }
    
    /**
     * Получение HTML кода для функционала элемента.
     * @param array $item
     * @param string $func
     * @return string
     */
    protected function getItemFuncHtml(array $item, $func)
    {
        $roles = $this->core->getRoles();
        $userId = $this->core->getUsers()->getCurrent()['Id'];
        $module = (0 != $this->module['ModuleId'] ? (int) $this->module['ModuleId'] : (string) $this->module['Module']);
        if (!$roles->rolesPermittedAction($userId, $module, $func)) {
            return '';
        }
        
        switch ($func) {
            case 'open':
                if (array_key_exists('path', $item)) {
                    return ' <a href="' . $item['path'] . '" target="_blank" title="Открыть">Открыть</a>';
                } else {
                    return '';
                }
            case 'add':
                if ($this->model->isCanCreateItems()) {
                    return '<a href="' . $this->basePath . '&' . $this->config->paramsNames['action'] . '=add&' . $this->config->paramsNames['itemId'] . '=0" title="Создать">Создать</a>';
                } else {
                    return '';
                }
            case 'edit':
                if ($this->model->isCanUpdateItems()) {
                    return ' <a href="' . $this->basePath . '&' . $this->config->paramsNames['action'] . '=edit&' . $this->config->paramsNames['itemId'] . '=' . $item['itemid'] . '" title="Редактировать">Редактировать</a>';
                } else {
                    return '';
                }
            case 'html':
                return ' <a href="' . $this->basePath . '&' . $this->config->paramsNames['action'] . '=edit&' . $this->config->paramsNames['itemId'] . '=' . $item['itemid'] . '&code=1" title="Редактировать в HTML редакторе">HTML</a>';
            case 'disable':
            case 'enable':
                if (array_key_exists('disabled', $item)) {
                    if ($item['disabled']) {
                        return ' <a href="' . $this->basePath . '&' . $this->config->paramsNames['action'] . '=enable&' . $this->config->paramsNames['itemId'] . '=' . $item['itemid'] . '" title="Включить">&#9632;</a>';
                    } else {
                        return ' <a href="' . $this->basePath . '&' . $this->config->paramsNames['action'] . '=disable&' . $this->config->paramsNames['itemId'] . '=' . $item['itemid'] . '" title="Отключить">&#9633;</a>';
                    }
                } else {
                    return '';
                }
            case 'delete':
                if ($this->model->isCanDeleteItems()) {
                    return ' <a href="' . $this->basePath . '&' . $this->config->paramsNames['action'] . '=delete&' . $this->config->paramsNames['itemId'] . '=' . $item['itemid'] . '" title="Удалить">&#8252;</a>';
                } else {
                    return '';
                }
            case 'delconfirm':
                if ($this->model->isCanDeleteItems()) {
                    return ' <a href="' . $this->basePath . '&' . $this->config->paramsNames['action'] . '=delete&' . $this->config->paramsNames['itemId'] . '=' . $item['itemid'] . '" title="Удалить" onclick="return confirm(\'Удалить запись?\')">&#8252;</a>';
                } else {
                    return '';
                }
            case 'up':
                return ' <a href="' . $this->basePath . '&' . $this->config->paramsNames['action'] . '=up&' . $this->config->paramsNames['itemId'] . '=' . $item['itemid'] . '" title="Переместить вверх">&#8593;</a>';
            case 'down':
                return ' <a href="' . $this->basePath . '&' . $this->config->paramsNames['action'] . '=down&' . $this->config->paramsNames['itemId'] . '=' . $item['itemid'] . '" title="Переместить вниз">&#8595;</a>';
            default:
                return '';
        }
    }
    
    /**
     * Установка функционалов элемента (вчистую).
     * @param array $item
     * @param array $funcs
     */
    protected function setItemFuncs(array $item, array $funcs)
    {
        $this->itemFuncs = array();
        foreach ($funcs as $func) {
            $this->appendItemFunc($func, $this->getItemFuncHtml($item, $func));
        }
    }
    
    /**
     * Получение HTML кода функционалов элемента.
     * @param array $item
     * @param array $funcs
     * @return string
     */
    protected function getItemFuncsHtml(array $item, array $funcs)
    {
        $this->setItemFuncs($item, $funcs);
        return implode('', array_values($this->itemFuncs));
    }
    
    /**
     * Удаление функционала элемента.
     * @param string $funcId
     */
    protected function removeItemFunc($funcId)
    {
        if (array_key_exists($funcId, $this->itemFuncs)) {
            unset($this->itemFuncs[$funcId]); //remove this the same id if exists
        }
    }
    
    /**
     * Добавление функционала элемента.
     * @param string $funcId
     * @param string $funcHtml
     * @param string $afterFuncId = null
     */
    protected function appendItemFunc($funcId, $funcHtml, $afterFuncId = null)
    {
        if ('' == $funcHtml) {
            return;
        }
        if (is_null($afterFuncId)) {
            $this->itemFuncs[$funcId] = $funcHtml;
        } else if ('' == $afterFuncId) {
            //remove this the same id if exists
            $this->removeItemFunc($funcId);
            $this->itemFuncs = array_merge(array($funcId => $funcHtml), $this->itemFuncs);
        } else {
            //remove this the same id if exists
            $this->removeItemFunc($funcId);
            $tabs = array();
            foreach ($this->itemFuncs as $id => $html) {
                $tabs[$id] = $html;
                if ($id == $afterFuncId) {
                    $tabs[$funcId] = $funcHtml;
                }
            }
            $this->itemFuncs = $tabs;
        }
    }
    
    /**
     * @return string
     */
    protected function fieldSort($fieldName)
    {
        if ($this->params->sortField == $fieldName) {
            if (0 == strcasecmp($this->params->sortDirection, 'desc')) {
                return  ' <a href="' . $this->basePath . '&' . $this->config->paramsNames['action'] . '=sort&' . $this->config->paramsNames['sortField'] . '=' . $fieldName . '&' . $this->config->paramsNames['sortDirection'] . '=asc">&#9650;</a>' . 
                        '<b>&#9660;</b>';
            } else {
                return  ' <b>&#9650;</b>' . 
                        '<a href="' . $this->basePath . '&' . $this->config->paramsNames['action'] . '=sort&' . $this->config->paramsNames['sortField'] . '=' . $fieldName . '&' . $this->config->paramsNames['sortDirection'] . '=desc">&#9660;</a>';
            }
        } else {
            return  ' <a href="' . $this->basePath . '&' . $this->config->paramsNames['action'] . '=sort&' . $this->config->paramsNames['sortField'] . '=' . $fieldName . '&' . $this->config->paramsNames['sortDirection'] . '=asc">&#9650;</a>' . 
                    '<a href="' . $this->basePath . '&' . $this->config->paramsNames['action'] . '=sort&' . $this->config->paramsNames['sortField'] . '=' . $fieldName . '&' . $this->config->paramsNames['sortDirection'] . '=desc">&#9660;</a>';
        }
    }
    
    /**
     * Generate content for external field.
     * @param array $field
     * @param array $item
     * @return string
     */
    protected function getExternalFieldContent(array $field, array $item)
    {
        $fieldItems = $this->model->getFieldItems($field); //$field['Items']
        if (null === $fieldItems) {
            return $item[$field['Name']];
        }
        $s = '';
        foreach ($fieldItems as $fieldItem) {
            $s .= $fieldItem['Title'] . ' ';
        }
        return $s;
    }
    
    /**
     * @param array $fields
     * @return string
     */
    protected function listItemsHeaderField(array $field)
    {
        return  '<th>' . $field['Title'] . 
                ($field['Sort'] ? $this->fieldSort($field['Name']) : '') . 
                '</th>';
    }
    
    /**
     * @param array $fields
     * @return string
     */
    protected function listItemsHeader(array $fields)
    {
        $s =    '<tr>';
        foreach ($fields as $field) {
            if (!$field['Show']) {
                continue;
            }
            $s .= $this->listItemsHeaderField($field);
        }
        $s .=   '<th>Управление&nbsp;' . 
                    '<a href="' . $this->basePath . '&' . $this->config->paramsNames['action'] . '=sort&' . $this->config->paramsNames['sortField'] . '=" title="Сбросить сортировку">&#9674;</a>' . 
                '</th>' . 
                '</tr>';
        return $s;
    }
    
    /**
     * @param array $field
     * @param array $item
     * @return string
     */
    protected function listItemsItemFieldRaw(array $field, array $item)
    {
        return  '<td class="type-' . $field['Type'] . (isset($field['Class']) ? ' ' . $field['Class'] : '') . '">' . 
                    $item[$field['Name']] . 
                '</td>';
    }
    
    /**
     * @param array $field
     * @param array $item
     * @return string
     */
    protected function listItemsItemFieldExternal(array $field, array $item)
    {
        return  '<td class="type-' . $field['Type'] . (isset($field['Class']) ? ' ' . $field['Class'] : '') . '">' . 
                    $this->getExternalFieldContent($field, $item) . 
                '</td>';
    }
    
    /**
     * @param array $field
     * @param array $item
     * @return string
     */
    protected function listItemsItemFieldList(array $field, array $item)
    {
        $value = $item[$field['Name']];
        $fieldItems = $this->model->getFieldItems($field); //$field['Items']
        foreach ($fieldItems as $fieldItem) {
            if ($value == $fieldItem['Value']) {
                $value = $fieldItem['Title'];
                break;
            }
        }
        return  '<td class="type-' . $field['Type'] . (isset($field['Class']) ? ' ' . $field['Class'] : '') . '" title="' . htmlspecialchars($item[$field['Name']]) . '">' . 
                    htmlspecialchars($value) . 
                '</td>';
    }
    
    /**
     * @param array $field
     * @param array $item
     * @return string
     */
    protected function listItemsItemFieldBool(array $field, array $item)
    {
        $value = $item[$field['Name']] ? '+' : '-';
        return  '<td class="type-' . $field['Type'] . (isset($field['Class']) ? ' ' . $field['Class'] : '') . '" title="' . htmlspecialchars($item[$field['Name']]) . '">' . 
                    htmlspecialchars($value) . 
                '</td>';
    }
    
    /**
     * @param array $field
     * @param array $item
     * @return string
     */
    protected function listItemsItemFieldDefault(array $field, array $item)
    {
        return  '<td class="type-' . $field['Type'] . (isset($field['Class']) ? ' ' . $field['Class'] : '') . '">' . 
                    htmlspecialchars($item[$field['Name']]) . 
                '</td>';
    }
    
    /**
     * @param array $field
     * @param array $item
     * @return string
     */
    protected function listItemsItemField(array $field, array $item)
    {
        if (isset($field['Raw']) && $field['Raw']) {
            return $this->listItemsItemFieldRaw($field, $item);
        } else if ((isset($field['External']) && $field['External'])) {
            return $this->listItemsItemFieldExternal($field, $item);
        } else {
            if ('list' == $field['Type'] || 'rlist' == $field['Type'] || 'slist' == $field['Type']) {
                return $this->listItemsItemFieldList($field, $item);
            } else if ('bool' == $field['Type']) {
                return $this->listItemsItemFieldBool($field, $item);
            } else {
                return $this->listItemsItemFieldDefault($field, $item);
            }
        }
    }
    
    /**
     * @param array $fields
     * @param array $item
     * @return string
     */
    protected function listItemsItem(array $fields, array $item)
    {
        $disabled = (array_key_exists('disabled', $item) && $item['disabled']) ? ' class="disabled"' : '';
        $s =    '<tr' . $disabled . '>';
        foreach ($fields as $field) {
            if (!$field['Show']) {
                continue;
            }
            $s .= $this->listItemsItemField($field, $item);
        }
        $s .=   '<td class="funcs">' . $this->getItemFuncsHtml($item, array('open', 'edit', 'disable', 'delconfirm')) . '</td>' . 
                '</tr>';
        return $s;
    }
    
    /**
     * @return string
     */
    public function listItems()
    {
        $fields = $this->model->getFields();
        $items = $this->model->getItems();
        
        $s = '<table class="items">';
        if ($this->model->isCanCreateItems()) {
            $s .= '<caption class="funcs">' . $this->getItemFuncsHtml(array('itemid' => 0), array('add')) . '</caption>';
        }
        if (null === $items || 0 == count($items)) {
            return $s . '<tr><th>Записей не найдено</th></tr></table>';
        }
        $s .= $this->listItemsHeader($fields);
        foreach ($items as $item) {
            $s .= $this->listItemsItem($fields, $item);
        }
        return $s . '</table>';
    }
    
    /**
     * @return string
     * @todo: split on: begin, add, head, items[itemData, itemFuncs], add, end
     */
    public function treeItems()
    {
        $fields = $this->model->getFields();
        $items = $this->model->getItems();
        
        $s = '<table class="items">';
        if ($this->model->isCanCreateItems()) {
            $s .= '<caption class="funcs">' . $this->getItemFuncsHtml(array('itemid' => 0), array('add')) . '</caption>';
        }
        $s .= '<tr>';
        $level = false;
        $levelNames = array('level', 'levelid', 'nesting', 'nestingid');
        $r = '';
        foreach ($fields as $field) {
            if (!$level && in_array($field['Name'], $levelNames)) {
                $level = true;
            }
            if (!$field['Show']) {
                continue;
            }
            $r .= '<th>' . $field['Title'];
            if ($field['Sort']) {
                $r .= $this->fieldSort($field['Name']);
            }
            $r .= '</th>';
        }
        if ($level) {
            $r = '<th>вложенность</th>' . $r;
        }
        $s .= $r . '<th>Управление&nbsp;<a href="' . $this->basePath . '&' . $this->config->paramsNames['action'] . '=sort&' . $this->config->paramsNames['sortField'] . '=" title="Сбросить сортировку">&#9674;</a></th></tr>';
        foreach ($items as $item) {
            $disabled = (array_key_exists('disabled', $item) && $item['disabled']) ? ' class="disabled"' : '';
            $s .=   '<tr' . $disabled . '>';
            if (array_key_exists('level', $item)) {
                $s .= '<td><span class="levelpadding">' . str_pad('', $item['level'] * 7, '&#8594;', STR_PAD_LEFT) . '</span></td>';
            }
            foreach ($fields as $field) {
                if (!$field['Show']) {
                    continue;
                }
                $s .=   '<td class="type-' . $field['Type'] . (isset($field['Class']) ? ' ' . $field['Class'] : '') . '">' . 
                            htmlspecialchars($item[$field['Name']]) . 
                        '</td>';
            }
            $s .=   '<td class="funcs">' . $this->getItemFuncsHtml($item, array('open', 'edit', 'disable', 'delconfirm')) . '</td></tr>';
        }
        return $s . '</table>';
    }
    
    /**
     * @param array $fields
     * @param array $item
     * @return string
     */
    protected function singleItemHeadField(array $field, array $item)
    {
        if ((isset($field['External']) && $field['External'])) {
            $s =    '<div class="itemfield type-' . $field['Type'] . '">' . 
                        '<span class="fieldname">' . $field['Title'] . '</span>' . 
                        '<span class="fieldvalue">' . $this->getExternalFieldContent($field, $item) . '</span>' . 
                    '</div>';
            return $s;
        }
            
        $value = $item[$field['Name']];
        if ('list' == $field['Type']) {
            $fieldItems = $this->model->getFieldItems($field); //$field['Items']
            foreach ($fieldItems as $fieldItem) {
                if ($value == $fieldItem['Value']) {
                    $value = $fieldItem['Title'];
                    break;
                }
            }
        }
        $s =    '<div class="itemfield type-' . $field['Type'] . '">' . 
                    '<span class="fieldname">' . $field['Title'] . '</span>' . 
                    '<span class="fieldvalue">' . htmlspecialchars($value) . '</span>' . 
                '</div>';
        return $s;
    }
    
    /**
     * @param array $fields
     * @param array $item
     * @return string
     */
    protected function singleItemHead(array $fields, array $item)
    {
        $s  =   '<div class="itemhead">' . 
                '<div class="funcs">' . $this->getItemFuncsHtml($item, array('open', 'edit', 'disable', 'delconfirm')) . '</div>';
        foreach ($fields as $field) {
            if (!$field['Show'] || 'mediumtext' == $field['Type'] || 'bigtext' == $field['Type']) {
                continue;
            }
            $s .= $this->singleItemHeadField($field, $item);
        }
        $s .= '</div>';
        return $s;
    }
    
    /**
     * @param array $fields
     * @param array $item
     * @return string
     */
    protected function singleItemBodyField(array $field, array $item)
    {
        $s =    '<div class="itemfield type-' . $field['Type'] . '">' . 
                    '<div class="fieldname">' . $field['Title'] . '</div>' . 
                    '<div class="fieldvalue">' . (isset($field['Raw']) && $field['Raw'] ? $item[$field['Name']] : htmlspecialchars($item[$field['Name']])) . '</div>' . 
                '</div>';
        return $s;
    }
    
    /**
     * @param array $fields
     * @param array $item
     * @return string
     */
    protected function singleItemBody(array $fields, array $item)
    {
        $s = '<div class="itembody">';
        foreach ($fields as $field) {
            if (!$field['Show'] || ('mediumtext' != $field['Type'] && 'bigtext' != $field['Type'])) {
                continue;
            }
            $s .= $this->singleItemBodyField($field, $item);
        }
        $s .= '</div>';
        return $s;
    }
    
    /**
     * @return string
     */
    public function singleItems()
    {
        $fields = $this->model->getFields();
        $items = $this->model->getItems();
        
        $s = '<div class="items">';
        if ($this->model->isCanCreateItems()) {
            $s .= '<div class="funcs">' . $this->getItemFuncsHtml(array('itemid' => 0), array('add')) . '</div>';
        }
        if (null === $items || 0 == count($items)) {
            return $s . '<div class="empty">Записей не найдено</div>';
        }
        foreach ($items as $item) {
            $disabled = (array_key_exists('disabled', $item) && $item['disabled']) ? ' disabled' : '';
            $s .= '<div class="item' . $disabled . '">';
            $s .= $this->singleItemHead($fields, $item);
            $s .= $this->singleItemBody($fields, $item);
            $s .= '</div>';
        }
        return $s . '</div>';
    }
    
    /**
     * Формирование атрибутов управляэщего элемента поля формы.
     * @param array $field <Type,Name,Title,Value,Constraints>
     * @param mixed $value
     * @return string
     */
    protected function getFormFieldAttributes(array $field, $value)
    {
        //replace space between date and time to 'T' for datetime-local format support
        //PHP function `strtotime` returns the same result for 'yyyy-mm-ddThh:mm:dd' and  'yyyy-mm-dd hh:mm:dd'
        switch ($field['Type']) {
            case 'int':
            case 'float':
                $value = ' value="' . $value . '"';
                break;
            case 'datetime':
                $value = ' value="' . str_replace(' ', 'T', ($value)) . '"';
                break;
            case 'bool':
                $value = ' value="1"';
                break;
            case 'list':
            case 'mlist':
            case 'rlist':
            case 'slist':
            case 'mslist':
            case 'mediumtext':
            case 'bigtext':
                $value = '';
                break;
            default:
                $value = ' value="' . htmlspecialchars($value) . '"';
        }
        
        $attributes = ' name="' . $field['Name'] . (('mlist' == $field['Type'] || 'mslist' == $field['Type']) ? '[]' : '') . '" id="' . strtolower($field['Name']) . '"' . $value;
        if (isset($field['Required']) && $field['Required']) {
            $attributes .= ' required';
        }
        if (isset($field['Disabled']) && $field['Disabled']) {
            $attributes .= ' disabled';
        } else if (0 != $this->params->itemId && isset($field['Unchange']) && $field['Unchange']) {
            $attributes .= ' disabled';
        }
        if (isset($field['Autofocus']) && $field['Autofocus']) {
            $attributes .= ' autofocus';
        }
        return $attributes;
    }
    
    /**
     * Формирование управляющего элемента поля типа целое число.
     * @param array $field <Type,Name,Title,Value,Constraints>
     * @param mixed $value
     * @return string
     */
    protected function formFieldIntElement(array $field, $value)
    {
        return '<input type="number" step="1" maxlength="9" size="12"' . $this->getFormFieldAttributes($field, $value) . '>';
    }
    
    /**
     * Формирование управляющего элемента поля типа число с точкой.
     * @param array $field <Type,Name,Title,Value,Constraints>
     * @param mixed $value
     * @return string
     */
    protected function formFieldFloatElement(array $field, $value)
    {
        return '<input type="number" step="0.01" maxlength="9" size="12"' . $this->getFormFieldAttributes($field, $value) . '>';
    }
    
    /**
     * Формирование управляющего элемента поля типа датавремя.
     * @param array $field <Type,Name,Title,Value,Constraints>
     * @param mixed $value
     * @return string
     */
    protected function formFieldDatetimeElement(array $field, $value)
    {
        //replace space between date and time to 'T' for datetime-local format support
        //PHP function `strtotime` returns the same result for 'yyyy-mm-ddThh:mm:dd' and  'yyyy-mm-dd hh:mm:dd'
        return '<input type="datetime-local" step="1" maxlength="19" size="20"' . $this->getFormFieldAttributes($field, $value) . '>';
    }
    
    /**
     * Формирование управляющего элемента поля типа список.
     * @param array $field <Type,Name,Title,Value,Constraints>
     * @param mixed $value
     * @return string
     */
    protected function formFieldListElement(array $field, $value)
    {
        $s = '<select' . $this->getFormFieldAttributes($field, $value) . ('mlist' == $field['Type'] ? ' multiple size="10"' : '') . '>';
        $items = $this->model->getFieldItems($field); //$field['Items']
        foreach ($items as $item) {
            if (is_array($value)) {
                $selected = in_array($item['Value'], $value);
            } else if (is_string($value) && $this->tools->isStringOfIntegers($value)) {
                $selected = in_array($item['Value'], $this->tools->getArrayOfIntegersFromString($value));
            } else {
                $selected = $value == $item['Value'];
            }
            if ($selected) {
                $s .= '<option value="' . htmlspecialchars($item['Value']) . '" selected>' . htmlspecialchars($item['Title']) . '</option>';
            } else {
                $s .= '<option value="' . htmlspecialchars($item['Value']) . '">' . htmlspecialchars($item['Title']) . '</option>';
            }
        }
        $s .= '</select>';
        return $s;
    }
    
    /**
     * Формирование управляющего элемента поля типа список (radiobuttons).
     * @param array $field <Type,Name,Title,Value,Constraints>
     * @param mixed $value
     * @return string
     */
    protected function formFieldRadioListElement(array $field, $value)
    {
        $s = '';
        $items = $this->model->getFieldItems($field); //$field['Items']
        foreach ($items as $item) {
            if (is_array($value)) {
                $selected = in_array($item['Value'], $value);
            } else if (is_string($value) && $this->tools->isStringOfIntegers($value)) {
                $selected = in_array($item['Value'], $this->tools->getArrayOfIntegersFromString($value));
            } else {
                $selected = $value == $item['Value'];
            }
            if ($selected) {
                $selected = ' checked';
            } else {
                $selected = '';
            }
            $s .=   '<div>' . 
                        '<label><input type="radio"' . $this->getFormFieldAttributes($field, $value) . ' value="' . htmlspecialchars($item['Value']) . '"' . $selected . '>' . htmlspecialchars($item['Title']) . '</label>' . 
                        (isset($item['Description']) ? '<div>' . htmlspecialchars($item['Description']) . '</div>' : '') . 
                    '</div>';
        }
        return $s;
    }
    
    /**
     * Формирование управляющего элемента поля типа список.
     * @param array $field <Type,Name,Title,Value,Constraints>
     * @param mixed $value
     * @return string
     */
    protected function formFieldStringListElement(array $field, $value)
    {
        $s = '<select' . $this->getFormFieldAttributes($field, $value) . ('mslist' == $field['Type'] ? ' multiple size="10"' : '') . '>';
        $items = $this->model->getFieldItems($field); //$field['Items']
        foreach ($items as $item) {
            if (is_array($value)) {
                $selected = in_array($item['Value'], $value);
            } else if (is_string($value) && false !== strpos($value, ',')) {
                $selected = in_array($item['Value'], explode(',', $value));
            } else {
                $selected = $value == $item['Value'];
            }
            if ($selected) {
                $s .= '<option value="' . htmlspecialchars($item['Value']) . '" selected>' . htmlspecialchars($item['Title']) . '</option>';
            } else {
                $s .= '<option value="' . htmlspecialchars($item['Value']) . '">' . htmlspecialchars($item['Title']) . '</option>';
            }
        }
        $s .= '</select>';
        return $s;
    }
    
    /**
     * Формирование управляющего элемента поля типа доплняемый список.
     * @param array $field <Type,Name,Title,Value,Constraints>
     * @param mixed $value
     * @return string
     */
    protected function formFieldComboElement(array $field, $value)
    {
        $options = '';
        $items = $this->model->getFieldItems($field); //$field['Items']
        foreach ($items as $item) {
            $options .= '<option value="' . htmlspecialchars($item['Value']) . '">' . htmlspecialchars($item['Title']) . '</option>';
        }
        return  '<input type="text" list="' . $field['Name'] . 'DataList"' . 
                    ' maxlength="255" size="40"' . 
                    $this->getFormFieldAttributes($field, $value) . 
                '>' . 
                '<datalist id="' . $field['Name'] . 'DataList">' . 
                    $options . 
                '</datalist>';
    }
    
    /**
     * Формирование управляющего элемента поля типа да-нет (вкл.-выкл.).
     * @param array $field <Type,Name,Title,Value,Constraints>
     * @param mixed $value
     * @return string
     */
    protected function formFieldBoolElement(array $field, $value)
    {
        return '<input type="checkbox" name="' . $field['Name'] . '" id="' . strtolower($field['Name']) . '" value="1"' . ($value ? ' checked' : '') . '>';
    }
    
    /**
     * Формирование управляющего элемента поля типа изображение.
     * @param array $field <Type,Name,Title,Value,Constraints>
     * @param mixed $value
     * @return string
     */
    protected function formFieldImageElement(array $field, $value)
    {
        $s =    '<input type="text" maxlength="255" size="40"' . $this->getFormFieldAttributes($field, $value) . '>' . 
                '<input type="button" value="i"' . 
                    ' onclick="window.open(\'extensions/ajaxfilemanager/ajaxfilemanager.php?editor=ufocms_image&elementId=' . strtolower($field['Name']) . '\', \'FM\', \'top=\' + (screen.availHeight / 2 - 235) + \',left=\' + (screen.availWidth / 2 - 380) + \',width=760,height=470,scrollbars=yes,resizable=yes,border=no,status=no\')"' . 
                    ' style="width: 20px;" title="Указать картинку">' . 
                '<input type="button" value="X"' . 
                    ' onclick="this.parentNode.firstChild.value = \'\'" style="width: 20px;"' . 
                    ' title="Убрать картинку">';
        //Image adjuster
        $s .=   ' | ' . 
                '<input type="button"' . 
                ' name="' . strtolower($field['Name']) . 'adjust" id="' . strtolower($field['Name']) . 'adjust"' . 
                ' value="a" style="width: 20px;" title="Изменить картинку"' . 
                ' onclick=""' . 
                '>' . 
                '<script type="text/javascript">addImageAdjuster("' . strtolower($field['Name']) . '")</script>';
        return $s;
    }
    
    /**
     * Формирование управляющего элемента поля типа произвольный файл.
     * @param array $field <Type,Name,Title,Value,Constraints>
     * @param mixed $value
     * @return string
     */
    protected function formFieldFileElement(array $field, $value)
    {
        return  '<input type="text" maxlength="255" size="40"' . $this->getFormFieldAttributes($field, $value) . '>' . 
                '<input type="button" value="i"' . 
                    ' onclick="window.open(\'extensions/ajaxfilemanager/ajaxfilemanager.php?editor=ufocms_file&elementId=' . strtolower($field['Name']) . '\', \'FM\', \'top=\' + (screen.availHeight / 2 - 235) + \',left=\' + (screen.availWidth / 2 - 380) + \',width=760,height=470,scrollbars=yes,resizable=yes,border=no,status=no\')"' . 
                    ' style="width: 20px;" title="Указать файл">';
    }
    
    /**
     * Формирование управляющего элемента поля типа папка.
     * @param array $field <Type,Name,Title,Value,Constraints>
     * @param mixed $value
     * @return string
     */
    protected function formFieldFolderElement(array $field, $value)
    {
        return  '<input type="text" maxlength="255" size="40"' . $this->getFormFieldAttributes($field, $value) . '>' . 
                '<input type="button" value="i"' . 
                    ' onclick="window.open(\'extensions/ajaxfilemanager/ajaxfilemanager.php?editor=ufocms_folder&elementId=' . strtolower($field['Name']) . '\', \'FM\', \'top=\' + (screen.availHeight / 2 - 235) + \',left=\' + (screen.availWidth / 2 - 380) + \',width=760,height=470,scrollbars=yes,resizable=yes,border=no,status=no\')"' . 
                    ' style="width: 20px;" title="Указать папку">';
    }
    
    /**
     * Формирование управляющего элемента поля типа текст.
     * @param array $field <Type,Name,Title,Value,Constraints>
     * @param mixed $value
     * @return string
     */
    protected function formFieldTextElement(array $field, $value)
    {
        return '<input type="text" maxlength="255" size="50"' . $this->getFormFieldAttributes($field, $value) . '>';
    }
    
    /**
     * Формирование управляющего элемента поля типа список.
     * @param array $field <Type,Name,Title,Value,Constraints>
     * @param mixed $value
     * @return string
     */
    protected function formFieldMediumtextElement(array $field, $value)
    {
        return '<textarea cols="50" rows="5"' . $this->getFormFieldAttributes($field, $value) . '>' . htmlspecialchars($value) . '</textarea>';
    }
    
    /**
     * Формирование управляющего элемента поля типа список.
     * @param array $field <Type,Name,Title,Value,Constraints>
     * @param mixed $value
     * @return string
     */
    protected function formFieldBigtextElement(array $field, $value)
    {
        return '<textarea cols="50" rows="10"' . $this->getFormFieldAttributes($field, $value) . '>' . htmlspecialchars($value) . '</textarea>';
    }
    
    /**
     * Формирование управляющего элемента поля формы.
     * @param array $field <Type,Name,Title,Value,Constraints>
     * @param mixed $value
     * @return string
     */
    protected function formFieldElement(array $field, $value)
    {
        $s = '';
        switch ($field['Type']) {
            case 'int':
                $s = $this->formFieldIntElement($field, $value);
                break;
            case 'float':
                $s = $this->formFieldFloatElement($field, $value);
                break;
            case 'datetime':
                $s = $this->formFieldDatetimeElement($field, $value);
                break;
            case 'list':
            case 'mlist':
                $s = $this->formFieldListElement($field, $value);
                break;
            case 'rlist':
                $s = $this->formFieldRadioListElement($field, $value);
                break;
            case 'slist':
            case 'mslist':
                $s = $this->formFieldStringListElement($field, $value);
                break;
            case 'combo':
                $s = $this->formFieldComboElement($field, $value);
                break;
            case 'text':
                $s = $this->formFieldTextElement($field, $value);
                break;
            case 'mediumtext':
                $s = $this->formFieldMediumtextElement($field, $value);
                break;
            case 'bigtext':
                $s = $this->formFieldBigtextElement($field, $value);
                break;
            case 'bool':
                $s = $this->formFieldBoolElement($field, $value);
                break;
            case 'image':
                $s = $this->formFieldImageElement($field, $value);;
                break;
            case 'file':
                $s = $this->formFieldFileElement($field, $value);;
                break;
            default:
                $method = 'formField' . ucfirst($field['Type']) . 'Element';
                if (method_exists($this, $method)) {
                    return $this->$method($field, $value);
                } else {
                    $s = 'Not implemented yet';
                }
        }
        return $s;
    }
    
    /**
     * Формирование поля формы, содержащее название (описание) и управляющий элемент.
     * @param array $field <Type,Name,Title,Value,Constraints>
     * @param mixed $value
     * @return string
     */
    protected function formField(array $field, $value)
    {
        $method = 'formField' . ucfirst($field['Type']);
        if (method_exists($this, $method)) {
            return $this->$method($field, $value);
        }
        
        $description = '';
        if (isset($field['Info'])) {
            $description = '<span title="' . htmlspecialchars($field['Info']) . '">i</span>';
        }
        
        $required = '';
        if (isset($field['Required']) && $field['Required']) {
            $required = '<sup>*</sup>';
        }
        
        return  '<tr class="type-' . $field['Type'] . '">' . 
                '<td><label>' . $field['Title'] . $description . $required . '</label></td>' . 
                '<td>' . $this->formFieldElement($field, $value) . '</td>' . 
                '</tr>';
    }
    
    /**
     * Получение установленных значений для внешнего поля.
     * @param array $field <Type,Name,Title,Value,Constraints>
     * @return mixed
     */
    protected function getItemExternalFieldValue($field)
    {
        return $this->model->getItemExternalFieldValue($field);
    }
    
    /**
     * Формирование начала формы.
     * @param array $attributes
     * @return string
     */
    protected function formBegin(array $attributes)
    {
        $s =    $this->formElement($attributes) . 
                '<table class="form">';
        return $s;
    }
    
    /**
     * Формирование тэга формы.
     * @param array $attributes
     * @return string
     */
    protected function formElement(array $attributes)
    {
        $s = '<form';
        foreach ($attributes as $name => $value) {
            $s .= ' ' . $name . '="' . $value . '"';
        }
        return $s . '>';
    }
    
    /**
     * Формирует набор атрибутов тэга формы.
     */
    protected function formElementAttributes($handler)
    {
        return array(
            'action' => $handler, 
            'method' => 'post'
        );
    }
    
    /**
     * Формирование обработчика формы.
     * @param array $item = null
     * @return string
     */
    protected function formHandler(array $item = null)
    {
        return  $this->basePath . 
                '&' . $this->config->paramsNames['action'] . '=update' . 
                (null !== $item ? '&' . $this->config->paramsNames['itemId'] . '=' . $item['itemid'] : '');
    }
    
    /**
     * Формирование списка полей связанных данных.
     * @param array $field
     * @param array $item
     * @return string
     */
    protected function formSubform(array $field, array $item)
    {
        if (isset($field['Schema'])) {
            //schema contains only structure, not data
            $this->model = $this->model->getFieldSchema($field);
            $fields = $this->model->getFields();
            if (0 == count($fields)) {
                return '';
            }
            //data gets from $item and assign to structure fields
            $values = json_decode($item[$field['Name']], true);
            if (null === $values || count($values) != count($fields)) {
                $values = array('itemid' => 0);
                foreach ($fields as $f) {
                    $values[$f['Name']] = $f['Value'];
                }
            }
        } else if (isset($field['Model'])) {
            //model initialized by $field['Model'] method with data from $item[$field['Name']]
            $this->model = $this->model->getFieldModel($field, $item[$field['Name']]);
            $fields = $this->model->getFields();
            $values = $this->model->getItem();
        } else {
            $fields = array();
            $values = array();
        }
        $s = '<tr><th colspan="2">' . htmlspecialchars($field['Title']) . '</th></tr>';
        $s .= $this->formFields($fields, $values);
        return $s;
    }
    
    /**
     * Формирование полей формы.
     * @param array $fields
     * @param array $item
     * @return string
     */
    protected function formFields(array $fields, array $item)
    {
        $s = '';
        foreach ($fields as $field) {
            if (!$field['Edit']) {
                continue;
            }
            if ('subform' == $field['Type']) {
                $s .= $this->formSubform($field, $item);
            } else if (array_key_exists($field['Name'], $item)) { //some old databases may not contain all declared fields
                $s .= $this->formField($field, $item[$field['Name']]);
            } else if (isset($field['External']) && $field['External']) {
                $s .= $this->formField($field, $this->getItemExternalFieldValue($field));
            }
        }
        return $s;
    }
    
    /**
     * Формирование окончания формы.
     * @param array $submitAttributes = null
     * @param array $cancelAttributes = null
     * @return string
     */
    protected function formEnd(array $submitAttributes = null, array $cancelAttributes = null)
    {
        $s =    '<tr><td colspan="2" align="center">' . 
                (null !== $cancelAttributes ? $this->formCancelElement($cancelAttributes) . '&nbsp;' : '') . 
                (null !== $submitAttributes ? $this->formSubmitElement($submitAttributes) . '&nbsp;' : '') . 
                '</td></tr>' . 
                '</table></form>';
        return $s;
    }
    
    /**
     * Формирование тэга отправки формы.
     * @param array $attributes
     * @return string
     */
    protected function formSubmitElement(array $attributes)
    {
        $s = '<input type="submit"';
        foreach ($attributes as $name => $value) {
            $s .= ' ' . $name . '="' . $value . '"';
        }
        return $s . '>';
    }
    
    /**
     * Формирование тэга отправки формы.
     * @param array $attributes
     * @return string
     */
    protected function formCancelElement(array $attributes)
    {
        $s = '<input type="button"';
        foreach ($attributes as $name => $value) {
            $s .= ' ' . $name . '="' . $value . '"';
        }
        return $s . ' onclick="history.back()">';
    }
    
    /**
     * Формирует набор атрибутов тэга кнопки отправки формы.
     */
    protected function formSubmitElementAttributes()
    {
        return array(
            'value' => 'Сохранить'
        );
    }
    
    /**
     * Формирует набор атрибутов тэга кнопки отмены формы.
     */
    protected function formCancelElementAttributes()
    {
        return array(
            'value' => 'Назад'
        );
    }
    
    /**
     * Формирование формы.
     * @return string
     */
    public function form()
    {
        $roles = $this->core->getRoles();
        $userId = $this->core->getUsers()->getCurrent()['Id'];
        $module = (0 != $this->module['ModuleId'] ? (int) $this->module['ModuleId'] : (string) $this->module['Module']);
        //TODO 'edit'
        $formDisabled = true;
        if ($roles->rolesPermittedAction($userId, $module, 'edit')) {
            $formDisabled = false;
        }
        
        $fields = $this->model->getFields();
        $item = $this->model->getItem();
        if (!isset($item['itemid'])) {
            $item['itemid'] = 0;
        }
        
        if (!$formDisabled) {
            $s = $this->formBegin($this->formElementAttributes($this->formHandler($item)));
        } else {
            $s = $this->formBegin([]);
        }
        
        $s .= $this->formFields($fields, $item);
        
        if (!$formDisabled) {
            $s .= $this->formEnd($this->formSubmitElementAttributes(), $this->formCancelElementAttributes());
        } else {
            $s .= $this->formEnd(null, $this->formCancelElementAttributes());
        }
        
        return $s;
    }
    
    /**
     * @return string
     */
    public function frameMainHeader()
    {
        $s = '<h1>' . $this->section['title'];
        $this->setMainTabs();
        $s .= $this->getMainTabs();
        $s .='</h1>';
        return $s;
    }
    
    /**
     * @return string
     */
    protected function getMainTabs()
    {
        return implode('', array_values($this->mainTabs));
    }
    
    /**
     * @param string $tabId
     * @param string $tabHtml
     * @param string $afterTabId = null
     */
    protected function appendMainTab($tabId, $tabHtml, $afterTabId = null)
    {
        if (is_null($afterTabId)) {
            $this->mainTabs[$tabId] = $tabHtml;
        } else if ('' == $afterTabId) {
            if (array_key_exists($tabId, $this->mainTabs)) {
                unset($this->mainTabs[$tabId]); //remove tab this the same id if exists
            }
            $this->mainTabs = array_merge(array($tabId => $tabHtml), $this->mainTabs);
        } else {
            if (array_key_exists($tabId, $this->mainTabs)) {
                unset($this->mainTabs[$tabId]); //remove tab this the same id if exists
            }
            $tabs = array();
            foreach ($this->mainTabs as $id => $html) {
                $tabs[$id] = $html;
                if ($id == $afterTabId) {
                    $tabs[$tabId] = $tabHtml;
                }
            }
            $this->mainTabs = $tabs;
        }
    }
    
    /**
     * Generating main fraim header tabs
     * @see appendMainTab
     */
    protected function setMainTabs()
    {
        if (is_null($this->params->coreModule) || !is_null($this->params->sectionId)) {
            $tab = '<a href="?' . $this->config->paramsNames['sectionId'] . '=' . $this->params->sectionId . '" title="содержимое раздела"' . (is_null($this->params->coreModule) ? ' class="current"' : '') . '>содержимое</a>';
            $this->appendMainTab('Items', $tab);
            $tab = '<a href="?' . $this->config->paramsNames['sectionId'] . '=' . $this->params->sectionId . '&' . $this->config->paramsNames['coreModule'] . '=insertions" title="вставки в раздел"' . ('insertions' == $this->params->coreModule ? ' class="current"' : '') . '>вставки</a>';
            $this->appendMainTab('Insertions', $tab);
            if (0 != $this->params->sectionId) {
                $tab = '<a href="?' . $this->config->paramsNames['coreModule'] . '=sections&action=edit&' . $this->config->paramsNames['itemId'] . '=' . $this->params->sectionId . '" title="свойства раздела"' . ('sections' == $this->params->coreModule ? ' class="current"' : '') . '>раздел</a>';
                $this->appendMainTab('Sections', $tab);
            }
            $tab = '<a href="' . $this->section['path'] . '" target="_blank" title="открыть страницу сайта">посмотреть</a>';
            $this->appendMainTab('SitePage', $tab);
        }
    }
    
    /**
     * Generating master (if master exists) header.
     * @return string
     */
    public function masterHeader()
    {
        $title = $this->getMasterTitle();
        if ('' == $title) {
            return '';
        }
        return '<h2>' . $title . '</h2>';
    }
    
    /**
     * Extract title value from master object.
     * @return string
     */
    protected function getMasterTitle()
    {
        //TODO: moveto config
        $arr = array('Title', 'title', 'Caption', 'caption', 'Indic', 'indic', 'Name', 'name');
        
        if (null === $this->model) {
            return '';
        }
        $master = $this->model->getMaster();
        if (null === $master) {
            return '';
        }
        $item = $master->getItem();
        if (null === $item) {
            return '';
        }
        
        foreach ($arr as $a) {
            if (isset($item[$a])) {
                return $item[$a];
            }
        }
        return '';
    }
    
    /**
     * @Common HEAD title.
     * @return string
     */
    public function headTitle()
    {
        return $this->section['title'];
    }
    
    /**
     * Common HEAD code.
     * @return string
     */
    public function headCode()
    {
        $s = '';
        
        //WYSIWYG
        if ((false !== stripos($this->layout, 'form') && !isset($_GET['code'])) || isset($_GET['wysiwyg'])) {
            $s .=   '<script type="text/javascript" src="extensions/tiny_mce/tiny_mce.js"></script>' . PHP_EOL . 
                    '<script type="text/javascript" src="extensions/tiny_mce/tiny_mce_init.php"></script>' . PHP_EOL;
        }
        
        //Image adjuster
        if (false !== stripos($this->layout, 'form')) {
$s .= <<<'EOD'
<script type="text/javascript" src="extensions/imageadjust/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="extensions/imageadjust/jquery.imgareaselect.min.js"></script>
<link type="text/css" rel="stylesheet" href="extensions/imageadjust/imgareaselect-animated.css">
<script type="text/javascript" src="extensions/imageadjust/imageadjuster.js"></script>
<script type="text/javascript">
var imageAdjusters = [];
function addImageAdjuster(id)
{
    imageAdjusters[id] = new ImageAdjuster("extensions/imageadjust/", id);
    imageAdjusters[id].drawUI();
    var elm = document.getElementById(id + "adjust");
    elm.onclick = function() {
        var wrap = document.getElementById(id + "adjustwrap");
        if ("none" == wrap.style.display) {
            imageAdjusters[id].init();
            wrap.style.display = "";
        } else {
            imageAdjusters[id].clear();
            wrap.style.display = "none";
        }
    };
}
</script>
EOD;
            }
        
        return $s;
    }
    
    /**
     * Common HTTP headers.
     */
    public function headers()
    {
        
    }
}
