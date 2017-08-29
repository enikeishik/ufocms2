<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\CoreComments;

/**
 * Module level UI
 */
class UIBlacklist extends \Ufocms\AdminModules\UI
{
    protected function init()
    {
        $this->section = array('path' => '', 'title' => 'Комментарии');
    }
    
    /**
     * @param array $item
     * @param array $funcs
     */
    protected function setItemFuncs(array $item, array $funcs)
    {
        parent::setItemFuncs($item, array('delconfirm'));
    }
    
    protected function setMainTabs()
    {
        parent::setMainTabs();
        $tab = '<a href="?' . $this->config->paramsNames['coreModule'] . '=' . $this->params->coreModule . '" title="содержимое раздела">содержимое</a>';
        $this->appendMainTab('Items', $tab);
        $tab = '<a href="?' . $this->config->paramsNames['coreModule'] . '=' . $this->params->coreModule . '&' . $this->config->paramsNames['subModule'] . '=blacklist" class="current" title="Черный список">Ч.С.</a>';
        $this->appendMainTab('Blacklist', $tab, 'Items');
    }
}
