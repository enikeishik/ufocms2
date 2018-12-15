<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\Auctions;

/**
 * Main module model
 */
class Model extends \Ufocms\Modules\Model //implements IModel
{
    /**
     * @var array|null
     */
    protected $currentUser = null;
    
    /**
     * @var int
     */
    protected $currentTime = null;
    
    
    protected function init()
    {
        if ('login' != $this->moduleParams['requestType']) {
            $this->currentUser = $this->core->getUsers()->getCurrent();
            if (null === $this->currentUser) {
                $this->core->riseError(301, 'Must login', $this->params->sectionPath . '?type=login');
            }
            //запрещаем кэширование страницы элемента
            //логичнее было бы запретить кэширование информера и действия
            //но информер и действие вызываются через ? 
            //pathRaw у них тот же что и у страницы элемента
            if (0 != $this->params->itemId) {
                $this->config->cacheForbidden = true;
            }
        } else {
            //to prevent any actions
            $this->params->action = null;
            $this->moduleParams['action'] = null;
            $this->moduleParams['actionId'] = null;
            $this->params->itemId = 0;
        }
        $this->currentTime = time();
    }
    
    public function getSettings()
    {
        if (null !== $this->settings) {
            return $this->settings;
        }
        $sql =  'SELECT *' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'auctions_settings' . 
                ' WHERE SectionId=' . $this->params->sectionId;
        $this->settings = $this->db->getItem($sql);
        $this->params->pageSize = $this->settings['PageLength'];
        return $this->settings;
    }
    
    public function getItems()
    {
        if (null !== $this->items) {
            return $this->items;
        }
        $this->getSettings();
        $now = date('Y-m-d H:i:s', $this->currentTime);
        $sqlBase =  ' FROM ' . C_DB_TABLE_PREFIX . 'auctions AS i' . 
                    ' WHERE SectionId=' . $this->params->sectionId . 
                    ' AND IsDisabled=0' . 
                    " AND DatePublicate<='" . $now . "'";
        $sqlOrder = 'DateStart DESC';
        $sql =  'SELECT *,' . 
                ' (SELECT MAX(DateCreate) FROM ' . C_DB_TABLE_PREFIX . 'auctions_log WHERE AuctionId=i.Id) AS DateStep' . 
                $sqlBase . 
                ' ORDER BY ' . $sqlOrder . 
                ' LIMIT ' . (($this->params->page - 1) * $this->params->pageSize) . 
                        ', ' . $this->params->pageSize;
        $this->itemsCount = $this->db->getValue('SELECT COUNT(*) AS Cnt' . $sqlBase, 'Cnt');
        if (0 < $this->itemsCount) {
            $this->items = $this->db->getItems($sql);
            //проверяем не истекло ли время аукциона или ставки
            $closedIds = [];
            foreach ($this->items as &$item) {
                if (!$item['IsClosed'] 
                && $this->checkClosed($item['DateStop'], $item['DateStep'], $item['StepTime'])) {
                    $closedIds[] = $item['Id'];
                    $item['IsClosed'] = 1;
                }
            }
            unset($item);
            if (0 < count($closedIds)) {
                $this->close($closedIds);
            }
        } else {
            $this->items = array();
        }
        return $this->items;
    }
    
    public function getItem()
    {
        if (null !== $this->item) {
            return $this->item;
        }
        $now = date('Y-m-d H:i:s', $this->currentTime);
        //two LEFT JOIN to gets only one (with last DateCreate) record from log
        $sql =  'SELECT i.*, l.UserId, l.DateCreate AS DateStep,' . 
                "DateStart<='" . $now . "' AS IsStarted" . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'auctions AS i' . 
                ' LEFT JOIN ' . C_DB_TABLE_PREFIX . 'auctions_log AS l ON i.Id=l.AuctionId' . 
                ' LEFT JOIN ' . C_DB_TABLE_PREFIX . 'auctions_log AS l2 ON i.Id=l2.AuctionId AND l.DateCreate<l2.DateCreate' . 
                ' WHERE i.Id=' . $this->params->itemId . 
                ' AND i.IsDisabled=0' . 
                " AND i.DatePublicate<='" . $now . "'" . 
                ' AND l2.DateCreate IS NULL';
        $this->item = $this->db->getItem($sql);
        //проверяем не истекло ли время аукциона или ставки
        if (!$this->item['IsClosed'] 
        && $this->checkClosed($this->item['DateStop'], $this->item['DateStep'], $this->item['StepTime'])) {
            $this->close([$this->params->itemId]);
            $this->item['IsClosed'] = 1;
        }
        //обновляем данные по количеству просмотров
        $this->updateViewCount($this->params->itemId);
        return $this->item;
    }
    
    /**
     * @param string(datetime) $dateStep
     * @param int $stepTime
     * @return bool
     */
    protected function checkClosed($dateStop, $dateStep, $stepTime)
    {
        return  $this->currentTime > strtotime($dateStop) 
                || (null !== $dateStep && ($this->currentTime - strtotime($dateStep)) > $stepTime);
    }
    
    /**
     * @param int $itemId
     * @return bool
     */
    protected function updateViewCount($itemId)
    {
        $sql =  'UPDATE ' . C_DB_TABLE_PREFIX . 'auctions' . 
                ' SET DateView=NOW(), ViewedCnt=ViewedCnt+1' . 
                ' WHERE Id=' . $itemId;
        $this->db->query($sql);
    }
    
