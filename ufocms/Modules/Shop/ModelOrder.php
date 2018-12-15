<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\Shop;

use Ufocms\Frontend\DIObject;
use Ufocms\Frontend\Container;

/**
 * Order model
 */
class ModelOrder extends DIObject
{
    /**
     * Lifetime of uncreated order.
     */
    const ORDER_LIFETIME = 31536000; //3600 * 24 * 365;
    
    /**
     * Order status value after creating.
     */
    const ORDER_CREATED_STATUS = 1;
    
    /**
     * Max quantity of one item in order.
     */
    const ITEMS_COUNT_LIMIT = 100;
    
    /**
     * Error message.
     */
    const ERR_ORDER_CREATE = 'Error while initiate order';
    
    /**
     * @var array
     */
    protected $module = null;
    
    /**
     * @var \Ufocms\Frontend\Debug
     */
    protected $debug = null;
    
    /**
     * Ссылка на объект конфигурации.
     * @var \Ufocms\Frontend\Config
     */
    protected $config = null;
    
    /**
     * @var \Ufocms\Frontend\Params
     */
    protected $params = null;
    
    /**
     * @var \Ufocms\Frontend\Db
     */
    protected $db = null;
    
    /**
     * @var \Ufocms\Frontend\Core
     */
    protected $core = null;
    
    /**
     * @var \Ufocms\Frontend\Tools
     */
    protected $tools = null;
    
    /**
     * Availabe module level parameters
     * @var array
     */
    protected $moduleParams = null;
    
    /**
     * Used as flag, indicate that data cached in $this->order expired and need to be rerquested in $this->getOrder().
     * @var boolean
     */
    protected $expired = false;
    
    /**
     * User id or token;
     * @var int|string
     */
    protected $user = null;
    
    /**
     * Current order data.
     * @var array
     */
    protected $order = null;
    
    /**
     * @var mixed
     */
    protected $actionResult = null;
    
    /**
     * Распаковка контейнера.
     */
    protected function unpackContainer()
    {
        $this->module =& $this->container->getRef('module');
        $this->debug =& $this->container->getRef('debug');
        $this->params =& $this->container->getRef('params');
        $this->db =& $this->container->getRef('db');
        $this->core =& $this->container->getRef('core');
        $this->config =& $this->container->getRef('config');
        $this->tools =& $this->container->getRef('tools');
        $this->moduleParams =& $this->container->getRef('moduleParams');
    }
    
