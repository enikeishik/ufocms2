<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\News2;

/**
 * Main module model
 */
class Model extends \Ufocms\Modules\Model //implements IModel
{
    public function getSettings()
    {
        if (null !== $this->settings) {
            return $this->settings;
        }
        $sql =  'SELECT *' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'news2_sections' . 
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
    
    //Списки элементов
    
    public function getItems()
    {
        $sqlBase =  ' FROM ' . C_DB_TABLE_PREFIX . 'news2 AS n' . 
                    ' INNER JOIN ' . C_DB_TABLE_PREFIX . 'sections AS s ON n.SectionId=s.id' . 
                    ' WHERE (' . 
                        'n.SectionId=' . $this->params->sectionId . 
                        ' OR n.Id IN(' . 
                            'SELECT ItemId FROM ' . C_DB_TABLE_PREFIX . 'news2_ns WHERE AnotherSectionId=' . $this->params->sectionId . 
                        ')' . 
                    ')' . 
                    ' AND n.IsHidden=0 AND n.DateCreate<=NOW()';
        if ($this->moduleParams['isRss'] || $this->moduleParams['isYandex'] || $this->moduleParams['isYaDzen']) {
            $sqlBase .= ' AND n.IsRss=1' . 
                        ' AND n.DateCreate>DATE_ADD(NOW(), INTERVAL - ' . $this->settings['RssExpireOffset'] . ' MINUTE)';
        }
        $sqlOrder = 'n.DateCreate DESC';
        $sql =  'SELECT n.*,s.path,s.indic' . 
                $sqlBase;
        if ($this->moduleParams['isRss'] || $this->moduleParams['isYandex'] || $this->moduleParams['isYaDzen']) {
            $sql .= ' ORDER BY DateCreate DESC' . 
                    ' LIMIT ' . ($this->params->pageSize * 2);
        } else {
            $sql .= ' ORDER BY ' . $sqlOrder . 
                    ' LIMIT ' . (($this->params->page - 1) * $this->params->pageSize) . 
                        ', ' . $this->params->pageSize;
        }
        $this->itemsCount = $this->db->getValue('SELECT COUNT(*) AS Cnt' . $sqlBase, 'Cnt');
        if (0 < $this->itemsCount) {
            $this->items = $this->db->getItems($sql);
        } else {
            $this->items = array();
        }
        return $this->items;
    }
    
    public function getItemsByTag()
    {
        $sqlBase =  ' FROM ' . C_DB_TABLE_PREFIX . 'news2 AS n' . 
                    ' INNER JOIN ' . C_DB_TABLE_PREFIX . 'sections AS s ON n.SectionId=s.id' . 
                    ' INNER JOIN ' . C_DB_TABLE_PREFIX . 'news2_nt AS nt ON n.Id=nt.ItemId' . 
                    ' LEFT JOIN ' . C_DB_TABLE_PREFIX . 'news2_ns AS ns ON n.Id=ns.ItemId' . 
                    ' WHERE nt.TagId=' . $this->moduleParams['tagId'] . 
                    ' AND (n.SectionId=' . $this->params->sectionId . ' OR ns.AnotherSectionId=' . $this->params->sectionId . ')';
                        ' AND n.IsHidden=0 AND n.DateCreate<=NOW()';
        $sqlOrder = 'n.DateCreate DESC, n.Id DESC';
        $sql =  'SELECT DISTINCT n.*,s.path,s.indic' . 
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
    
    public function getItemsByDate()
    {
        $sqlBase =  ' FROM ' . C_DB_TABLE_PREFIX . 'news2 AS n' . 
                    ' INNER JOIN ' . C_DB_TABLE_PREFIX . 'sections AS s ON n.SectionId=s.id' . 
                    ' LEFT JOIN ' . C_DB_TABLE_PREFIX . 'news2_ns AS ns ON n.Id=ns.ItemId' . 
                    ' WHERE (n.SectionId=' . $this->params->sectionId . ' OR ns.AnotherSectionId=' . $this->params->sectionId . ')' . 
                        ' AND n.IsHidden=0 AND n.DateCreate<=NOW()' . 
                        " AND n.DateCreate>='" . $this->moduleParams['date'] . " 00:00:00'" . 
                        " AND n.DateCreate<='" . $this->moduleParams['date'] . " 23:59:59'";
        $sqlOrder = 'n.DateCreate DESC, n.Id DESC';
        $sql =  'SELECT DISTINCT n.*,s.path,s.indic' . 
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
    
    //Данные элемента
    
    public function getItem()
    {
        if (null !== $this->item) {
            return $this->item;
        }
        
        $sql =  'SELECT *' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'news2' . 
                ' WHERE Id=' . $this->params->itemId . 
                ' AND IsHidden=0';
        $this->item = $this->db->getItem($sql);
        $sql =  'SELECT s.path,s.indic,s.title' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'sections AS s' . 
                ' INNER JOIN ' . C_DB_TABLE_PREFIX . 'news2_ns AS ns ON s.id=ns.AnotherSectionId' . 
                ' WHERE ns.ItemId=' . $this->params->itemId . ' AND s.isenabled!=0';
        $this->item = array_merge($this->item, array('AnotherSections' => $this->db->getItems($sql)));
        
        //обновляем данные по количеству просмотров
        $this->updateViewCount($this->item['Id'], $this->item['UserId']);
        
        return $this->item;
    }
    
    protected function updateViewCount($itemId, $itemUserId)
    {
        //если у элемента UserId=0 то увеличиваем счетчик просмотров независимо от смотрящего
        if (0 != $itemUserId) {
            //если смотрящий является владельцем элемента, выходим без увеличения счетчика
            $currentUser = $this->core->getUsers()->getCurrent();
            if (null !== $currentUser && $itemUserId == $currentUser['Id']) {
                return;
            }
        }
        $sql = 'UPDATE ' . C_DB_TABLE_PREFIX . 'news2' . 
               ' SET DateView=NOW(), ViewedCnt=ViewedCnt+1' . 
               ' WHERE Id=' . $itemId;
        $this->db->query($sql);
    }
    
    /**
     * Get item related tags
     * @param int $itemId = null
     * @return array|null
     */
    public function getItemTags($itemId = null)
    {
        if (null === $itemId) {
            $itemId = $this->params->itemId;
        }
        $sql =  'SELECT t.Id, t.Tag' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'news2_tags AS t' . 
                ' INNER JOIN ' . C_DB_TABLE_PREFIX . 'news2_nt AS nt ON t.Id=nt.TagId' . 
                ' WHERE nt.ItemId=' . $itemId . ' AND t.IsDisabled=0';
        return $this->db->getItems($sql);
    }
    
    /**
     * Get related (by tags) items
     * @param int $count = 5
     * @param int $itemId = null
     * @return array|null
     */
    public function getSimilarItems($count = 5, $itemId = null)
    {
        if (null === $itemId) {
            $itemId = $this->params->itemId;
        }
        //TODO: попробовать разбить на два запроса (внут. и внешн.) и выполнять их отдельно
        $sql =  'SELECT n.*' . 
                ',COUNT(*) AS Cnt' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'news2 AS n' . 
                ' INNER JOIN ' . C_DB_TABLE_PREFIX . 'news2_nt AS nt ON n.Id=nt.ItemId' . 
                ' WHERE n.SectionId=' . $this->params->sectionId . ' AND n.IsHidden=0' . 
                ' AND nt.TagId IN (' . 
                    'SELECT TagId' . 
                    ' FROM ' . C_DB_TABLE_PREFIX . 'news2_nt' . 
                    ' WHERE ItemId=' . $itemId . 
                    ') AND ItemId!=' . $itemId . 
                ' GROUP BY ItemId' . 
                ' ORDER BY Cnt DESC, n.DateCreate DESC' . 
                ' LIMIT ' . $count;
        return $this->db->getItems($sql);
    }
    
    //Действия
    
    /**
     * Добавление элемента.
     * @return bool
     */
    public function add()
    {
        echo 'add@@@add';
        $this->actionResult = true;
    }
}
