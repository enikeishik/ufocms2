<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\Board;

/**
 * Module level UI
 */
class UI extends \Ufocms\AdminModules\UI
{
    /**
     * @see parent
     */
    protected function formFieldMediumtextElement(array $field, $value)
    {
        return  '<textarea class="mceNoEditor" cols="50" rows="10"' . $this->getFormFieldAttributes($field, $value) . '>' . 
                str_replace('<br>', "\r\n", str_replace(["\r", "\n"], '', $value)) . 
                '</textarea>';
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
