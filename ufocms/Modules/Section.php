<?php
/**
 * @copyright
 */

namespace Ufocms\Modules;

/**
 * Structure containing section fields
 */
class Section
{
    public $section = array(
        'id'            => 0, 
        'topid'         => 0, 
        'parentid'      => 0, 
        'orderid'       => 0, 
        'levelid'       => 0, 
        'isparent'      => false, 
        'moduleid'      => 0, 
        'designid'      => 0, 
        'mask'          => '', 
        'path'          => '', 
        'image'         => '', 
        'timage'        => '', 
        'indic'         => '', 
        'title'         => '', 
        'metadesc'      => '', 
        'metakeys'      => '', 
        'isenabled'     => true, 
        'insearch'      => false, 
        'inmenu'        => false, 
        'inlinks'       => false, 
        'inmap'         => false, 
        'shtitle'       => 0, 
        'shmenu'        => 0, 
        'shlinks'       => 0, 
        'shcomments'    => 0, 
        'shrating'      => 0, 
        'flsearch'      => 0, 
        'flcache'       => 0, 
    );
}
