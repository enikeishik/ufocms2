<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\Tales;

/**
 * Module level UI
 */
class UI extends \Ufocms\AdminModules\UI
{
    
    /**
     * @see parent
     */
    protected function getFormFieldAttributes(array $field, $value)
    {
        $s = '';
        if (0 == $this->params->itemId) {
            switch ($field['Name']) {
                case 'Url':
                    $s = ' onchange="checkAlias(this)" onblur="checkAlias(this)"';
                    break;
                case 'Title':
                    $s = ' onkeyup="setAlias(this)" onchange="setAlias(this)" onblur="setAlias(this)"';
                    break;
                case 'MetaDesc':
                    $s = ' onblur="blnDescrSet=\'\'!=this.value"';
                    break;
                case 'MetaKeys':
                    $s = ' onblur="blnKeysSet=\'\'!=this.value"';
                    break;
            }
        } else {
            switch ($field['Name']) {
                case 'Url':
                    $s = ' readonly ondblclick="makeEditable(this)"';
                    break;
            }
        }
        return parent::getFormFieldAttributes($field, $value) . $s;
    }
    
    /**
     * @see parent
     */
    public function form()
    {
        return  '<script type="text/javascript" src="templates/tales/form.js"></script>' . 
                parent::form();
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
