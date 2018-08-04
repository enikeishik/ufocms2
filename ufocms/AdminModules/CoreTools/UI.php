<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\CoreTools;

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
        $this->section = array('path' => '', 'title' => 'Инструменты');
    }
    
    /**
     * @see parent
     */
    protected function setMainTabs()
    {
        parent::setMainTabs();
        $tab = '<a href="?' . $this->config->paramsNames['coreModule'] . '=' . $this->params->coreModule . '" class="current" title="инструменты">инструменты</a>';
        $this->appendMainTab('Index', $tab);
        $tab = '<a href="?' . $this->config->paramsNames['coreModule'] . '=' . $this->params->coreModule . '&' . $this->config->paramsNames['subModule'] . '=backup" title="резервные копии системы">резерв.копии</a>';
        $this->appendMainTab('Backup', $tab);
        $tab = '<a href="?' . $this->config->paramsNames['coreModule'] . '=' . $this->params->coreModule . '&' . $this->config->paramsNames['subModule'] . '=update" title="обновление системы">обновление</a>';
        $this->appendMainTab('Update', $tab);
        $tab = '<a href="?' . $this->config->paramsNames['coreModule'] . '=' . $this->params->coreModule . '&' . $this->config->paramsNames['subModule'] . '=install" title="установка модуля">установка</a>';
        $this->appendMainTab('Install', $tab);
    }
}
