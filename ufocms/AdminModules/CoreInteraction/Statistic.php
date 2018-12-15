<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\CoreInteraction;

/**
 * Interaction stat common class
 */
class Statistic
{
    /**
     * @var \Ufocms\Frontend\Debug
     */
    protected $debug = null;
    
    /**
     * @var \Ufocms\Frontend\Db
     */
    protected $db = null;
    
    /**
     * @var \Ufocms\Frontend\Core
     */
    protected $core = null;
    
    
    /**
     * @param Db &$db
     * @param Core &$core
     * @param Debug &$debug = null
     */
    public function __construct(&$db, &$core, &$debug = null)
    {
        $this->db     =& $db;
        $this->core   =& $core;
        $this->debug  =& $debug;
    }
    
    public function getPeriods()
    {
        $periods = array();
        $sql = 'SELECT u.Code, p.Id AS PeriodId, p.Period' . 
               ' FROM ' . C_DB_TABLE_PREFIX . 'interaction_stat_periods_units AS u' . 
               ' INNER JOIN ' . C_DB_TABLE_PREFIX . 'interaction_stat_periods AS p ON u.Id=p.UnitId' . 
               ' WHERE p.IsDisabled=0';
        $items = $this->db->getItems($sql);
        foreach ($items as $item) {
            $periods[$item['PeriodId']] = $item;
        }
        unset($items);
        //на всякий случай проверяем нет ли в БД данных с идентификатором 0
        //и если есть, убираем эти данные, 
        //поскольку 0 зарезервирован для статистики за все время
        if (array_key_exists(0, $periods)) {
            $arr_ = array();
            foreach ($periods as $key => $val) {
                if (0 != $key) {
                    $arr_[$key] = $periods[$key];
                }
            }
            $periods = $arr_;
        }
        return $periods;
    }

    public function updateCommentRating($commentId)
    {
        $sql = 'SELECT COUNT(*) AS CntVal, SUM(Rate) AS SumVal' . 
               ' FROM ' . C_DB_TABLE_PREFIX . 'interaction_comments_rates' . 
               ' WHERE IsDisabled=0 AND CommentId=' . $commentId . 
               ' GROUP BY CommentId';
        $item = $this->db->getItem($sql);
        if (null !== $item) {
            $cnt = $item['CntVal'];
            $sum = $item['SumVal'];
        } else {
            $cnt = 0;
            $sum = 0;
        }
        
        $sql = 'UPDATE ' . C_DB_TABLE_PREFIX . 'interaction_comments' . 
               ' SET RatesCnt=' . $cnt . ',Rating=' . $sum . 
               ' WHERE Id=' . $commentId;
        return $this->db->query($sql);
    }
    
