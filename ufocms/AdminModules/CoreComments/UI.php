<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\CoreComments;

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
        $this->section = array('path' => '', 'title' => 'Комментарии');
    }
    
    /**
     * @see parent
     */
    protected function singleItemHeadField(array $field, array $item)
    {
        if ('url' == $field['Name']) {
            $s =    '<div class="itemfield type-' . $field['Type'] . '">' . 
                        '<span class="fieldname">' . $field['Title'] . '</span>' . 
                        '<span class="fieldvalue">' . 
                            '<a href="' . $item[$field['Name']] . '" target="_blank">' . 
                                htmlspecialchars($item[$field['Name']]) . 
                            '</a>' . 
                        '</span>' . 
                    '</div>';
            return $s;
        }
        return parent::singleItemHeadField($field, $item);
    }
    
    /**
     * @see parent
     */
    protected function singleItemBodyField(array $field, array $item)
    {
        if ('info' == $field['Name']) {
            $s =    '<div class="itemfield type-' . $field['Type'] . '">' . 
                        '<div class="fieldname">' . $field['Title'] . '</div>' . 
                        '<div class="fieldvalue">' . 
                            '<div style="font-size: 10px; max-height: 60px; overflow: auto;">' . 
                                '<pre style="margin: 0px;">' . trim($item[$field['Name']]) . '</pre>' . 
                            '</div>' . 
                        '</div>' . 
                    '</div>';
            return $s;
        }
        return parent::singleItemBodyField($field, $item);
    }
    
    /**
     * @see parent
     */
    protected function setItemFuncs(array $item, array $funcs)
    {
        parent::setItemFuncs($item, $funcs) . '<br>';
        $s = ' <a href="' . $this->basePath . '&' . $this->config->paramsNames['action'] . '=blacklist&' . $this->config->paramsNames['itemId'] . '=' . $item['itemid'] . '" title="Добавить IP в черный список для данного URL">в ч.с.</a>';
        $this->appendItemFunc('blacklist', $s, 'disable');
        $s = ' <a href="' . $this->basePath . '&' . $this->config->paramsNames['action'] . '=globalblacklist&' . $this->config->paramsNames['itemId'] . '=' . $item['itemid'] . '" title="Добавить IP в глобальный черный список">в г.ч.с.</a>';
        $this->appendItemFunc('globalblacklist', $s, 'blacklist');
    }
    
    /**
     * @see parent
     */
    protected function setMainTabs()
    {
        parent::setMainTabs();
        $tab = '<a href="?' . $this->config->paramsNames['coreModule'] . '=' . $this->params->coreModule . '" class="current" title="содержимое раздела">содержимое</a>';
        $this->appendMainTab('Items', $tab);
        $tab = '<a href="?' . $this->config->paramsNames['coreModule'] . '=' . $this->params->coreModule . '&' . $this->config->paramsNames['subModule'] . '=blacklist" title="Черный список">Ч.С.</a>';
        $this->appendMainTab('Blacklist', $tab, 'Items');
    }
}
