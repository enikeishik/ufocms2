<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\Shop;

/**
 * Order's statuses
 */
trait OrdersStatuses
{
    /**
     * Status id and date field in DB.
     * @var array
     */
    protected $statuses = array( 
        0 => array('Title' => 'Инициирован',    'DateField' => 'DateInit',      'Admin' => false), 
        1 => array('Title' => 'Оформлен',       'DateField' => 'DateCreate',    'Admin' => false), 
        2 => array('Title' => 'Оплачен',        'DateField' => 'DatePaid',      'Admin' => true), 
        3 => array('Title' => 'Собран',         'DateField' => 'DateEquip',     'Admin' => true), 
        4 => array('Title' => 'Отправлен',      'DateField' => 'DateSend',      'Admin' => true), 
        9 => array('Title' => 'Закрыт',         'DateField' => 'DateClosed',    'Admin' => true)
    );
    
    /**
     * 
     * @return array
     */
    public function getStatuses()
    {
        $items = array();
        foreach ($this->statuses as $key => $val) {
            $items[] = array('Value' => $key, 'Title' => $val['Title']);
        }
        return $items;
    }
    
    /**
     * @param int $statusId
     * @return array
     */
    public function getStatus($statusId)
    {
        return $this->statuses[$statusId];
    }
    
    /**
     * @param int $statusId
     * @return array|null
     */
    public function getNextStatus($statusId)
    {
        $found = false;
        foreach ($this->statuses as $s => $sd) {
            if ($statusId == $s) {
                $found = true;
                continue;
            }
            if ($found) {
                return array('Status' => $s, 'Data' => $this->statuses[$s]);
            }
        }
        return null;
    }
}
