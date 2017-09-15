<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\CoreUsers;

/**
 * Module level UI
 */
class UISettings extends UI
{
    /**
     * @see parent
     */
    protected function setMainTabs()
    {
        parent::setMainTabs();
        $tab = '<a href="?' . $this->config->paramsNames['coreModule'] . '=' . $this->params->coreModule . '" title="пользователи">пользователи</a>';
        $this->appendMainTab('Items', $tab);
        $tab = '<a href="?' . $this->config->paramsNames['coreModule'] . '=' . $this->params->coreModule . '&' . $this->config->paramsNames['subModule'] . '=groups" title="группы">группы</a>';
        $this->appendMainTab('Groups', $tab);
        $tab = '<a href="?' . $this->config->paramsNames['coreModule'] . '=' . $this->params->coreModule . '&' . $this->config->paramsNames['subModule'] . '=settings" class="current" title="настройки">настройки</a>';
        $this->appendMainTab('Settings', $tab);
    }
}
