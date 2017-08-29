<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\News;

/**
 * Module level UI
 */
class UIImportSettings extends \Ufocms\AdminModules\News\UI
{
    protected function setItemFuncs(array $item, array $funcs)
    {
        parent::setItemFuncs($item, $funcs);
        if (!in_array('add', $funcs)) {
            $s = '<br><a href="' . $this->basePath . '&' . $this->config->paramsNames['action'] . '=resetguid&' . $this->config->paramsNames['itemId'] . '=' . $item['itemid'] . '" title="Сбросить GUID">GUID</a>';
            $this->appendItemFunc('resetguid', $s);
        }
    }
}
