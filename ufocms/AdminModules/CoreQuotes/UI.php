<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\CoreQuotes;

/**
 * Module level UI
 */
class UI extends \Ufocms\AdminModules\UI
{
    protected function init()
    {
        $this->section = array('path' => '', 'title' => 'Цитаты');
    }
    
    protected function setItemFuncs(array $item, array $funcs)
    {
        parent::setItemFuncs($item, $funcs);
        if (null === $this->params->subModule) {
            if (in_array('add', $funcs)) {
                $s = ' <a href="' . $this->basePath . '&action=add&itemid0&code=1" title="Создать в HTML редакторе">HTML</a>';
                $this->appendItemFunc('html', $s, 'add');
            } else {
                $s = ' <a href="' . $this->basePath . '&action=edit&itemid=' . $item['itemid'] . '&code=1" title="Редактировать в HTML редакторе">HTML</a>';
                $this->appendItemFunc('html', $s, 'edit');
            }
        } else if ('groups' == $this->params->subModule) {
            $this->appendItemFunc('spacer', '&nbsp;', 'delconfirm');
            $s = ' <a href="javascript:getHtml(' . $item['itemid'] . ')" title="Получить код группы для размещения в HTML">JS</a>';
            $this->appendItemFunc('html', $s, 'spacer');
            $s = ' <a href="javascript:getPhp(' . $item['itemid'] . ')" title="Получить код группы для размещения в PHP">PHP</a>';
            $this->appendItemFunc('php', $s, 'html');
        }
    }
    
    public function headCode()
    {
        $s = parent::headCode();
        if ('groups' == $this->params->subModule) {
            $s .= '<script type="text/javascript" src="templates/quotes.js"></script>';
        }
        return $s;
    }
    
    public function headers()
    {
        header('X-XSS-Protection:0'); //to avoid chrome ERR_BLOCKED_BY_XSS_AUDITOR
    }
    
    protected function setMainTabs()
    {
        parent::setMainTabs();
        $tab = '<a href="?' . $this->config->paramsNames['coreModule'] . '=' . $this->params->coreModule . '"' . (null === $this->params->subModule ? ' class="current"' : '') . ' title="Редактирование цитат">цитаты</a>';
        $this->appendMainTab('Items', $tab);
        $tab = '<a href="?' . $this->config->paramsNames['coreModule'] . '=' . $this->params->coreModule . '&' . $this->config->paramsNames['subModule'] . '=groups"' . ('groups' == $this->params->subModule ? ' class="current"' : '') . ' title="Редактирование групп">группы</a>';
        $this->appendMainTab('Blacklist', $tab, 'Items');
    }
}
