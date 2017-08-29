<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\Mainpage;

/**
 * Module level UI
 */
class UI extends \Ufocms\AdminModules\UI
{
    /**
     * @see parent
     */
    protected function formFieldBigtextElement(array $field, $value)
    {
        return '<textarea cols="100" rows="30"' . $this->getFormFieldAttributes($field, $value) . '>' . htmlspecialchars($value) . '</textarea>';
    }
    
    /**
     * @see parent
     */
    protected function setMainTabs()
    {
        parent::setMainTabs();
        $tab = '<a href="?' . $this->config->paramsNames['sectionId'] . '=' . $this->params->sectionId . '&code=1"' . (isset($_GET['code']) ? ' class="current"' : '') . ' title="редактирование HTML">HTML</a>';
        $this->appendMainTab('Html', $tab, 'Items');
    }
}