    public function updateItemStat($sectionId, $itemId, $postType = 0)
    {
        $stats = array();
        //обсчет статистики за все время
        switch ($postType) {
            case 0:
                //статистика по комментариям
                $sql = 'SELECT COUNT(*) AS CntVal, AVG(CommentStatus) AS AvgVal, MAX(DateCreate) AS DtmVal' . 
                       ' FROM ' . C_DB_TABLE_PREFIX . 'interaction_comments' . 
                       ' WHERE IsDisabled=0 AND SectionId=' . $sectionId . ' AND ItemId=' . $itemId;
                break;
            case 1:
                //статистика по голосам
                $sql = 'SELECT COUNT(*) AS CntVal, AVG(Rate) AS AvgVal, MAX(DateCreate) AS DtmVal' . 
                       ' FROM ' . C_DB_TABLE_PREFIX . 'interaction_rates' . 
                       ' WHERE IsDisabled=0 AND SectionId=' . $sectionId . ' AND ItemId=' . $itemId;
                break;
            default:
                return false;
        }
        $item = $this->db->getItem($sql);
        if (null !== $item) {
            $stats[0]['Cnt'] = $item['CntVal'];
            $stats[0]['Avg'] = is_null($item['AvgVal']) ? 0 : $item['AvgVal'];
            $stats[0]['Dtm'] = is_null($item['DtmVal']) ? '0000-00-00 00:00:00' : $item['DtmVal'];
        }
        
        //обсчет статистики для периодов
        $periods = $this->getPeriods();
        foreach ($periods as $periodId => $period) {
            switch ($postType) {
                case 0:
                    //статистика по комментариям
                    $sql = 'SELECT COUNT(*) AS CntVal, AVG(CommentStatus) AS AvgVal, MAX(DateCreate) AS DtmVal' . 
                           ' FROM ' . C_DB_TABLE_PREFIX . 'interaction_comments' . 
                           ' WHERE IsDisabled=0 AND SectionId=' . $sectionId . ' AND ItemId=' . $itemId . 
                           ' AND DateCreate>=DATE_ADD(NOW(), INTERVAL - ' . $period['Period'] . ' ' . $period['Code'] . ')';
                    break;
                case 1:
                    //статистика по голосам
                    $sql = 'SELECT COUNT(*) AS CntVal, AVG(Rate) AS AvgVal, MAX(DateCreate) AS DtmVal' . 
                           ' FROM ' . C_DB_TABLE_PREFIX . 'interaction_rates' . 
                           ' WHERE IsDisabled=0 AND SectionId=' . $sectionId . ' AND ItemId=' . $itemId . 
                           ' AND DateCreate>=DATE_ADD(NOW(), INTERVAL - ' . $period['Period'] . ' ' . $period['Code'] . ')';
                    break;
            }
            /* DEBUG echo 'LN: ' . __LINE__ . '; F: ' . __FUNCTION__ . '; SQL: ' . $sql . '<br />'; */
            $item = $this->db->getItem($sql);
            if (null !== $item) {
                $stats[$periodId]['Cnt'] = $item['CntVal'];
                $stats[$periodId]['Avg'] = is_null($item['AvgVal']) ? 0 : $item['AvgVal'];
                $stats[$periodId]['Dtm'] = is_null($item['DtmVal']) ? '0000-00-00 00:00:00' : $item['DtmVal'];
            } else {
                $stats[$periodId]['Cnt'] = 0;
                $stats[$periodId]['Avg'] = 0;
                $stats[$periodId]['Dtm'] = '0000-00-00 00:00:00';
            }
        }
        
        $ret = array();
        
        //обновление статистики за все время
        //обновление статистики для периодов
        foreach ($stats as $periodId => $stat) {
            $sql = 'SELECT Id FROM ' . C_DB_TABLE_PREFIX . 'interaction_stat_items' . 
                   ' WHERE SectionId=' . $sectionId . ' AND ItemId=' . $itemId . 
                   ' AND PeriodId=' . $periodId;
            $id = $this->db->getValue($sql, 'Id');
            
            if (0 != $id) {
                switch ($postType) {
                    case 0:
                        $sql = 'UPDATE ' . C_DB_TABLE_PREFIX . 'interaction_stat_items' . 
                               " SET DateComment='" . $stat['Dtm'] . "'," . 
                                    ' DateCommentUpdate=NOW(),' . 
                                    ' CommentsCnt=' . $stat['Cnt'] . ',' . 
                                    " CommentsStatusAvg='" . $stat['Avg'] . "'" . 
                               ' WHERE Id=' . $id;
                        break;
                    case 1:
                        $sql = 'UPDATE ' . C_DB_TABLE_PREFIX . 'interaction_stat_items' . 
                               " SET DateRate='" . $stat['Dtm'] . "'," . 
                                    ' DateRateUpdate=NOW(),' . 
                                    ' RatesCnt=' . $stat['Cnt'] . ',' . 
                                    " Rating='" . $stat['Avg'] . "'" . 
                               ' WHERE Id=' . $id;
                        break;
                }
            } else {
                switch ($postType) {
                    case 0:
                        $sql = 'INSERT INTO ' . C_DB_TABLE_PREFIX . 'interaction_stat_items' . 
                               ' (SectionId,ItemId,PeriodId,DateComment,DateCommentUpdate,CommentsCnt,CommentsStatusAvg)' . 
                               ' VALUES(' . $sectionId . ',' . $itemId . ',' . $periodId . ',' . 
                                        "'" . $stat['Dtm'] . "',NOW()," . $stat['Cnt'] . ",'" . $stat['Avg'] . "')";
                        break;
                    case 1:
                        $sql = 'INSERT INTO ' . C_DB_TABLE_PREFIX . 'interaction_stat_items' . 
                               ' (SectionId,ItemId,PeriodId,DateRate,DateRateUpdate,RatesCnt,Rating)' . 
                               ' VALUES(' . $sectionId . ',' . $itemId . ',' . $periodId . ',' . 
                                        "'" . $stat['Dtm'] . "',NOW()," . $stat['Cnt'] . ",'" . $stat['Avg'] . "')";
                        break;
                }
            }
            $ret[$periodId] = $this->db->query($sql);
        }
        
        //возвращаем значение (ищем была ли ошибка, если нет возвращается true иначе false)
        return (false === array_search(false, $ret));
    }
    
