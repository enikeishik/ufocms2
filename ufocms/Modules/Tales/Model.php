<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\Tales;

/**
 * Main module model
 */
class Model extends \Ufocms\Modules\Model //implements IModel
{
    /**
     * Инициализация параметров и бизнес логики.
     */
    protected function init()
    {
        if (null === $this->moduleParams['itemId']) {
            $this->moduleParams['itemId'] = 0;
        } else {
            //redirect to item alias
            $itemAlias = $this->getItemAlias($this->moduleParams['itemId']);
            if (null === $itemAlias) {
                $this->core->riseError(404, 'Item not exists');
            }
            $this->core->riseError(301, 'Use alias', $this->params->$sectionPath . $itemAlias);
        }
        
        if (null !== $this->moduleParams['alias']) {
            $this->moduleParams['itemId'] = $this->getItemIdByAlias($this->moduleParams['alias']);
            if (null === $this->moduleParams['itemId']) {
                $this->core->riseError(404, 'Item not exists');
            }
            $this->params->itemId = $this->moduleParams['itemId'];
        }
    }
    
    public function getSettings()
    {
        if (null !== $this->settings) {
            return $this->settings;
        }
        $sql =  'SELECT *' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'tales_sections' . 
                ' WHERE SectionId=' . $this->params->sectionId;
        $this->settings = $this->db->getItem($sql);
        if (null === $this->moduleParams['pageSize']) {
            $this->params->pageSize = $this->settings['PageLength'];
        } else {
            if ($this->moduleParams['pageSize'] < $this->config->pageSizeMin
            || $this->moduleParams['pageSize'] > $this->config->pageSizeMax) {
                $this->params->pageSize = $this->settings['PageLength'];
            }
        }
        return $this->settings;
    }
    
    public function getItems()
    {
        $sqlBase =  ' FROM ' . C_DB_TABLE_PREFIX . 'tales AS i' . 
                    ' INNER JOIN ' . C_DB_TABLE_PREFIX . 'sections AS s ON i.SectionId=s.id' . 
                    ' WHERE i.SectionId=' . $this->params->sectionId . 
                    ' AND i.IsHidden=0';
        switch ($this->settings['Orderby']) {
            case 0:
                $sqlOrder = 'OrderNumber';
                break;
            case 1:
                $sqlOrder = 'OrderNumber DESC';
                break;
            case 2:
                $sqlOrder = 'Title';
                break;
            case 3:
                $sqlOrder = 'Title DESC';
                break;
            case 4:
                $sqlOrder = 'DateCreate';
                break;
            case 5:
                $sqlOrder = 'DateCreate DESC';
                break;
            case 6:
                $sqlOrder = 'Url';
                break;
            case 7:
                $sqlOrder = 'Url DESC';
                break;
            case 8:
                $sqlOrder = 'ViewedCnt';
                break;
            case 9:
                $sqlOrder = 'ViewedCnt DESC';
                break;
            default:
                $sqlOrder = 'OrderNumber';
        }
        $sql =  'SELECT i.*,s.path,s.indic' . 
                $sqlBase . 
                ' ORDER BY ' . $sqlOrder . 
                ' LIMIT ' . (($this->params->page - 1) * $this->params->pageSize) . 
                    ', ' . $this->params->pageSize;
        $this->itemsCount = $this->db->getValue('SELECT COUNT(*) AS Cnt' . $sqlBase, 'Cnt');
        if (0 < $this->itemsCount) {
            $this->items = $this->db->getItems($sql);
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
        
        $sql =  'SELECT *' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'tales' . 
                ' WHERE Id=' . $this->params->itemId . 
                ' AND IsHidden=0';
        $this->item = $this->db->getItem($sql);
        
        //обновляем данные по количеству просмотров
        $this->updateViewCount($this->item['Id'], $this->item['UserId']);
        
        return $this->item;
    }
    
    protected function updateViewCount($itemId, $itemUserId)
    {
        /*
        //если у элемента UserId=0 то увеличиваем счетчик просмотров независимо от смотрящего
        if (0 != $itemUserId) {
            //если смотрящий является владельцем элемента, выходим без увеличения счетчика
            $currentUser = $this->core->getUsers()->getCurrent();
            if (null !== $currentUser && $itemUserId == $currentUser['Id']) {
                return;
            }
        }
        */
        $sql = 'UPDATE ' . C_DB_TABLE_PREFIX . 'tales' . 
               ' SET DateView=NOW(), ViewedCnt=ViewedCnt+1' . 
               ' WHERE Id=' . $itemId;
        $this->db->query($sql);
    }
    
    /**
     * @param int $itemId
     * @return string
     */
    protected function getItemAlias($itemId)
    {
        $sql =  'SELECT Url AS Alias' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'tales' . 
                ' WHERE IsHidden=0' . 
                ' AND Id=' . $itemId;
        return $this->db->getValue($sql, 'Alias');
    }
    
    /**
     * @param string $alias
     * @return int|null
     */
    protected function getItemIdByAlias($alias) {
        $sql =  'SELECT Id' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'tales' . 
                ' WHERE SectionId=' . $this->params->sectionId . 
                ' AND IsHidden=0' . 
                " AND Url='" . $this->db->addEscape($alias) . "'";
        return $this->db->getValue($sql, 'Id');
    }
}
