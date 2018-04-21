<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\CoreRoles;

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
        $this->section = array('path' => '', 'title' => 'Роли управления');
    }
    
    /**
     * @see parent
     */
    protected function setItemFuncs(array $item, array $funcs)
    {
        parent::setItemFuncs($item, $funcs);
        if (__CLASS__ == get_class($this) && isset($item['Id'])) {
            if ($item['IsSystem']) {
                $this->setItemFuncs(array(), array());
            }
            $s = '<a href="' . $this->basePath . '&' . $this->config->paramsNames['subModule'] . '=restrictions&roleid=' . $item['Id'] . '" title="Редактировать ограничения" style="padding: 2px 15px;">Ограничения</a> ';
            $this->appendItemFunc('restrictions', $s, '');
            $s = '<a href="' . $this->basePath . '&' . $this->config->paramsNames['subModule'] . '=permsmods&roleid=' . $item['Id'] . '" title="Редактировать разрешения на модули разделов" style="padding: 2px 15px;">Модули</a> ';
            $this->appendItemFunc('permsmods', $s, 'restrictions');
            $s = '<a href="' . $this->basePath . '&' . $this->config->paramsNames['subModule'] . '=permscore&roleid=' . $item['Id'] . '" title="Редактировать разрешения на модули ядра" style="padding: 2px 15px;">Ядро</a> <br><br>';
            $this->appendItemFunc('permscore', $s, 'permsmods');
        }
    }
    
    /**
     * @see parent
     */
    protected function formFieldMediumtextElement(array $field, $value)
    {
        return  '<textarea class="mceNoEditor" cols="50" rows="10"' . $this->getFormFieldAttributes($field, $value) . '>' . 
                str_replace('<br>', "\r\n", str_replace(["\r", "\n"], '', $value)) . 
                '</textarea>';
    }
}
