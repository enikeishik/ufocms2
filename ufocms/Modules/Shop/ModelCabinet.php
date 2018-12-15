<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\Shop;

use Ufocms\Frontend\Container;

/**
 * Main module model
 */
class ModelCabinet extends \Ufocms\Modules\Model //implements IModel
{
    /**
     * Status id and date field in DB.
     * @var array
     */
    private $statuses = array( 
        0 => array('Title' => 'Инициирован',    'DateField' => 'DateInit',      'Admin' => false), 
        1 => array('Title' => 'Оформлен',       'DateField' => 'DateCreate',    'Admin' => false), 
        2 => array('Title' => 'Оплачен',        'DateField' => 'DatePaid',      'Admin' => true), 
        3 => array('Title' => 'Собран',         'DateField' => 'DateEquip',     'Admin' => true), 
        4 => array('Title' => 'Отправлен',      'DateField' => 'DateSend',      'Admin' => true), 
        9 => array('Title' => 'Закрыт',         'DateField' => 'DateClosed',    'Admin' => true)
    );
    
    /**
     * @var int
     */
    protected $userId = null;
    
    protected function init()
    {
        if ('login' != $this->moduleParams['cabinet']) {
            $users = $this->core->getUsers();
            $user = $users->getCurrent();
            if (null === $user) {
                $this->core->riseError(301, 'Must login', $this->params->sectionPath . '?cabinet=login');
            }
            $this->userId = (int) $user['Id'];
            unset($user);
            unset($users);
        } else {
            //there is no any actions, so nothing to disable
        }
    }
    
    /**
     * @param int $statusId
     * @return array
     */
    public function getStatus($statusId)
    {
        return $this->statuses[$statusId];
    }
    
    public function getItems()
    {
        if (null !== $this->items) {
            return $this->items;
        }
        
        
        $sqlBase =  ' FROM ' . C_DB_TABLE_PREFIX . 'shop_orders' . 
                    ' WHERE UserId=' . $this->userId . 
                    ' AND Status>0';
        $sql =  'SELECT *' . 
                $sqlBase . 
                ' ORDER BY DateCreate DESC' . 
                ' LIMIT ' . (($this->params->page - 1) * $this->params->pageSize) . 
                    ', ' . $this->params->pageSize;
        
        $this->itemsCount = $this->db->getValue('SELECT COUNT(*) AS Cnt' . $sqlBase, 'Cnt');
        if (0 < $this->itemsCount) {
            $this->items = $this->db->getItems($sql);
            foreach ($this->items as &$item) {
                $status = $this->getStatus($item['Status']);
                $item['StatusTitle'] = $status['Title'];
                $item['StatusDate'] = $item[$status['DateField']];
                $item['Elements'] = $this->getItemElements($item['Id']);
            }
            unset($item);
        } else {
            $this->items = array();
        }
        return $this->items;
    }
    
    /**
     * Gets order items.
     * @return array
     */
    protected function getItemElements($itemId) {
        $sql =  'SELECT i.*, oi.ItemsCount, c.Alias AS CategoryAlias, c.Title AS CategoryTitle' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'shop_orders_items AS oi' . 
                ' LEFT JOIN ' . C_DB_TABLE_PREFIX . 'shop_items AS i ON oi.ItemId=i.Id' . 
                ' LEFT JOIN ' . C_DB_TABLE_PREFIX . 'shop_categories AS c ON i.CategoryId=c.Id' . 
                ' WHERE oi.OrderId=' . $itemId . 
                ' ORDER BY oi.Id';
        return $this->db->getItems($sql);
    }
}
