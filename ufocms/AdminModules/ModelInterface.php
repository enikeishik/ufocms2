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
interface ModelInterface extends SchemaInterface, StatelessModelInterface
{
    /**
     * Get model module.
     * @return array
     */
    public function getModule();
    
    /**
     * Get model of master (when this model is slave).
     * @return Model|null
     */
    public function getMaster();
    
    /**
     * @return string
     */
    public function getItemsTable();
    
    /**
     * @return string
     */
    public function getItemIdField();
    
    /**
     * @return string
     */
    public function getItemDisabledField();
    
    /**
     * Get field model (when field contains link to structured external data) by demand.
     * @param string|array $field
     * @param mixed $value = null
     * @return object|null
     */
    public function getFieldModel($field, $value = null);
    
    /**
     * Get field schema (when field value contains structured data itself, as JSON for example, and schema is external) by demand.
     * @param string|array $field
     * @return array|null
     */
    public function getFieldSchema($field);
    
    /**
     * Get field items (when field value is item of list) by demand.
     * @param string|array $field
     * @return array|null
     */
    public function getFieldItems($field);
    
    /**
     * Get value for external field (field contains data in another table).
     * @param string|array $field
     * @return mixed
     */
    public function getItemExternalFieldValue($field);
    
    /**
     * @return array
     */
    public function getItems();
    
    /**
     * @return int
     */
    public function getItemsCount();
    
    /**
     * @return array
     */
    public function getItem();
    
    /**
     * Get sections with the same muduleid as current section.
     * @param bool $nc = false
     * @return array
     */
    public function getSections($nc = false);
    
    /**
     * Update item data.
     * @return bool
     */
    public function update();
    
    /**
     * Delete item
     * @return bool
     */
    public function delete();
    
    /**
     * Disable item (set flag disabled)
     * @return bool
     */
    public function disable();
    
    /**
     * Enable item (unset flag disabled)
     * @return bool
     */
    public function enable();
    
    /**
     * @return mixed
     */
    public function getResult();
    
    /**
     * @return bool
     */
    public function isCanCreateItems();
    
    /**
     * @return bool
     */
    public function isCanUpdateItems();
    
    /**
     * @return bool
     */
    public function isCanDeleteItems();
    
    /**
     * @return int|null
     */
    public function getLastInsertedId();
}
