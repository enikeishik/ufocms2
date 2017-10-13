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
            $s = '<a href="' . $this->basePath . '&' . $this->config->paramsNames['subModule'] . '=answers&votingid=' . $item['Id'] . '" title="Редактировать ответы" style="padding: 2px 15px;">Ответы</a> ';
            $this->appendItemFunc('answers', $s, '');
            $s = '<a href="' . $this->basePath . '&' . $this->config->paramsNames['subModule'] . '=votes&votingid=' . $item['Id'] . '" title="Просмотр лога голосов" style="padding: 2px 15px;">Голоса</a> <br><br>';
            $this->appendItemFunc('votes', $s, 'answers');
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
