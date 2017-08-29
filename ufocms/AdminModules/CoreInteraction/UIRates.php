<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\CoreInteraction;

/**
 * Module level UI
 */
class UIRates extends \Ufocms\AdminModules\UI
{
    /**
     * @see parent
     */
    protected function init()
    {
        $this->section = array('path' => '', 'title' => 'Интерактив');
    }
    
    /**
     * @see parent
     */
    protected function setItemFuncs(array $item, array $funcs)
    {
        parent::setItemFuncs($item, array('disable', 'delconfirm'));
        $s = ' <a href="' . $this->basePath . '&' . $this->config->paramsNames['action'] . '=blacklist&' . $this->config->paramsNames['itemId'] . '=' . $item['itemid'] . '" title="Добавить IP в черный список">в ч.с.</a>';
        $this->appendItemFunc('blacklist', $s);
    }
    
    /**
     * @see parent
     */
    protected function listItemsItemFieldRaw(array $field, array $item)
    {
        return  '<td class="type-' . $field['Type'] . '">' . 
                    '<div style="font-size: 10px; max-width: 260px; overflow: auto;">' . 
                        '<pre>' . $item[$field['Name']] . '</pre>' . 
                    '</div>' . 
                '</td>';
    }
    
    /**
     * @see parent
     */
    protected function setMainTabs()
    {
        parent::setMainTabs();
        $tab = '<a href="?' . $this->config->paramsNames['coreModule'] . '=' . $this->params->coreModule . '" title="комментарии">комментарии</a>';
        $this->appendMainTab('Items', $tab);
        $tab = '<a href="?' . $this->config->paramsNames['coreModule'] . '=' . $this->params->coreModule . '&' . $this->config->paramsNames['subModule'] . '=rates" class="current" title="оценки">оценки</a>';
        $this->appendMainTab('Rates', $tab);
        $tab = '<a href="?' . $this->config->paramsNames['coreModule'] . '=' . $this->params->coreModule . '&' . $this->config->paramsNames['subModule'] . '=blacklist" title="Черный список">Ч.С.</a>';
        $this->appendMainTab('Blacklist', $tab);
    }
}
