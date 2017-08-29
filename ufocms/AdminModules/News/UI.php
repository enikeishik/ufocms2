<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\News;

/**
 * Module level UI
 */
class UI extends \Ufocms\AdminModules\UI
{
    protected function setItemFuncs(array $item, array $funcs)
    {
        parent::setItemFuncs($item, $funcs);
        if (__CLASS__ == get_class($this) && in_array('add', $funcs)) {
            $s = ' <a href="' . $this->basePath . '&' . $this->config->paramsNames['action'] . '=import" title="Импорт из источников RSS">Импорт</a>';
            $this->appendItemFunc('import', $s);
            /*
            $s = '<br>';
            if () {
                $s .= ' <a href="' . $this->basePath . '&' . $this->config->paramsNames['action'] . '=rsson&' . $this->config->paramsNames['itemId'] . '=' . $item['itemid'] . '" title="Отображать в RSS ленте">RSS</a>';
            } else {
                $s .= ' <a href="' . $this->basePath . '&' . $this->config->paramsNames['action'] . '=rssoff&' . $this->config->paramsNames['itemId'] . '=' . $item['itemid'] . '" title="Скрыть из RSS ленты">RSS</a>';
            }
            $this->appendItemFunc('rss', $s);
            */
        }
    }
    
    protected function setMainTabs()
    {
        parent::setMainTabs();
        $tab = '<a href="?' . $this->config->paramsNames['sectionId'] . '=' . $this->params->sectionId . '&' . $this->config->paramsNames['subModule'] . '=import"' . ('import' == $this->params->subModule ? ' class="current"' : '') . ' title="Настройки источников RSS">источники RSS</a>';
        $this->appendMainTab('Import', $tab, 'Items');
        $tab = '<a href="?' . $this->config->paramsNames['sectionId'] . '=' . $this->params->sectionId . '&' . $this->config->paramsNames['subModule'] . '=settings"' . ('settings' == $this->params->subModule ? ' class="current"' : '') . ' title="Настройки модуля">настройки</a>';
        $this->appendMainTab('Settings', $tab, 'Import');
        $tab = '<a href="?' . $this->config->paramsNames['sectionId'] . '=' . $this->params->sectionId . '"' . (is_null($this->params->subModule) ? ' class="current"' : '') . ' title="содержимое раздела">содержимое</a>';
        $this->appendMainTab('Items', $tab);
    }
}
