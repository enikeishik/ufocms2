<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\CoreComments;

/**
 * AdminWidget model class
 */
class AdminWidget extends \Ufocms\AdminModules\AdminWidget
{
    public function render()
    {
        $sql =  'SELECT *' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'comments' . 
                ' WHERE disabled=0' . 
                ' ORDER BY dtm DESC' . 
                ' LIMIT 5';
        $items = $this->db->getItems($sql);
        if (null !== $items && 0 < count($items)) {
            echo    '<div class="widget">' . 
                    '<div class="caption">Комментарии</div>' . 
                    '<div class="items">';
            foreach ($items as $item) {
                echo    '<div class="item">' . 
                            '<div class="itemhead">' . 
                                '<div class="fieldvalue">' . 
                                    'URL: ' . htmlspecialchars($item['url']) . ' ' . 
                                    'IP: ' . htmlspecialchars($item['ip']) . 
                                '</div>' . 
                            '</div>' . 
                            '<div class="itembody">' . 
                                '<div class="fieldvalue">' . htmlspecialchars($item['comment']) . '</div>' . 
                                '<div class="fieldvalue">' . 
                                    htmlspecialchars($item['comment_sign']) . ' ' . 
                                    htmlspecialchars($item['comment_email']) . ' ' . 
                                    htmlspecialchars($item['comment_url']) . 
                                '</div>' . 
                            '</div>' . 
                        '</div>';
            }
            echo    '</div>' . 
                    '</div>' . "\r\n";
        }
    }
}
