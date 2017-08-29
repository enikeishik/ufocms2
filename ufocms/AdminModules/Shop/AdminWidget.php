<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\Shop;

/**
 * AdminWidget model class
 */
class AdminWidget extends \Ufocms\AdminModules\AdminWidget
{
    use OrdersStatuses;
    
    public function render()
    {
        $items = $this->getOrders();
        if (null !== $items && 0 < count($items)) {
            echo    '<div class="widget">' . 
                    '<div class="caption">Магазин, заказы</div>' . 
                    '<div class="items">';
            foreach ($items as $item) {
                $user = '';
                if (0 != $item['UserId']) {
                    $userData = $this->core->getUsers()->get($item['UserId']);
                    if (null !== $userData) {
                        $user = '<div class="fieldvalue">Пользователь: «' . htmlspecialchars($userData['Title']) . '»</div>';
                        unset($userData);
                    }
                }
                echo    '<div class="item">' . 
                            '<div class="itemhead">' . 
                                '<div class="fieldvalue">Id: ' . $item['Id'] . ' Дата оформления: ' . $item['DateCreate'] . '</div>' . 
                                '<div class="fieldvalue">Раздел: «' . htmlspecialchars($item['indic']) . '» </div>' . 
                                $user . 
                                '<div class="fieldvalue">Статус: ' . $this->getStatus($item['Status'])['Title'] . '</div>' . 
                            '</div>' . 
                            '<div class="itembody">' . 
                                '<div class="fieldvalue">';
                $elements = $this->getOrderItems($item['Id']);
                if (null !== $elements) {
                    foreach ($elements as $element) {
                        if ($element['Title']) {
                            echo    '<div class="subfields">' . 
                                        htmlspecialchars($element['Title']) . 
                                        '<span>' . $element['ItemsCount'] . ' шт.</span>' . 
                                        '<span>' . $element['Price'] . ' руб.</span>' . 
                                    '</div>' . 
                                    '<div class="clear"></div>';
                        } else {
                            echo    '<div class="subfields">' . 
                                        'товар удален' . 
                                        '<span>' . $element['ItemsCount'] . ' шт.</span>' . 
                                        '<span>-</span>' . 
                                    '</div>' . 
                                    '<div class="clear"></div>';
                        }
                    }
                }
                echo            
                                '</div>' . 
                            '</div>' . 
                        '</div>';
            }
            echo    '</div>' . 
                    '</div>'. "\r\n";
        }
    }
    
    /**
     * @return array|null
     */
    protected function getOrders()
    {
        $sql =  'SELECT o.Id, o.UserId, o.Status, o.DateCreate, s.path, s.indic' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'shop_orders AS o' . 
                ' INNER JOIN ' . C_DB_TABLE_PREFIX . 'sections AS s ON o.SectionId=s.id' . 
                ' WHERE o.Status>0 AND o.Status<9' . 
                ' ORDER BY o.DateCreate DESC' . 
                ' LIMIT 5';
        return $this->db->getItems($sql);
    }
    
    /**
     * @param int $orderId
     * @return array|null
     */
    public function getOrderItems($orderId)
    {
        $sql =  'SELECT oi.ItemsCount, i.Id, i.Alias, i.Title, i.Price,' . 
                ' c.Alias AS CategoryAlias, c.Title AS CategoryTitle' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'shop_orders_items AS oi' . 
                ' LEFT JOIN ' . C_DB_TABLE_PREFIX . 'shop_items AS i ON oi.ItemId=i.Id' . 
                ' LEFT JOIN ' . C_DB_TABLE_PREFIX . 'shop_categories AS c ON i.CategoryId=c.Id' . 
                ' WHERE oi.OrderId=' . $orderId . 
                ' ORDER BY oi.Id';
        return $this->db->getItems($sql);
    }
}
