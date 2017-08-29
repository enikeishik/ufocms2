<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\SysULogin;

/**
 * Structure containing section fields
 */
class Section extends \Ufocms\Modules\Section
{
    public function __construct()
    {
        //set some values for $section here
        $this->section['indic'] = 'Авторизация через сервис uLogin';
        $this->section['title'] = 'Авторизация через сервис uLogin';
    }
}
