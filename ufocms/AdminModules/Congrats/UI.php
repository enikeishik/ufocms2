<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\Congrats;

/**
 * Module level UI
 */
class UI extends \Ufocms\AdminModules\UI
{
    protected function setMainTabs()
    {
        parent::setMainTabs();
        $tab = '<a href="?' . $this->config->paramsNames['sectionId'] . '=' . $this->params->sectionId . '&' . $this->config->paramsNames['subModule'] . '=settings"' . ('settings' == $this->params->subModule ? ' class="current"' : '') . ' title="Настройки модуля">настройки</a>';
        $this->appendMainTab('Settings', $tab, 'Items');
        $tab = '<a href="?' . $this->config->paramsNames['sectionId'] . '=' . $this->params->sectionId . '"' . (is_null($this->params->subModule) ? ' class="current"' : '') . ' title="содержимое раздела">содержимое</a>';
        $this->appendMainTab('Items', $tab);
    }
}
