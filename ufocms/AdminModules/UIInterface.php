<?php
/**
 * UFOCMS v2 Content Management System
 * 
 * @copyright   Copyright (C) 2005 - 2017 Enikeishik <enikeishik@gmail.com>. All rights reserved.
 * @author      Enikeishik <enikeishik@gmail.com>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Ufocms\AdminModules;

use Ufocms\Frontend\DIObjectInterface;

/**
 * Module level model base class interface
 */
interface UIInterface extends DIObjectInterface
{
    /**
     * Формирование постраничной навигации.
     * @return string
     * @todo: define pagesShow in config
     */
    public function pagination();
    
    /**
     * Формирование списка элементов для фильтрации данных.
     * @return string
     */
    public function filters();
    
    /**
     * Формирование линейного табличного списка элементов.
     * @return string
     */
    public function listItems();
    
    /**
     * Формирование древовидного списка элементов.
     * @return string
     */
    public function treeItems();
    
    /**
     * Формирование линейного расширенного списка элементов.
     * @return string
     */
    public function singleItems();
    
    /**
     * Формирование формы редактирования элемента.
     * @return string
     */
    public function form();
    
    /**
     * Формирование отображаемого на странице заголовка.
     * @return string
     */
    public function frameMainHeader();
    
    /**
     * Generating master (if master exists) header to display on page.
     * @return string
     */
    public function masterHeader();
    
    /**
     * @Common HEAD title.
     * @return string
     */
    public function headTitle();
    
    /**
     * Common HEAD code.
     * @return string
     */
    public function headCode();
    
    /**
     * Common HTTP headers.
     */
    public function headers();
}
