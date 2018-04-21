<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\CoreUsers;

/**
 * Module level UI
 */
class UI extends \Ufocms\AdminModules\UI
{
    /**
     * @see parent
     */
    protected function init()
    {
        $this->section = array('path' => '', 'title' => 'Зарегистрированные пользователи сайта');
    }
    
    /**
     * @see parent
     */
    protected function getExternalFieldContent(array $field, array $item)
    {
        if ('Groups' == $field['Name']) {
            $items = $this->model->getUserGroups($item['Id']);
        } else if ('Roles' == $field['Name']) {
            $items = $this->model->getUserRoles($item['Id']);
        } else {
            return '';
        }
        if (0 < count($items)) {
            return implode(', ', $items);
        }
        return '-';
    }
    
    /**
     * @see parent
     */
    protected function singleItemHeadField(array $field, array $item)
    {
        if ('Password' == $field['Name']) {
            $s =    '<div class="itemfield type-' . $field['Type'] . '">' . 
                        '<span class="fieldname">' . $field['Title'] . '</span>' . 
                        '<span class="fieldvalue" onmouseover="this.innerHTML=\'' . htmlspecialchars($item[$field['Name']]) . '\'" onmouseout="this.innerHTML=\'******\'" style="width: 100px;">******</span>' . 
                    '</div>';
            return $s;
        }
        return parent::singleItemHeadField($field, $item);
    }
    
    /**
     * @see parent
     */
    public function singleItems()
    {
        //
        return parent::singleItems();
    }
    
    /**
     * @see parent
     */
    protected function setMainTabs()
    {
        parent::setMainTabs();
        $tab = '<a href="?' . $this->config->paramsNames['coreModule'] . '=' . $this->params->coreModule . '" class="current" title="пользователи">пользователи</a>';
        $this->appendMainTab('Items', $tab);
        $tab = '<a href="?' . $this->config->paramsNames['coreModule'] . '=' . $this->params->coreModule . '&' . $this->config->paramsNames['subModule'] . '=groups" title="группы">группы</a>';
        $this->appendMainTab('Groups', $tab);
        $tab = '<a href="?' . $this->config->paramsNames['coreModule'] . '=' . $this->params->coreModule . '&' . $this->config->paramsNames['subModule'] . '=settings" title="настройки">настройки</a>';
        $this->appendMainTab('Settings', $tab);
    }
}
