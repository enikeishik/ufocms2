<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\CoreInteraction;

/**
 * AdminWidget model class
 */
class AdminWidget extends \Ufocms\AdminModules\AdminWidget
{
    public function render()
    {
        $sql =  'SELECT c.*, s.path, s.indic' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'interaction_comments AS c' . 
                ' LEFT JOIN ' . C_DB_TABLE_PREFIX . 'sections AS s ON c.SectionId=s.id' . 
                ' WHERE c.IsDisabled=0' . 
                ' ORDER BY c.DateCreate DESC' . 
                ' LIMIT 5';
        $items = $this->db->getItems($sql);
        if (null !== $items && 0 < count($items)) {
            echo    '<div class="widget">' . 
                    '<div class="caption">Интерактив</div>' . 
                    '<div class="items">';
            foreach ($items as $item) {
                echo    '<div class="item">' . 
                            '<div class="itemhead">' . 
                                '<div class="fieldvalue">' . 
                                    'Раздел: «' . htmlspecialchars($item['indic']) . '» ' . 
                                    'IP: ' . htmlspecialchars($item['IP']) . 
                                '</div>' . 
                            '</div>' . 
                            '<div class="itembody">' . 
                                '<div class="fieldvalue">' . htmlspecialchars($item['CommentText']) . '</div>' . 
                                '<div class="fieldvalue">' . 
                                    htmlspecialchars($item['CommentAuthor']) . ' ' . 
                                    htmlspecialchars($item['CommentEmail']) . ' ' . 
                                    htmlspecialchars($item['CommentUrl']) . ' ' . 
                                    htmlspecialchars($item['CommentStatus']) . ' ' . 
                                '</div>' . 
                            '</div>' . 
                        '</div>';
            }
            echo    '</div>' . 
                    '</div>' . "\r\n";
        }
    }
}
