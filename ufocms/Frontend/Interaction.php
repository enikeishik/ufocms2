<?php
/**
 * UFOCMS v2 Content Management System
 * 
 * @copyright   Copyright (C) 2005 - 2017 Enikeishik <enikeishik@gmail.com>. All rights reserved.
 * @author      Enikeishik <enikeishik@gmail.com>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Ufocms\Frontend;

/**
 * Класс интерактивных составляющих - комментарии, оценка комментариев, рейтинг.
 */
class Interaction
{
    /**
     * @var Debug
     */
    protected $debug = null;
    
    /**
     * @var Config
     */
    protected $config = null;
    
    /**
     * @var Params
     */
    protected $params = null;
    
    /**
     * @var Db
     */
    protected $db = null;
    
    /**
     * @var InteractionConfig
     */
    protected $settings = null;
    
    /**
     * @var int
     */
    protected $sectionId = null;
    
    /**
     * @var int
     */
    protected $itemId = null;
    
    /**
     * @var int
     */
    protected $userId = null;
    
    /**
     * Осуществляется ли запрос посредством JS (XMLHttpRequest)
     * @var bool
     */
    protected $isXhr = null;
    
    /**
     * @var int
     */
    protected $commentsCount = null;
    
    /**
     * @var array
     */
    protected $itemStatistic = null;
    
    /**
     * @var array
     */
    protected $userStatistic = null;
    
    /**
     * Конструктор.
     * @param Config &$config
     * @param Params &$params
     * @param Db &$db
     * @param Debug &$debug = null
     */
    public function __construct(&$config, &$params, &$db, &$debug = null)
    {
        $this->config   =& $config;
        $this->params   =& $params;
        $this->debug    =& $debug;
        $this->db       =& $db;
        $this->init();
    }
    
    /**
     * Инициализация полей.
     */
    protected function init()
    {
        $this->settings = new InteractionConfig();
        $this->sectionId = $this->params->sectionId;
        $this->itemId = $this->params->itemId;
    }
    
    /**
     * Установка параметров (раздел, элемент, пользователь).
     * @param int $sectionId
     * @param int $itemId = 0
     * @param int $userId = 0
     * @param bool $isXhr = false
     */
    public function setParams($sectionId, $itemId = 0, $userId = 0, $isXhr = false)
    {
        $this->sectionId = $sectionId;
        $this->itemId = $itemId;
        $this->userId = $userId;
        $this->isXhr = $isXhr;
    }
    
