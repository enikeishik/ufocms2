<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\CoreInteraction;

/**
 * Module level UI
 */
class UICommentRates extends UIRates
{
    /**
     * @see parent
     */
    protected function setMainTabs()
    {
        parent::setMainTabs();
        $tab = '<a href="?' . $this->config->paramsNames['coreModule'] . '=' . $this->params->coreModule . '" class="current" title="комментарии">комментарии</a>';
        $this->appendMainTab('Items', $tab);
        $tab = '<a href="?' . $this->config->paramsNames['coreModule'] . '=' . $this->params->coreModule . '&' . $this->config->paramsNames['subModule'] . '=rates" title="оценки">оценки</a>';
        $this->appendMainTab('Rates', $tab);
        $tab = '<a href="?' . $this->config->paramsNames['coreModule'] . '=' . $this->params->coreModule . '&' . $this->config->paramsNames['subModule'] . '=blacklist" title="Черный список">Ч.С.</a>';
        $this->appendMainTab('Blacklist', $tab);
    }
}
