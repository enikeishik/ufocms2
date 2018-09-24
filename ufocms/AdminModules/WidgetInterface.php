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
 * Module level view base class interface
 */
interface WidgetInterface extends DIObjectInterface
{
    
    /**
     * Get field items by demand
     * @param string|array $field
     * @return array|null
     */
    public function getFieldItems($field);
    
    /**
     * Возвращает флаг использования контентного поля в БД.
     * @return bool
     * @todo change to isUseContent
     */
    public function getUseContent();
    
    /**
     * Возвращает флаг использования единственного источника (а не нескольких).
     * @return bool
     * @todo change to isSingleSource
     */
    public function getSingleSource();
    
    /**
     * Возвращает флаг зависимости параметров от источника.
     * @return bool
     * @todo change to isSourceDepends
     */
    public function getSourceDepends();
}
