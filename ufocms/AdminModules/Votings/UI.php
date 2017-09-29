<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\Votings;

/**
 * Module level UI
 */
class UI extends \Ufocms\AdminModules\UI
{
    /**
     * @see parent
     */
    protected function setItemFuncs(array $item, array $funcs)
    {
        parent::setItemFuncs($item, $funcs);
        if (__CLASS__ == get_class($this) && isset($item['Id'])) {
            $s = '<div><a href="' . $this->basePath . '&' . $this->config->paramsNames['subModule'] . '=answers&votingid=' . $item['Id'] . '" title="Редактировать ответы" style="padding: 2px 15px;">Ответы</a></div><br>';
            $this->appendItemFunc('answers', $s, '');
        }
    }
    
    /**
     * @see parent
     */
    protected function setMainTabs()
    {
        parent::setMainTabs();
        $tab = '<a href="?' . $this->config->paramsNames['sectionId'] . '=' . $this->params->sectionId . '&' . $this->config->paramsNames['subModule'] . '=settings"' . ('settings' == $this->params->subModule ? ' class="current"' : '') . ' title="Настройки модуля">настройки</a>';
        $this->appendMainTab('Settings', $tab, 'Items');
        $tab = '<a href="?' . $this->config->paramsNames['sectionId'] . '=' . $this->params->sectionId . '"' . (is_null($this->params->subModule) ? ' class="current"' : '') . ' title="содержимое раздела">содержимое</a>';
        $this->appendMainTab('Items', $tab);
    }
}