    public function updateUserStat($userId, $postType = 0)
    {
        if (0 == $userId) {
            return false;
        }
        
        $stats = array();
        //обсчет статистики за все время
        switch ($postType) {
            case 0:
                //статистика по комментариям
                $sql = 'SELECT COUNT(*) AS CntVal, AVG(CommentStatus) AS AvgVal, MAX(DateCreate) AS DtmVal' . 
                       ' FROM ' . C_DB_TABLE_PREFIX . 'interaction_comments' . 
                       ' WHERE IsDisabled=0 AND UserId=' . $userId;
                break;
            case 1:
                //статистика по голосам
                $sql = 'SELECT COUNT(*) AS CntVal, AVG(Rate) AS AvgVal, MAX(DateCreate) AS DtmVal' . 
                       ' FROM ' . C_DB_TABLE_PREFIX . 'interaction_rates' . 
                       ' WHERE IsDisabled=0 AND UserId=' . $userId;
                break;
            case 2:
                //статистика по отметкам других комментариев
                $sql = 'SELECT COUNT(*) AS CntVal, AVG(cr.Rate) AS AvgVal, MAX(cr.DateCreate) AS DtmVal' . 
                       ' FROM ' . C_DB_TABLE_PREFIX . 'interaction_comments_rates AS cr' . 
                       ' INNER JOIN ' . C_DB_TABLE_PREFIX . 'interaction_comments AS c ON cr.CommentId=c.Id' . 
                       ' WHERE c.IsDisabled=0 AND cr.IsDisabled=0 AND cr.UserId=' . $userId;
                break;
            case -1:
                //статистика по отметкам других пользователей комментариев зарегистрированного пользователя
                $sql = 'SELECT COUNT(*) AS CntVal, AVG(cr.Rate) AS AvgVal, AVG(c.Rating) AS Rating, MAX(cr.DateCreate) AS DtmVal' . 
                       ' FROM ' . C_DB_TABLE_PREFIX . 'interaction_comments_rates AS cr' . 
                       ' INNER JOIN ' . C_DB_TABLE_PREFIX . 'interaction_comments AS c ON cr.CommentId=c.Id' . 
                       ' WHERE c.IsDisabled=0 AND cr.IsDisabled=0 AND c.UserId=' . $userId;
                break;
            default:
                return false;
        }
        /* DEBUG echo 'LN: ' . __LINE__ . '; F: ' . __FUNCTION__ . '; SQL: ' . $sql . '<br />'; */
        $item = $this->db->getItem($sql);
        if (null !== $item) {
            $stats[0]['Cnt'] = $item['CntVal'];
            $stats[0]['Avg'] = is_null($item['AvgVal']) ? 0 : $item['AvgVal'];
            if (-1 == $postType) {
                $stats[0]['Rating'] = is_null($item['Rating']) ? 0 : $item['Rating'];
            }
           $stats[0]['Dtm'] = is_null($item['DtmVal']) ? '0000-00-00 00:00:00' : $item['DtmVal'];
        }
        
        //обсчет статистики для периодов
        $periods = $this->getPeriods();
        foreach ($periods as $periodId => $period) {
            switch ($postType) {
                case 0:
                    //статистика по комментариям
                    $sql = 'SELECT COUNT(*) AS CntVal, AVG(CommentStatus) AS AvgVal, MAX(DateCreate) AS DtmVal' . 
                           ' FROM ' . C_DB_TABLE_PREFIX . 'interaction_comments' . 
                           ' WHERE IsDisabled=0 AND UserId=' . $userId . 
                           ' AND DateCreate>=DATE_ADD(NOW(), INTERVAL - ' . $period['Period'] . ' ' . $period['Code'] . ')';
                    break;
                case 1:
                    //статистика по голосам
                    $sql = 'SELECT COUNT(*) AS CntVal, AVG(Rate) AS AvgVal, MAX(DateCreate) AS DtmVal' . 
                           ' FROM ' . C_DB_TABLE_PREFIX . 'interaction_rates' . 
                           ' WHERE IsDisabled=0 AND UserId=' . $userId . 
                           ' AND DateCreate>=DATE_ADD(NOW(), INTERVAL - ' . $period['Period'] . ' ' . $period['Code'] . ')';
                    break;
                case 2:
                    //статистика по отметкам других комментариев
                    $sql = 'SELECT COUNT(*) AS CntVal, AVG(cr.Rate) AS AvgVal, MAX(cr.DateCreate) AS DtmVal' . 
                           ' FROM ' . C_DB_TABLE_PREFIX . 'interaction_comments_rates AS cr' . 
                           ' INNER JOIN ' . C_DB_TABLE_PREFIX . 'interaction_comments AS c ON cr.CommentId=c.Id' . 
                           ' WHERE c.IsDisabled=0 AND cr.IsDisabled=0 AND cr.UserId=' . $userId . 
                           ' AND cr.DateCreate>=DATE_ADD(NOW(), INTERVAL - ' . $period['Period'] . ' ' . $period['Code'] . ')';
                    break;
                case -1:
                    //статистика по отметкам других пользователей комментариев зарегистрированного пользователя
                     $sql = 'SELECT COUNT(*) AS CntVal, AVG(cr.Rate) AS AvgVal, AVG(c.Rating) AS Rating, MAX(cr.DateCreate) AS DtmVal' . 
                           ' FROM ' . C_DB_TABLE_PREFIX . 'interaction_comments_rates AS cr' . 
                           ' INNER JOIN ' . C_DB_TABLE_PREFIX . 'interaction_comments AS c ON cr.CommentId=c.Id' . 
                           ' WHERE c.IsDisabled=0 AND cr.IsDisabled=0 AND c.UserId=' . $userId . 
                           ' AND cr.DateCreate>=DATE_ADD(NOW(), INTERVAL - ' . $period['Period'] . ' ' . $period['Code'] . ')';
                   break;
            }
            /* DEBUG echo 'LN: ' . __LINE__ . '; F: ' . __FUNCTION__ . '; SQL: ' . $sql . '<br />'; */
            $item = $this->db->getItem($sql);
            if (null !== $item) {
                $stats[$periodId]['Cnt'] = $item['CntVal'];
                $stats[$periodId]['Avg'] = is_null($item['AvgVal']) ? 0 : $item['AvgVal'];
                if (-1 == $postType) {
                    $stats[$periodId]['Rating'] = is_null($item['Rating']) ? 0 : $item['Rating'];
                }
                $stats[$periodId]['Dtm'] = is_null($item['DtmVal']) ? '0000-00-00 00:00:00' : $item['DtmVal'];
            }
        }
        
        $ret = array();
        
        //обновление статистики за все время
        //обновление статистики для периодов
        foreach ($stats as $periodId => $stat) {
            $sql = 'SELECT Id FROM ' . C_DB_TABLE_PREFIX . 'interaction_stat_users' . 
                   ' WHERE UserId=' . $userId . 
                   ' AND PeriodId=' . $periodId;
            $id = $this->db->getValue($sql, 'Id');
            
            if (0 != $id) {
                switch ($postType) {
                    case 0:
                        $sql = 'UPDATE ' . C_DB_TABLE_PREFIX . 'interaction_stat_users' . 
                               " SET DateComment='" . $stat['Dtm'] . "'," . 
                                    ' DateCommentUpdate=NOW(),' . 
                                    ' CommentsCnt=' . $stat['Cnt'] . ',' . 
                                    " CommentsStatusAvg='" . $stat['Avg'] . "'" . 
                               ' WHERE Id=' . $id;
                        break;
                    case 1:
                        $sql = 'UPDATE ' . C_DB_TABLE_PREFIX . 'interaction_stat_users' . 
                               " SET DateRate='" . $stat['Dtm'] . "'," . 
                                    ' DateRateUpdate=NOW(),' . 
                                    ' RatesCnt=' . $stat['Cnt'] . ',' . 
                                    " RateAvg='" . $stat['Avg'] . "'" . 
                               ' WHERE Id=' . $id;
                        break;
                    case 2:
                        $sql = 'UPDATE ' . C_DB_TABLE_PREFIX . 'interaction_stat_users' . 
                               " SET DateRateComment='" . $stat['Dtm'] . "'," . 
                                    ' DateRateCommentUpdate=NOW(),' . 
                                    ' RatesCommentsCnt=' . $stat['Cnt'] . ',' . 
                                    " RateCommentsAvg='" . $stat['Avg'] . "'" . 
                               ' WHERE Id=' . $id;
                        break;
                    case -1:
                        $sql = 'UPDATE ' . C_DB_TABLE_PREFIX . 'interaction_stat_users' . 
                               " SET DateCommentRate='" . $stat['Dtm'] . "'," . 
                                    ' DateCommentRateUpdate=NOW(),' . 
                                    ' CommentsRatesCnt=' . $stat['Cnt'] . ',' . 
                                    " CommentsRateAvg='" . $stat['Avg'] . "'," . 
                                    " CommentsRating='" . $stat['Rating'] . "'" . 
                               ' WHERE Id=' . $id;
                        break;
             }
            } else {
                switch ($postType) {
                    case 0:
                        $sql = 'INSERT INTO ' . C_DB_TABLE_PREFIX . 'interaction_stat_users' . 
                               ' (UserId,PeriodId,DateComment,DateCommentUpdate,CommentsCnt,CommentsStatusAvg)' . 
                               ' VALUES(' . $userId . ',' . $periodId . ',' . 
                                        "'" . $stat['Dtm'] . "',NOW()," . $stat['Cnt'] . ",'" . $stat['Avg'] . "')";
                        break;
                    case 1:
                        $sql = 'INSERT INTO ' . C_DB_TABLE_PREFIX . 'interaction_stat_users' . 
                               ' (UserId,PeriodId,DateRate,DateRateUpdate,RatesCnt,RateAvg)' . 
                               ' VALUES(' . $userId . ',' . $periodId . ',' . 
                                        "'" . $stat['Dtm'] . "',NOW()," . $stat['Cnt'] . ",'" . $stat['Avg'] . "')";
                        break;
                    case 2:
                        $sql = 'INSERT INTO ' . C_DB_TABLE_PREFIX . 'interaction_stat_users' . 
                               ' (UserId,PeriodId,DateRateComment,DateRateCommentUpdate,RatesCommentsCnt,RateCommentsAvg)' . 
                               ' VALUES(' . $userId . ',' . $periodId . ',' . 
                                        "'" . $stat['Dtm'] . "',NOW()," . $stat['Cnt'] . ",'" . $stat['Avg'] . "')";
                        break;
                    case -1:
                        $sql = 'INSERT INTO ' . C_DB_TABLE_PREFIX . 'interaction_stat_users' . 
                               ' (UserId,PeriodId,DateCommentRate,DateCommentRateUpdate,CommentsRatesCnt,CommentsRateAvg,CommentsRating)' . 
                               ' VALUES(' . $userId . ',' . $periodId . ',' . 
                                        "'" . $stat['Dtm'] . "',NOW()," . $stat['Cnt'] . ",'" . $stat['Avg'] . "','" . $stat['Rating'] . "')";
                        break;
                }
            }
            $ret[$periodId] = $this->db->query($sql);
        }
        
        //возвращаем значение (ищем была ли ошибка, если нет возвращается true иначе false)
        return (false === array_search(false, $ret));
    }
}
