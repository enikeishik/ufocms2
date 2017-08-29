<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\AdminStart;

/**
 * AdminWidget model class
 */
class AdminWidget extends \Ufocms\AdminModules\AdminWidget
{
    public function render()
    {
        $items = array(
            ['title' => 'Version', 'body' => '2.0 beta'], 
            ['title' => 'License', 'body' => 'GNU GENERAL PUBLIC LICENSE Version 3, 29 June 2007 <a href="/license.txt" target="_blank">license.txt</a>', 'raw' => true], 
            ['title' => 'Requirements', 'body' => 'PHP >= 7.0; MySQL >= 5.5 / MariaDB >= 10'], 
        );
        echo    '<div class="widget">' . 
                '<div class="caption">Информация</div>' . 
                '<div class="items">';
        foreach ($items as $item) {
            echo    '<div class="item">' . 
                        '<div class="itemhead">' . 
                            '<div class="fieldvalue">' . $item['title'] . '</div>' . 
                        '</div>' . 
                        '<div class="itembody">' . 
                            '<div class="fieldvalue">' . (isset($item['raw']) && $item['raw'] ? $item['body'] : htmlspecialchars($item['body'])) . '</div>' . 
                        '</div>' . 
                    '</div>';
        }
        echo '</div>' . 
             '</div>' . "\r\n";
    }
}