    /**
     * Получение очищенного от добавок класса URL.
     * @return string
     */
    public function getUrl()
    {
        $uri = $_SERVER['REQUEST_URI'];
        //убираем постраничный вывод списка комментариев из URL
        $uri = preg_replace('/\/comments[0-9]+$/', '', $uri);
        if (false !== $pos = strrpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        //BOOKMARK: close slash
        //$uri = $uri . '/';
        return $uri;
    }
    
    /**
     * Проверка проголосовал ли уже пользователь за материал.
     *
     * Проверяется текущий пользователь по отметке в БД, 
     * либо (если пользователь не зарегистрирован) по кукам.
     *
     * @return boolean
     */
    public function currentUserVoted()
    {
        return $this->voted(1);
    }
    
    /**
     * Получение общего количества комментариев.
     * @return int|null
     */
    public function getCommentsCount()
    {
        if (null == $this->commentsCount) {
            $sql =  'SELECT COUNT(*) AS Cnt FROM ' . C_DB_TABLE_PREFIX . 'interaction_comments' . 
                    ' WHERE IsDisabled=0' . 
                    ' AND SectionId=' . $this->sectionId . 
                    ' AND ItemId=' . $this->itemId;
            $this->commentsCount = $this->db->getValue($sql, 'Cnt');
        }
        return $this->commentsCount;
    }
    
    /**
     * получение списка комментариев с постраничной разбивкой
     *
     * @param int $page = 1
     * @param int $pageSize = 10
     * @param bool $sortDesc обратная сортировка
     * @return array
     *
     * @todo передавать в качестве параметров пользовательскую сортировку
     */
    public function getComments($page = 1, $pageSize = 10, $sortDesc = false)
    {
        $cnt = $this->getCommentsCount();
        if (null === $cnt) {
            return null;
        }
        if (0 == $cnt) {
            return array();
        }
        
        $sql =  'SELECT Id,UserId,TopId,ParentId,OrderId,LevelId,Mask,' . 
                'DateCreate,IP,CommentText,CommentAuthor,CommentEmail,CommentUrl,CommentStatus,' . 
                'AnswerText,AnswerAuthor,AnswerEmail,AnswerUrl,RatesCnt,Rating' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'interaction_comments' . 
                ' WHERE IsDisabled=0' . 
                ' AND SectionId=' . $this->sectionId . 
                ' AND ItemId=' . $this->itemId;
        $sql .= ' ORDER BY DateCreate' . ($sortDesc ? ' DESC' : '');
        $sql .= ' LIMIT ' . ($page - 1) * $pageSize . ', ' . $pageSize;
        return $this->db->getItems($sql);
    }
    
    /**
     * получение последнего добавленного комментария
     * @return array|null
     */
    public function getLastComment()
    {
        $sql =  'SELECT Id,UserId,TopId,ParentId,OrderId,LevelId,Mask,' . 
                'DateCreate,IP,CommentText,CommentAuthor,CommentEmail,CommentUrl,CommentStatus,' . 
                'AnswerText,AnswerAuthor,AnswerEmail,AnswerUrl,RatesCnt,Rating' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'interaction_comments' . 
                ' WHERE IsDisabled=0' . 
                ' AND SectionId=' . $this->sectionId . 
                ' AND ItemId=' . $this->itemId . 
                ' ORDER BY DateCreate DESC' . 
                ' LIMIT 1';
        return $this->db->getItem($sql);
    }
    
    /**
     * Получение оценки комментария.
     * @param int|string $commentId
     * @return array|null
     */
    public function getCommentRating($commentId)
    {
        $commentId = (int) $commentId;
        if (0 >= $commentId) {
            return null;
        }
        $sql = 'SELECT RatesCnt,Rating' . 
               ' FROM ' . C_DB_TABLE_PREFIX . 'interaction_comments' . 
               ' WHERE Id=' . $commentId;
        return $this->db->getItem($sql);
    }
    
    /**
     * @see getItemStatistic
     */
    public function getRating()
    {
        return $this->getItemStatistic(0);
    }
    
    /**
     * Получение полной статистики по запрошенному/текущему материалу: 
     * даты последних к/г, количества и средние значения и т.п.
     *
     * @param int $periodId - период за который необходимо получить статитстику, по-умолчанию выдается статистика за все имеющиеся периоды
     * @param int $itemId - идентификатор материала, для которого необходимо получить статитстику, по-умолчанию выдается статистика текущего материала
     * @return array|null
     */
    public function getItemStatistic($periodId = null, $itemId = null)
    {
        //TODO: чтобы заработал параметр $itemId, необходимо передавать его
        //в обновление статистики, для этого необходимо подправить функции обновления статистики
        if (null === $itemId) {
            $itemId = $this->itemId;
            //если результат уже был получен, возвращаем его
            if (null !== $this->itemStatistic) {
                if (null === $periodId) {
                    return $this->itemStatistic;
                } else if (array_key_exists('PeriodId' . $periodId, $this->itemStatistic)) {
                    return $this->itemStatistic['PeriodId' . $periodId];
                } else {
                    return null;
                }
            }
        }
        
        //получаем статистику
        $sql = 'SELECT PeriodId, DateComment, CommentsCnt, CommentsStatusAvg,' . 
               ' DateRate, RatesCnt, Rating' . 
               ' FROM ' . C_DB_TABLE_PREFIX . 'interaction_stat_items' . 
               ' WHERE SectionId=' . $this->sectionId . 
               ' AND ItemId=' . $itemId;
        $statistic = $this->db->getItems($sql, 'PeriodId');
        
        if ($itemId == $this->itemId) {
            //сохраняем результат для повторного использования 
            //если запрошена статистика текущего элемента
            $this->itemStatistic = $statistic;
        }
        
        if (null === $periodId) {
            return $statistic;
        } else if (array_key_exists('PeriodId' . $periodId, $statistic)) {
            return $statistic['PeriodId' . $periodId];
        } else {
            return null;
        }
    }
    
    /**
     * Получение полной статистики по запрошенному/текущему пользователю: 
     * даты последних к/о/г, количества и средние значения и т.п.
     *
     * @param int $periodId - период за который необходимо получить статитстику, по-умолчанию выдается статистика за все имеющиеся периоды
     * @param int $userId идентификаторо пользователя, для которого необходимо получить статистику
     * @return array|null
     */
    public function getUserStatistic($periodId = null, $userId = null)
    {
        //TODO: чтобы заработал параметр $userId, необходимо передавать его
        //в обновление статистики, для этого необходимо подправить функции обновления статистики
        if (null === $userId) {
            $userId = $this->userId;
            if (0 != $userId) {
                //если результат уже был получен, возвращаем его
                if (null !== $this->userStatistic) {
                    if (null === $periodId) {
                        return $this->userStatistic;
                    } else if (array_key_exists('PeriodId' . $periodId, $this->userStatistic)) {
                        return $this->userStatistic['PeriodId' . $periodId];
                    } else {
                        return null;
                    }
                }
            }
        }
        if (0 == $userId) {
            return null;
        }
        
        //получаем статистику
        $sql =  'SELECT PeriodId,' . 
                ' DateComment, CommentsCnt, CommentsStatusAvg,' . 
                ' DateCommentRate, CommentsRatesCnt, CommentsRateAvg, CommentsRating,' . 
                ' DateRateComment, RatesCommentsCnt, RateCommentsAvg,' . 
                ' DateRate, RatesCnt, RateAvg' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'interaction_stat_users' . 
                ' WHERE UserId=' . $userId;
        $statistic = $this->db->getItems($sql, 'PeriodId');
        
        if ($userId == $this->userId) {
            //сохраняем результат для повторного использования 
            //если запрошена статистика текущего элемента
            $this->userStatistic = $statistic;
        }
        
        if (null === $periodId) {
            return $statistic;
        } else if (array_key_exists('PeriodId' . $periodId, $statistic)) {
            return $statistic['PeriodId' . $periodId];
        } else {
            return null;
        }
    }
    
    /**
     * Проверка проголосовал ли уже пользователь за материал/комментарий.
     *
     * @param int $postType тип данных статистики: 0 - комментарии, 1 - голоса, 2 - отметки комментариям (поставленные мною комментариям других), -1 - отметки комментариев (поставленные моим комментариям другими)
     * @param int $commentId идентификатор комментария, который оценивается
     * @return bool
     * @throws \Exception
     */
    protected function voted($postType, $commentId = 0)
    {
        //проверка для зарегистрированных пользователей сайта
        if (0 != $this->userId) {
            switch ($postType) {
                case 1:
                    $sql =  'SELECT COUNT(*) AS Cnt FROM ' . C_DB_TABLE_PREFIX . 'interaction_rates' . 
                            ' WHERE UserId=' . $this->userId . 
                            ' AND SectionId=' . $this->sectionId . 
                            ' AND ItemId=' . $this->itemId . 
                            ' AND IsDisabled=0';
                    break;
                case 2:
                    $sql =  'SELECT COUNT(*) AS Cnt FROM ' . C_DB_TABLE_PREFIX . 'interaction_comments_rates' . 
                            ' WHERE UserId=' . $this->userId . 
                            ' AND SectionId=' . $this->sectionId . 
                            ' AND CommentId=' . $commentId . 
                            ' AND IsDisabled=0';
                    break;
                default:
                    throw new \Exception('Unsupported parameter value $postType=' . $postType . '.');
            }
            return 0 < $this->db->getValue($sql, 'Cnt');
            
        //проверка незарегистрированных пользователей по кукам
        } else {
            if (1 == $postType) {
                $cookieName = $this->settings->rateCookieName . ':s' . $this->sectionId . ':i' . $this->itemId;
            } else {
                $cookieName = $this->settings->rateCookieName . ':s' . $this->sectionId . ':c' . $commentId;
            }
            if (isset($_COOKIE[$cookieName]) && $_COOKIE[$cookieName]) {
                return true;
            } else {
                return false;
            }
        }
    }
}
