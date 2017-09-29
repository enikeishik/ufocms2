<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\Votings;

/**
 * Main module model
 */
class View extends \Ufocms\Modules\View //implements IView
{
    protected function getModuleContext()
    {
        if (0 == $this->params->itemId) {
            return parent::getModuleContext();
        }
        
        $context = parent::getModuleContext();
        $item = $context['item'] ? : $this->model->getItem();
        
        return array_merge(
            $context, 
            array(
                'ticket'        => $this->model->getTicket(), 
                'showForm'      => $this->isShowForm($item, $this->moduleParams), 
                'showResults'   => $this->isShowResults($item, $this->moduleParams), 
            )
        );
    }
    
    /**
     * @param array $voting
     * @param array $moduleParams
     * @return bool
     */
    public function isShowForm(array $voting, array $moduleParams)
    {
        return  !$voting['IsClosed'] 
                && (!$this->model->isVoted($voting['Id']) && !$moduleParams['results']) 
                && (time() < strtotime($voting['DateStop']));
    }
    
    /**
     * @param array $voting
     * @param array $moduleParams
     * @return bool
     */
    public function isShowResults(array $voting, array $moduleParams)
    {
        return  ($moduleParams['results'] && -1 == $voting['ResultsDisplay']) 
                || ($this->model->isVoted($voting['Id']) && $voting['ResultsDisplay'] < 1) 
                || (time() > strtotime($voting['DateStop']));
    }
}