    /**
     * Initiate $this->user and $this->order.
     * @todo make antiflood for anonymouse users (with user token)
     */
    protected function init() {
        $users = $this->core->getUsers();
        $user = $users->getCurrent();
        if (null !== $user) {
            $this->user = (int) $user['Id'];
        } else {
            if (isset($_COOKIE['token'])) {
                $this->user = $_COOKIE['token'];
            } else {
                $this->user = sha1($_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT'] . microtime());
            }
            setcookie('token', $this->user, time() + ModelOrder::ORDER_LIFETIME, rtrim($this->params->sectionPath, '/'));
        }
        $this->setOrder();
    }
    
    /**
     * Set value for $this->order.
     */
    protected function setOrder() {
        $this->order = $this->getLastIniniatedOrder();
        if (null !== $this->order) {
            return;
        }
        
        if (is_int($this->user)) {
            $sql =  'INSERT INTO ' . C_DB_TABLE_PREFIX . 'shop_orders' . 
                    ' (SectionId, UserId, DateInit)' . 
                    ' VALUES(' . $this->params->sectionId . ', ' . $this->user . ', NOW())';
        } else {
            $sql =  'INSERT INTO ' . C_DB_TABLE_PREFIX . 'shop_orders' . 
                    ' (SectionId, UserToken, DateInit)' . 
                    " VALUES(" . $this->params->sectionId . ", '" . $this->user . "', NOW())";
        }
        if ($this->db->query($sql)) {
            $sql = 'SELECT * FROM ' . C_DB_TABLE_PREFIX . 'shop_orders WHERE Id=' . $this->db->getLastInsertedId();
            $this->order = $this->db->getItem($sql);
        }
    }
    
    /**
     * Gets last initiated (saved in DB, but not formed) order.
     * @return array|null
     */
    protected function getLastIniniatedOrder() {
        $sql =  'SELECT *' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'shop_orders' . 
                ' WHERE Status=0' . 
                ' AND ' . (is_int($this->user) ? 'UserId=' . $this->user : "UserToken='" . $this->user . "'");
        return $this->db->getItem($sql);
    }
    
    /**
     * Gets order data.
     * @return array
     */
    public function getOrder() {
        if ($this->expired) {
            $this->setOrder();
        }
        return $this->order;
    }
    
    /**
     * Gets order items.
     * @return array
     */
    public function getItems() {
        $sql =  'SELECT i.*, oi.ItemsCount, c.Alias AS CategoryAlias, c.Title AS CategoryTitle' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'shop_orders_items AS oi' . 
                ' LEFT JOIN ' . C_DB_TABLE_PREFIX . 'shop_items AS i ON oi.ItemId=i.Id' . 
                ' LEFT JOIN ' . C_DB_TABLE_PREFIX . 'shop_categories AS c ON i.CategoryId=c.Id' . 
                ' WHERE oi.OrderId=' . $this->order['Id'] . 
                ' ORDER BY oi.Id';
        return $this->db->getItems($sql);
    }
    
    /**
     * Gets count of order items.
     * @param int $itemId
     * @return int
     */
    protected function itemsCount($itemId) {
        $sql =  'SELECT ItemsCount' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'shop_orders_items' . 
                ' WHERE SectionId=' . $this->params->sectionId . 
                ' AND OrderId=' . $this->order['Id'] . 
                ' AND ItemId=' . $itemId;
        return $this->db->getValue($sql, 'ItemsCount');
    }
    
    /**
     * Check exisings of order item.
     * @param int $itemId
     * @return boolean
     */
    protected function itemExists($itemId) {
        $sql =  'SELECT COUNT(*) AS Cnt' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'shop_orders_items' . 
                ' WHERE SectionId=' . $this->params->sectionId . 
                ' AND OrderId=' . $this->order['Id'] . 
                ' AND ItemId=' . $itemId;
        return 0 != $this->db->getValue($sql, 'Cnt');
    }
    
    /**
     * Add new item into order items list or increase counter of exising item.
     * @param int $itemId
     * @param int $count = 1
     * @return boolean
     * @todo check items (and categories?) for hidden
     */
    public function addItem($itemId, $count = 1) {
        if ($this->order['Status'] >= self::ORDER_CREATED_STATUS) {
            $this->setActionResult('add', false);
            return;
        }
        if (!is_int($itemId) || !is_int($count) 
            || 0 >= $itemId 
            || 0 >= $count || self::ITEMS_COUNT_LIMIT < $count) {
            $this->setActionResult('add', false);
            return;
        }
        if (!$this->itemExists($itemId)) {
            $sql =  'INSERT INTO ' . C_DB_TABLE_PREFIX . 'shop_orders_items' . 
                    ' (SectionId, OrderId, ItemId, ItemsCount)' . 
                    ' VALUES(' . $this->params->sectionId . 
                    ',' . $this->order['Id'] . 
                    ',' . $itemId . 
                    ',' . $count . ')';
        } else {
            $sql =  'UPDATE ' . C_DB_TABLE_PREFIX . 'shop_orders_items' . 
                    ' SET ItemsCount=ItemsCount+1' . 
                    ' WHERE SectionId=' . $this->params->sectionId . 
                    ' AND OrderId=' . $this->order['Id'] . 
                    ' AND ItemId=' . $itemId;
        }
        $this->setActionResult('add', $this->db->query($sql));
    }
    
    /**
     * Decrease counter of item in order, o remove item from order list if item is last.
     * @param int $itemId
     * @return boolean
     */
    public function removeItem($itemId) {
        if ($this->order['Status'] >= self::ORDER_CREATED_STATUS) {
            $this->setActionResult('remove', false);
            return;
        }
        if (!is_int($itemId) || 0 >= $itemId) {
            $this->setActionResult('remove', false);
            return;
        }
        if (1 == $this->itemsCount($itemId)) {
            $sql =  'DELETE FROM ' . C_DB_TABLE_PREFIX . 'shop_orders_items' . 
                    ' WHERE SectionId=' . $this->params->sectionId . 
                    ' AND OrderId=' . $this->order['Id'] . 
                    ' AND ItemId=' . $itemId;
        } else {
            $sql =  'UPDATE ' . C_DB_TABLE_PREFIX . 'shop_orders_items' . 
                    ' SET ItemsCount=ItemsCount-1' .
                    ' WHERE SectionId=' . $this->params->sectionId . 
                    ' AND OrderId=' . $this->order['Id'] . 
                    ' AND ItemId=' . $itemId;
        }
        $this->setActionResult('remove', $this->db->query($sql));
    }
    
    /**
     * Save contact information and comment in order.
     * @param string $address
     * @param string $email
     * @param string $phone
     * @param string $comment
     * @return boolean
     */
    public function precreate($address, $email, $phone, $comment) {
        if ($this->order['Status'] >= self::ORDER_CREATED_STATUS) {
            $this->setActionResult('confirm', false);
            return;
        }
        $sql =  'UPDATE ' . C_DB_TABLE_PREFIX . 'shop_orders' . 
                " SET Address='" . $this->db->addEscape($address) . "'," . 
                    " Email='" . $this->db->addEscape($email) . "'," . 
                    " Phone='" . $this->db->addEscape($phone) . "'," . 
                    " Comment='" . $this->db->addEscape($comment) . "'" . 
                ' WHERE SectionId=' . $this->params->sectionId . 
                ' AND Id=' . $this->order['Id'];
        $this->expired = true;
        $this->setActionResult('confirm', $this->db->query($sql));
    }
    
    /**
     * Set order flag to 'order created' and current timestamp.
     * @return boolean
     */
    public function create() {
        if ($this->order['Status'] >= self::ORDER_CREATED_STATUS) {
            $this->setActionResult('send', false);
            return;
        }
        $sql =  'UPDATE ' . C_DB_TABLE_PREFIX . 'shop_orders' . 
                ' SET Status=' . self::ORDER_CREATED_STATUS . ',' . 
                ' DateCreate=NOW()' . 
                ' WHERE SectionId=' . $this->params->sectionId . 
                ' AND Id=' . $this->order['Id'];
        $this->expired = true;
        if ($this->db->query($sql)) {
            $this->sendNotify();
            $this->actionResult = true;
            $this->setActionResult('send', true);
            setcookie('token', $this->user, time() - self::ORDER_LIFETIME, rtrim($this->params->sectionPath, '/'));
            return;
        } else {
            $this->sendNotify($this->db->getError());
            $this->setActionResult('send', false);
            $this->actionResult = false;
            return;
        }
    }
    
    /**
     * Send error notify to administrator.
     * @todo send notify to administration of the shop
     * @todo create field AdminEmail in DB
     */
    protected function sendNotify($error = null)
    {
        
    }
    
    /**
     * Remove all items from order list and delete the order.
     * @return boolean
     */
    public function clear() {
        if ($this->order['Status'] >= self::ORDER_CREATED_STATUS) {
            $this->setActionResult('clear', false);
            return;
        }
        $sql1 = 'DELETE FROM ' . C_DB_TABLE_PREFIX . 'shop_orders_items' . 
                ' WHERE SectionId=' . $this->params->sectionId . 
                ' AND OrderId=' . $this->order['Id'];
        $sql2 = 'DELETE FROM ' . C_DB_TABLE_PREFIX . 'shop_orders' . 
                ' WHERE SectionId=' . $this->params->sectionId . 
                ' AND Id=' . $this->order['Id'];
        $this->setActionResult('clear', $this->db->query($sql1) && $this->db->query($sql2));
        setcookie('token', $this->user, time() - self::ORDER_LIFETIME, rtrim($this->params->sectionPath, '/'));
    }
    
    /**
     * @return mixed
     */
    public function getActionResult()
    {
        return $this->actionResult;
    }
    
    /**
     * Set $actionResult from outside.
     * @param string $action
     * @param bool $result
     * @param array $options 
     */
    public function setActionResult($action, $result, array $options = null)
    {
        $this->actionResult = array(
            'action'    => $action, 
            'request'   => true, 
            'result'    => $result, 
            'options'   => $options, 
        );
    }
    
    /**
     * Set $actionResult from outside for bad request.
     */
    public function badActionRequest($action, array $options = null)
    {
        $this->setActionResult($action, null, $options);
    }
}
