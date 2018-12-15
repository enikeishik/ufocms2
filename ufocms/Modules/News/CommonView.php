<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\News;

/**
 * Module level common (for all sections) view
 */
class CommonView extends \Ufocms\Modules\View //implements ViewInterface
{
    use Tools;
    
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
        $arr['section']['indic']    = 'Новости';
        $arr['section']['title']    = 'Новости';
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
        } else if (null !== $this->moduleParams['authors']) {
            return '/commonauthors.php';
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
