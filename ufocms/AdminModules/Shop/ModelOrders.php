<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\Shop;

/**
 * News module model class
 */
class ModelOrders extends \Ufocms\AdminModules\Model
{
    use OrdersStatuses;
    
    /**
     * @see parent
     */
    protected function init()
    {
        parent::init();
        $this->itemsTable = 'shop_orders';
        $this->itemDisabledField = '';
        $this->defaultSort = 'DateInit DESC';
        $this->canCreateItems = false;
        $this->canDeleteItems = false;
        $this->config->registerFormAction('status');
        $this->config->registerAction('setstatus');
        $this->config->registerMakeAction('setstatus');
        $this->params->actionUnsafe = false;
    }
    
    protected function setFields()
    {
        $this->fields = array(
            array('Type' => 'int',          'Name' => 'Id',             'Value' => 0,   'Title' => 'id',                'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => false),
            array('Type' => 'list',         'Name' => 'SectionId',      'Value' => 0,   'Title' => 'Раздел',            'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => false,    'Items' => 'getSections',   'Unchange' => true),
            array('Type' => 'int',          'Name' => 'UserId',         'Value' => 0,   'Title' => 'UserId',            'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => false),
            array('Type' => 'text',         'Name' => 'UserToken',      'Value' => '',  'Title' => 'UserToken',         'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => false),
            array('Type' => 'list',         'Name' => 'Status',         'Value' => 0,   'Title' => 'Статус',            'Filter' => true,   'Show' => true,     'Sort' => true,     'Edit' => false,    'Items' => 'getStatuses'),
            array('Type' => 'datetime',     'Name' => 'DateInit',       'Value' => '',  'Title' => 'Инициирован',       'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => false),
            array('Type' => 'datetime',     'Name' => 'DateCreate',     'Value' => '',  'Title' => 'Оформлен',          'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => false),
            array('Type' => 'datetime',     'Name' => 'DatePaid',       'Value' => '',  'Title' => 'Оплачен',           'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => false),
            array('Type' => 'datetime',     'Name' => 'DateEquip',      'Value' => '',  'Title' => 'Собран',            'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => false),
            array('Type' => 'datetime',     'Name' => 'DateSend',       'Value' => '',  'Title' => 'Отправлен',         'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => false),
            array('Type' => 'datetime',     'Name' => 'DateClosed',     'Value' => '',  'Title' => 'Закрыт',            'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => false),
            array('Type' => 'text',         'Name' => 'Address',        'Value' => '',  'Title' => 'Адрес',             'Filter' => true,   'Show' => true,     'Sort' => true,     'Edit' => true,     'Unchange' => true),
            array('Type' => 'text',         'Name' => 'Email',          'Value' => '',  'Title' => 'Email',             'Filter' => true,   'Show' => true,     'Sort' => true,     'Edit' => true,     'Unchange' => true),
            array('Type' => 'text',         'Name' => 'Phone',          'Value' => '',  'Title' => 'Телефон',           'Filter' => true,   'Show' => true,     'Sort' => true,     'Edit' => true,     'Unchange' => true),
            array('Type' => 'mediumtext',   'Name' => 'Comment',        'Value' => '',  'Title' => 'Комментарий',       'Filter' => true,   'Show' => true,     'Sort' => false,    'Edit' => true,     'Unchange' => true),
            array('Type' => 'bigtext',      'Name' => 'Report',         'Value' => '',  'Title' => 'Отчет',             'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'mediumtext',   'Name' => 'Notes',          'Value' => '',  'Title' => 'Заметки',           'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
        );
    }
    
    /**
     * @param int $orderId
     * @return array|null
     */
    public function getOrderItems($orderId)
    {
        $sql =  'SELECT oi.ItemsCount, i.*, c.Alias AS CategoryAlias, c.Title AS CategoryTitle' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'shop_orders_items AS oi' . 
                ' LEFT JOIN ' . C_DB_TABLE_PREFIX . 'shop_items AS i ON oi.ItemId=i.Id' . 
                ' LEFT JOIN ' . C_DB_TABLE_PREFIX . 'shop_categories AS c ON i.CategoryId=c.Id' . 
                ' WHERE oi.OrderId=' . $orderId . 
                ' ORDER BY oi.Id';
        return $this->db->getItems($sql);
    }
    
    /**
     * @todo make some cheks
     */
    public function setstatus()
    {
        $status = (int) $_POST['Status'];
        $statusData = $this->statuses[$status];
        $sql =  'UPDATE ' . C_DB_TABLE_PREFIX . 'shop_orders' . 
                ' SET Status=' . $status . ', ' . $statusData['DateField'] . '=NOW()' . 
                ' WHERE Id=' . $this->params->itemId;
        if ($this->db->query($sql)) {
            $this->result = 'updated';
        } else {
            $this->result = 'DB error: ' . $this->db->getError();
            return false;
        }
    }
}
