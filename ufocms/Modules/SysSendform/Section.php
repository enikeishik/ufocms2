<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\SysSendform;

/**
 * Structure containing section fields
 */
class Section extends \Ufocms\Modules\Section
{
    public function __construct()
    {
        //set some values for $section here
        $this->section['indic'] = 'Отправка формы';
        $this->section['title'] = 'Отправка формы';
    }
}
