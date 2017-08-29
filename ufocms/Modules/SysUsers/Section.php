<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\SysUsers;

/**
 * Structure containing section fields
 */
class Section extends \Ufocms\Modules\Section
{
    public function __construct()
    {
        //set some values for $section here
        $this->section['indic'] = 'Зарегистрированные пользователи сайта';
        $this->section['title'] = 'Зарегистрированные пользователи сайта';
    }
}
