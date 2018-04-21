<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\CoreRoles;

/**
 * Module level UI
 */
class UIPermissions extends UI
{
    /**
     * @see parent
     */
    protected function init()
    {
        $this->section = array('path' => '', 'title' => 'Роли управления \ разрешения роли «' . $this->model->getRole()['Title'] . '»');
    }
    
    /**
     * @see parent
     */
    public function frameMainHeader()
    {
        $s =    '<h1><a href="?' . $this->config->paramsNames['coreModule'] . '=' . $this->params->coreModule . '">' . 
                'Роли управления</a> <span>разрешения роли «' . $this->model->getRole()['Title'] . '»</span></h1>';
        return $s;
    }
    
    /**
     * @see parent
     */
    protected function listItemsItemFieldDefault(array $field, array $item)
    {
        $item[$field['Name']] = str_replace([':', ','], [': ', ', '], $item[$field['Name']]);
        return parent::listItemsItemFieldDefault($field, $item);
    }
    
    /**
     * @see parent
     */
    protected function setItemFuncs(array $item, array $funcs)
    {
        if ($this->model->getRole()['IsSystem']) {
            parent::setItemFuncs(array(), array());
        } else {
            parent::setItemFuncs($item, $funcs);
        }
    }
}
