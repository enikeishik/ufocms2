<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\SysSearch;

/**
 * Structure containing section fields
 */
class Section extends \Ufocms\Modules\Section
{
    public function __construct()
    {
        //set some values for $section here
        $this->section['indic'] = 'Поиск по сайту';
        $this->section['title'] = 'Поиск по сайту';
    }
}
