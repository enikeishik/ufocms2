<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\CoreSendform;

/**
 * AdminWidget model class
 */
class AdminWidget extends \Ufocms\AdminModules\AdminWidget
{
    public function render()
    {
        $sql =  'SELECT *' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'sendforms' . 
                ' WHERE Status<2' . 
                ' ORDER BY DateCreate DESC' . 
                ' LIMIT 5';
        $items = $this->db->getItems($sql);
        if (null !== $items && 0 < count($items)) {
            echo    '<div class="widget">' . 
                    '<div class="caption">Формы</div>' . 
                    '<div class="items">';
            foreach ($items as $item) {
                echo    '<div class="item">' . 
                            '<div class="itemhead">' . 
                                '<div class="fieldvalue">' . $item['DateCreate'] . '</div>' . 
                                '<div class="fieldvalue">' . $item['Url'] . '</div>' . 
                            '</div>' . 
                            '<div class="itembody">' . 
                                '<div class="fieldvalue">' . $item['Form'] . '</div>' . 
                            '</div>' . 
                        '</div>';
            }
            echo    '</div>' . 
                    '</div>' . "\r\n";
        }
    }
}
