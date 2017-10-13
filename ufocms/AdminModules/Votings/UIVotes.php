<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\Votings;

/**
 * Module level UI
 */
class UIVotes extends UI
{
    /**
     * @see parent
     */
    protected function getMasterTitle()
    {
        $title = parent::getMasterTitle();
        if ('' == $title) {
            return '';
        }
        return $title . ' \ голоса';
    }
    
    /**
     * @param array $field
     * @param string $basePathFields
     * @return string
     */
    protected function filterByTypeIp(array $field, $basePathFields)
    {
        $s =    '<form action="' . $this->basePath . '" method="get">' . 
                '<div>Искать в поле «' . $field['Title'] . '»</div>' . 
                $basePathFields . 
                '<input type="hidden" name="' . $this->config->paramsNames['action'] . '" value="filter">' . 
                '<input type="hidden" name="' . $this->config->paramsNames['page'] . '" value="1">' . 
                '<input type="hidden" name="filtername" value="' . $field['Name'] . '">' . 
                '<input type="text" pattern="\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}" placeholder="123.123.123.123" name="filtervalue" value="' . ($this->params->filterName == $field['Name'] ? htmlspecialchars($this->params->filterValue) : '') . 
                    '" maxlength="255">' . 
                '<input type="submit" value="&gt;">' . 
                '</form>';
        return $s;
    }
}
