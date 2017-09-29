<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\Votings;

/**
 * Module level UI
 */
class UIAnswers extends UI
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
        return $title . ' \ ответы';
    }
}