    /**
     * Close auction.
     * @param array $itemsIds
     */
    protected function close(array $itemsIds)
    {
        $sql =  'UPDATE ' . C_DB_TABLE_PREFIX . 'auctions' . 
                ' SET IsClosed=1' . 
                ' WHERE Id IN (' . implode(',', $itemsIds) . ')';
        $this->db->query($sql);
    }
    
    /**
     * Auction step action.
     */
    public function step()
    {
        $this->actionResult = array(
            'success' => false, 
            'error'   => '', 
        );
        
        $now = date('Y-m-d H:i:s');
        
        //get info
        $sql =  'SELECT Step, PriceStop, PriceCurrent,' . 
                ' (SELECT UserId' . 
                    ' FROM ' . C_DB_TABLE_PREFIX . 'auctions_log' . 
                    ' WHERE AuctionId=i.Id' . 
                    ' AND DateCreate=(' . 
                        'SELECT MAX(DateCreate)' . 
                        ' FROM ' . C_DB_TABLE_PREFIX . 'auctions_log' . 
                        ' WHERE AuctionId=i.Id' . 
                    ')' . 
                ') AS UserId' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'auctions AS i' . 
                ' WHERE Id=' . $this->params->itemId . 
                ' AND IsDisabled=0' . 
                ' AND IsClosed=0' . 
                " AND DateStart<='" . $now . "'" . 
                " AND DateStop>'" . $now . "'";
        $item = $this->db->getItem($sql);
        if (null === $item) {
            $this->actionResult['error'] = 'Item not found';
            return;
        }
        
        if ($this->currentUser['Id'] == $item['UserId']) {
            $this->actionResult['error'] = 'User already make step';
            return;
        }
        
        $priceCurrent = $item['PriceCurrent'];
        $priceNew = $priceCurrent + $item['Step'];
        
        //update PriceCurrent immediately, other users may ask for it
        if (!$this->setStep($priceNew)) {
            return;
        }
        
        //write log
        $sql =  'INSERT INTO ' . C_DB_TABLE_PREFIX . 'auctions_log' . 
                ' (UserId,SectionId,AuctionId,DateCreate,PriceNew,IsSuccess,Info)' . 
                ' VALUES(' . 
                $this->currentUser['Id'] . ',' . 
                $this->params->sectionId . ',' . 
                $this->params->itemId . ',' . 
                "'" . $now . "'," . 
                $priceNew . ',' . 
                '0,' . 
                "'" . $this->db->addEscape(json_encode($_SERVER)) . "'" . 
                ')';
        if (!$this->db->query($sql)) {
            $this->actionResult['error'] = 'DB error on log insert: ' . $this->db->getError();
            //rollback
            $this->setStep($priceCurrent);
            return;
        }
        
        //check if alone
        $sql =  'SELECT Id,IsSuccess' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'auctions_log' . 
                ' WHERE AuctionId=' . $this->params->itemId . 
                ' AND PriceNew=' . $priceNew . 
                ' ORDER BY Id';
        $items = $this->db->getItems($sql);
        if (null === $items || 1 > count($items)) {
            $this->actionResult['error'] = 'Log records not found';
            //rollback
            $this->setStep($priceCurrent);
            return;
        }
        if (1 == count($items)) {
            if (!$items[0]['IsSuccess']) {
                if ($this->setStepSuccess($items[0]['Id'])) {
                    $this->actionResult['success'] = true;
                } else {
                    //rollback
                    $this->setStep($priceCurrent);
                }
            }
        } else {
            //search fo IsSuccess item
            foreach ($items as $itm) {
                if ($itm['IsSuccess']) {
                    $this->actionResult['success'] = true;
                    return;
                }
            }
            //if no IsSuccess item, make it first item (ORDER BY Id)
            if ($this->setStepSuccess($items[0]['Id'])) {
                $this->actionResult['success'] = true;
            } else {
                //rollback
                $this->setStep($priceCurrent);
            }
        }
        
        if ($this->actionResult['success'] && $item['PriceStop'] >= $priceNew) {
            $this->close([$this->params->itemId]);
        }
    }
    
    /**
     * @param int $logItemId
     * @return bool
     */
    protected function setStepSuccess($logItemId)
    {
        $sql =  'UPDATE ' . C_DB_TABLE_PREFIX . 'auctions_log' . 
                ' SET IsSuccess=1' . 
                ' WHERE Id=' . $logItemId;
        if ($this->db->query($sql)) {
            return true;
        } else {
            $this->actionResult['error'] = 'DB error on log update: ' . $this->db->getError();
            return false;
        }
    }
    
    /**
     * @param string $dateStep
     * @param int $priceNew
     * @return bool
     */
    protected function setStep($priceNew)
    {
        $sql =  'UPDATE ' . C_DB_TABLE_PREFIX . 'auctions' . 
                ' SET' . 
                ' PriceCurrent=' . $priceNew . 
                ' WHERE Id=' . $this->params->itemId;
        if ($this->db->query($sql)) {
            return true;
        } else {
            $this->actionResult['error'] = 
                ('' == $this->actionResult['error'] ? '' : PHP_EOL) . 
                'DB error on item update: ' . $this->db->getError();
            return false;
        }
    }
}
