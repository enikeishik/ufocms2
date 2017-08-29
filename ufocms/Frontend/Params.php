<?php
/**
 * @copyright
 */

namespace Ufocms\Frontend;

/**
 * Structure containing application parameters defined by user
 */
class Params
{
    /**
     * Полученный путь
     * @var string
     */
    public $pathRaw = null;
    
    /**
     * Системный путь
     * @var string
     */
    public $systemPath = null;
    
    /**
     * Путь раздела
     * @var string
     */
    public $sectionPath = null;
    
    /**
     * Идентификатор раздела
     * @var string
     */
    public $sectionId = null;
    
    /**
     * Параметры раздела, остаток пути после нахождения пути раздела
     * @var array
     */
    public $sectionParams = null;
    
    /**
     * Имя модуля
     * @var string
     */
    public $moduleName = null;
    
    /**
     * Путь элемента раздела
     * @var string
     */
    public $itemPath = null;
    
    /**
     * Идентификатор элемента раздела
     * @var int
     */
    public $itemId = null;
    
    /**
     * Идентификатор действия
     * @var int
     */
    public $actionId = null;
    
    /**
     * Имя метода действия
     * @var string
     */
    public $action = null;
    
    /**
     * Номер страницы постраничного вывода
     * @var int
     */
    public $page = null;
    
    /**
     * Размер страницы постраничного вывода
     * @var int
     */
    public $pageSize = null;
    
    /**
     * Имя поля фильтрации
     * @var string
     */
    public $filterName = null;
    
    /**
     * Значение поля фильтрации
     * @var string
     */
    public $filterValue = null;
    
    /**
     * Имя поля сортировки
     * @var string
     */
    public $sortField = null;
    
    /**
     * Направление сортировки
     * @var string
     */
    public $sortDirection = null;
}
