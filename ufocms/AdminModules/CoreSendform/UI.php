<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\CoreSendform;

/**
 * Module level UI
 */
class UI extends \Ufocms\AdminModules\UI
{
    protected function init()
    {
        $this->section = array('path' => '', 'title' => 'Результаты форм');
    }
    
    /**
     * @param array $item
     * @param array $funcs
     */
    protected function setItemFuncs(array $item, array $funcs)
    {
        $this->itemFuncs = array();
        $s = '';
        if (2 > $item['Status']) {
            $s .= ' <a href="' . $this->basePath . '&action=remove&itemid=' . $item['itemid'] . '" title="Пометить как прочитанное">Убрать</a>';
        }
        $s .= ' <a href="' . $this->basePath . '&action=delete&itemid=' . $item['itemid'] . '" title="Удалить" onclick="return confirm(\'Удалить запись?\')">Удалить</a>';
        $this->appendItemFunc('', $s);
    }
}
