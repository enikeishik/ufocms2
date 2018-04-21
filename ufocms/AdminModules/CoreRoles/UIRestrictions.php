<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\CoreRoles;

/**
 * Module level UI
 */
class UIRestrictions extends UI
{
    /**
     * @see parent
     */
    protected function init()
    {
        $this->section = array('path' => '', 'title' => 'Роли управления \ ограничения роли «' . $this->model->getRole()['Title'] . '»');
    }
    
    /**
     * @see parent
     */
    public function frameMainHeader()
    {
        $s =    '<h1><a href="?' . $this->config->paramsNames['coreModule'] . '=' . $this->params->coreModule . '">' . 
                'Роли управления</a> <span>ограничения роли «' . $this->model->getRole()['Title'] . '»</span></h1>';
        return $s;
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
