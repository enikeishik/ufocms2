<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\SysSitemap;

/**
 * Structure containing section fields
 */
class Section extends \Ufocms\Modules\Section
{
    public function __construct()
    {
        //set some values for $section here
        $this->section['indic'] = 'Карта сайта';
        $this->section['title'] = 'Карта сайта';
    }
}
