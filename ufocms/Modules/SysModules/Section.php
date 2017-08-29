<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\SysModules;

/**
 * Structure containing section fields
 */
class Section extends \Ufocms\Modules\Section
{
    public function __construct()
    {
        //set some values for $section here
        $this->section['indic'] = 'Модули сайта';
        $this->section['title'] = 'Модули сайта';
    }
}
