<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\Board;

/**
 * Main module view
 */
class CommonView extends \Ufocms\Modules\View //implements ViewInterface
{
    /**
     * @see parent
     */
    protected function init()
    {
        if (0 != $this->params->itemId) {
            $item = $this->model->getItem();
            $this->core->riseError(301, 'Use section path', $item['path'] . $this->params->itemId);
        }
        parent::init();
    }
    
    /**
     * @see parent
     */
    protected function getApplicationContext()
    {
        $arr = parent::getApplicationContext();
        $arr['section']['path']     = $this->params->sectionPath . '/' . strtolower($this->module['Name']) . '/';
        $arr['section']['indic']    = 'Объявления';
        $arr['section']['title']    = 'Объявления';
        return $arr;
    }
    
    /**
     * @see parent
     */
    protected function getModuleTemplateEntry()
    {
        if (0 != $this->params->itemId) {
            //not used, riseError 301 in init()
            return '/commonitem.php';
        } else {
            return '/commonindex.php';
        }
    }
    
    /**
     * @see parent
     */
    protected function renderHead($entry = null)
    {
        if (0 == $this->params->itemId) {
            parent::renderHead();
        }
    }
}
