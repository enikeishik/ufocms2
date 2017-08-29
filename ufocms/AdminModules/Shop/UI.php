<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\Shop;

/**
 * Module level UI
 */
class UI extends \Ufocms\AdminModules\UI
{
    protected function setItemFuncs(array $item, array $funcs)
    {
        parent::setItemFuncs($item, $funcs);
        if (__CLASS__ == get_class($this) && in_array('add', $funcs)) {
            $categoryId = 0;
            if ('CategoryId' == $this->params->filterName && 0 != $this->params->filterValue) {
                $categoryId = (int) $this->params->filterValue;
            }
            $blocker = '';
            if (0 == $categoryId) {
                $blocker = ' onclick="alert(\'Укажите категорию\');return false;"';
            }
            $s = ' <a' . $blocker . ' href="' . $this->basePath . '&' . $this->config->paramsNames['action'] . '=import&categoryid=' . $categoryId . '" title="Импорт из файла CSV">Импорт</a>';
            $this->appendItemFunc('import', $s);
            $s = ' <a' . $blocker . ' href="' . $this->basePath . '&' . $this->config->paramsNames['action'] . '=export&categoryid=' . $categoryId . '" title="Импорт в файл CSV">Экспорт</a>';
            $this->appendItemFunc('export', $s);
        }
    }
    
    /**
     * @see parent
     */
    protected function getFormFieldAttributes(array $field, $value)
    {
        $s = '';
        if (0 == $this->params->itemId) {
            switch ($field['Name']) {
                case 'Alias':
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
                case 'Alias':
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
        return  '<script type="text/javascript" src="templates/shop/form.js"></script>' . 
                parent::form();
    }
    
    protected function setMainTabs()
    {
        parent::setMainTabs();
        $tab = '<a href="?' . $this->config->paramsNames['sectionId'] . '=' . $this->params->sectionId . '&' . $this->config->paramsNames['subModule'] . '=categories"' . ('categories' == $this->params->subModule ? ' class="current"' : '') . ' title="Редактирование каталога категорий">категории</a>';
        $this->appendMainTab('Categories', $tab, 'Items');
        $tab = '<a href="?' . $this->config->paramsNames['sectionId'] . '=' . $this->params->sectionId . '&' . $this->config->paramsNames['subModule'] . '=orders"' . ('orders' == $this->params->subModule ? ' class="current"' : '') . ' title="Заказы пользователей">заказы</a>';
        $this->appendMainTab('Orders', $tab, 'Categories');
        $tab = '<a href="?' . $this->config->paramsNames['sectionId'] . '=' . $this->params->sectionId . '&' . $this->config->paramsNames['subModule'] . '=settings"' . ('settings' == $this->params->subModule ? ' class="current"' : '') . ' title="Настройки модуля">настройки</a>';
        $this->appendMainTab('Settings', $tab, 'Orders');
        $tab = '<a href="?' . $this->config->paramsNames['sectionId'] . '=' . $this->params->sectionId . '"' . (is_null($this->params->subModule) ? ' class="current"' : '') . ' title="содержимое раздела">содержимое</a>';
        $this->appendMainTab('Items', $tab);
    }
}
