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
 * Класс управления интерактивными составляющими - комментарии, оценка комментариев, рейтинг.
 */
class InteractionManage extends Interaction
{
    /**
     * @var InteractionConfig
     */
    protected $settings = null;
    
    /**
     * @var string
     */
    protected $mailTo = null;
    
    /**
     * @var string
     */
    protected $mailFrom = null;
    
    /**
     * @var string
     */
    protected $mailSubject = null;
    
    /**
     * @var string
     */
    protected $mailBody = null;
    
    /**
     * @var array
     */
    protected $messageMarks = null;

    
    /**
     * @var Captcha
     */
    protected $captcha = null;
    
    /**
     * @var string
     */
    protected $error = null;
    
    /**
     * @var int
     */
    protected $errorCode = null;
    
    /**
     * @var mixed
     */
    protected $actionResult = null;
    
    /**
     * Конструктор.
     * @param Config &$config
     * @param Params &$params
     * @param Db &$db
     * @param Debug &$debug = null
     */
    public function __construct(&$config, &$params, &$db, &$debug = null)
    {
        parent::__construct($config, $params, $db, $debug);
    }
    
    /**
     * Инициализация полей.
     */
    protected function init()
    {
        parent::init();
        $this->settings = new InteractionConfig();
        $this->messageMarks = array(
            '{URL}', 
            '{DTM}', 
            '{IP}', 
            '{COMMENT}', 
            '{AUTHOR}', 
            '{EMAIL}', 
            '{WWW}', 
            '{STATUS}', 
            '{RATE}', 
        );
        $this->error = '';
        $this->actionResult = array(
            'source'    => false, 
            'human'     => false, 
            'db'        => false, 
            'email'     => false, 
        );
    }
    
    /**
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }
    
    /**
     * @return int
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }
    
    /**
     * @return mixed
     */
    public function getActionResult()
    {
        return $this->actionResult;
    }
    
    /**
     * Установка объекта CAPTCHA.
     * @param object $captcha          объект CAPTCHA
     */
    public function setCaptcha($captcha)
    {
        $this->settings->checkCaptcha = true;
        $this->captcha = $captcha;
    }
    
    /**
     * Установка флагов определяющих различные проверки при добавлении к/о/г.
     *
     * @param bool|array $requireRegistered = null  добавление возможно только для зарегистрированных пользователей сайта
     * @param bool $commentPremoderation = null     премодерация комментариев
     * @param bool $checkReferer = null             проверять источник referer
     * @param object $captchaObject = null          объект CAPTCHA
     * @param int $commentMaxLength = null          максимальный объем текста комментария
     * @return void
     *
     * @example
     *  - setFlags(1);
     *  - setFlags(0, 0, 1, 1);
     *  - setFlags(array('Comments' => 0, 'Rates' => 0, 'CommentsRates' => 1), 0, 1, 1, 1024);
     */
    public function setFlags(
        $requireRegistered      = null, 
        $commentPremoderation   = null, 
        $checkReferer           = null, 
        $captchaObject          = null, 
        $commentMaxLength       = null
    ) {
        if (is_array($requireRegistered)) {
            if (array_key_exists('Comments', $requireRegistered)) {
                $this->settings->commentRequireRegistered       = (bool) $requireRegistered['Comments'];
            }
            if (array_key_exists('Rates', $requireRegistered)) {
                $this->settings->commentRateRequireRegistered   = (bool) $requireRegistered['Rates'];
            }
            if (array_key_exists('CommentsRates', $requireRegistered)) {
                $this->settings->rateRequireRegistered          = (bool) $requireRegistered['CommentsRates'];
            }
        } else if (!is_null($requireRegistered)) {
            $requireRegistered = (bool) $requireRegistered;
            $this->settings->commentRequireRegistered           = $requireRegistered;
            $this->settings->commentRateRequireRegistered       = $requireRegistered;
            $this->settings->rateRequireRegistered              = $requireRegistered;
        }
        
        if (!is_null($commentPremoderation)) {
            $this->settings->commentPremoderation = (bool) $commentPremoderation;
        }
        if (!is_null($checkReferer)) {
            $this->settings->checkReferer = (bool) $checkReferer;
        }
        if (is_object($captchaObject)) {
            $this->settings->checkCaptcha = true;
            $this->captcha = $captchaObject;
        }
        if (!is_null($commentMaxLength)) {
            $this->settings->commentMaxLength = (int) $commentMaxLength;
        }
    }
    
    /**
     * Установка задержек.
     *
     * @param int|array $delay      задержка между добавлениями к/о/г
     * @param bool $delayRegistered  применять задержки для зарегистрированных пользователей сайта
     * @return void
     *
     * @example
     *  - setDelays(60);
     *  - setDelays(30, true);
     *  - setDelays(array('Comments' => 60, 'Rates' => 3600, 'CommentsRates' => 3600));
     */
    public function setDelays($delay, $delayRegistered = null)
    {
        if (is_array($delay)) {
            if (array_key_exists('Comments', $delay)) {
                $this->settings->commentAddDelay = (int) $delay['Comments'];
                if (0 > $this->settings->commentAddDelay) {
                    $this->settings->commentAddDelay = 0;
                }
            }
            if (array_key_exists('CommentsRates', $delay)) {
                $this->settings->commentRateDelay = (int) $delay['CommentsRates'];
                if (0 > $this->settings->commentRateDelay) {
                    $this->settings->commentRateDelay = 0;
                }
            }
            if (array_key_exists('Rates', $delay)) {
                $this->settings->rateDelay = (int) $delay['Rates'];
                if (0 > $this->settings->rateDelay) {
                    $this->settings->rateDelay = 0;
                }
            }
        } else {
            $delay = (int) $delay;
            if (0 > $delay) {
                $delay = 0;
            }
            $this->settings->commentAddDelay    = $delay;
            $this->settings->commentRateDelay   = $delay;
            $this->settings->rateDelay          = $delay;
        }
        
        if (!is_null($delayRegistered)) {
            $this->settings->delayRegistered = (bool) $delayRegistered;
        }
    }
    
    /**
     * Установка параметров оповещения по электронной почте.
     *
     * @param string $to    адрес, куда отправлять оповещения
     * @param string $subj  тема оповещений
     * @param string $body  текст оповещений
     * @param bool $html использовать для оповещений HTML формат (вместо текстового)
     * @param string $from  обратный адрес
     * @return void
     * @throws \Exception
     */
    public function setMail($to, $subj, $body, $html = false, $from = '')
    {
        if ('' == $to || ('' == $subj && '' == $body)) {
            throw new \Exception('MailTo or Message unset.');
        }
        $this->mailTo = $to;
        $this->mailSubject = $subj;
        $this->mailBody = $body;
        $this->settings->mailFormatHtml = $html;
        $this->mailFrom = $from;
    }
    
    /**
     * Добавление нового комментария.
     *
     * @return bool
     */
    public function addComment()
    {
        //проверка источника и сбор информации (IP и т.п.)
        $info = $this->check(0, array('text'));
        if (!is_array($info)) {
            return false;
        }
        
        //сбор данных
        $text = $_POST['text'];
        $author = isset($_POST['author']) ? $_POST['author'] : '';
        $email = isset($_POST['email']) ? $_POST['email'] : '';
        $url = isset($_POST['url']) ? $_POST['url'] : '';
        $status = isset($_POST['status']) ? (int) $_POST['status'] : $this->settings->commentStatusDefault;
        if ($status > $this->settings->commentStatusMax || $status < $this->settings->commentStatusMin) {
            $status = $this->settings->commentStatusDefault;
        }
        
        //запись данных в БД
        $sql =  'INSERT INTO ' . C_DB_TABLE_PREFIX . 'interaction_comments' . 
                ' (UserId,SectionId,ItemId,' . 
                  'DateCreate,IP,Info,' . 
                  'CommentText,CommentAuthor,CommentEmail,CommentUrl,' . 
                  'CommentStatus,IsDisabled)' . 
                ' VALUES(' . 
                $this->userId . ',' . 
                $this->sectionId . ',' . 
                $this->itemId . ',' . 
                'NOW(),' . 
                "'" . long2ip(ip2long($info['IP'])) . "'," . 
                "'" . $this->db->addEscape($info['Info']) . "'," . 
                "'" . $this->db->addEscape(nl2br(htmlspecialchars(substr($text, 0, $this->settings->commentMaxLength)))) . "'," . 
                "'" . $this->db->addEscape(htmlspecialchars(substr($author, 0, 255))) . "'," . 
                "'" . $this->db->addEscape(htmlspecialchars(substr($email, 0, 255))) . "'," . 
                "'" . $this->db->addEscape(htmlspecialchars(substr($url, 0, 255))) . "'," . 
                $status . ',' . 
                (int) (0 != $this->settings->commentPremoderation) . ')';
        if ($this->db->query($sql)) {
            $commentId = $this->db->getLastInsertedId();
            $sql =  'UPDATE ' . C_DB_TABLE_PREFIX . 'interaction_comments' . 
                    ' SET TopId=' . $commentId . 
                    ' WHERE Id=' . $commentId;
            $this->db->query($sql);
            $this->updateItemStatistic();
            $this->updateUserStatistic();
            if (0 != $this->settings->commentAddDelay && (0 == $this->userId || $this->settings->delayRegistered)) {
                $this->pushToStack($info['IP']);
            }
            $this->actionResult['db'] = true;
        } else {
            $this->error = 'Database error';
            $this->errorCode = 8;
            return false;
        }
        
        //отправляем уведомление на email
        $data = array(
            'IP' => $info['IP'], 
            'Info' => $info['Info'], 
            'Text' => $text, 
            'Author' => $author, 
            'Email' => $email, 
            'Url' => $url, 
            'Status' => $status, 
            'Rate' => ''
        );
        if (!$this->sendMail($data)) {
            $this->error = 'SendMail error';
            $this->errorCode = 9;
        }
        
        return true;
    }
    
    /**
     * Добавление оценки комментария.
     *
     * @return bool
     */
    public function addCommentRate()
    {
        //сбор данных
        if (!isset($_POST['commentid'])) {
            $this->error = 'POST data unset';
            $this->errorCode = 7;
            return false;
        }
        $commentId = (int) $_POST['commentid'];
        
        //проверка источника и сбор информации (IP и т.п.)
        $info = $this->check(2, array('commentrate'), $commentId);
        if (!is_array($info)) {
            return false;
        }
        
        //сбор данных
        //проверяем существует ли запись с таким идентификатором
        //и попутно получаем из базы необходимые дополнительные данные 
        //(идентификатор пользователя-автора комментария для обновления его статистики)
        $sql =  'SELECT UserId' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'interaction_comments' . 
                ' WHERE Id=' . $commentId;
        $commentUserId = $this->db->getValue($sql, 'UserId');
        if (null === $commentUserId) {
            $this->error = 'Comment not exists';
            $this->errorCode = 10;
            return false;
        }
        
        //сбор данных
        $commentRate = (int) $_POST['commentrate'];
        if ($commentRate > $this->settings->commentRateMax || $commentRate < $this->settings->commentRateMin) {
            $commentRate = $this->settings->commentRateDefault;
        }
        
        //запись данных в БД
        $sql =  'INSERT INTO ' . C_DB_TABLE_PREFIX . 'interaction_comments_rates' . 
                ' (UserId,SectionId,CommentId,' . 
                  'DateCreate,IP,Info,Rate)' . 
                ' VALUES(' . 
                $this->userId . ',' . 
                $this->sectionId . ',' . 
                $commentId . ',' . 
                'NOW(),' . 
                "'" . long2ip(ip2long($info['IP'])) . "'," . 
                "'" . $this->db->addEscape($info['Info']) . "'," . 
                $commentRate . ')';
        if ($this->db->query($sql)) {
            $this->updateCommentRating($commentId);
            //обновляем статистику текущего пользователя
            $this->updateUserStatistic (2);
            //обновляем статистику пользователя-автора комментария на который была сделана отметка
            $this->updateUserStatistic(-1, $commentUserId);
            if (0 != $this->settings->commentRateDelay && (0 == $this->userId || $this->settings->delayRegistered)) {
                $this->pushToStack($info['IP'], 2, $commentId);
            }
            $cookieName = $this->settings->rateCookieName . ':s' . $this->sectionId . ':c' . $commentId;
            setcookie($cookieName, '1', time() + $this->settings->rateCookieLifetime);
        } else {
            $this->error = 'Database error';
            $this->errorCode = 8;
            return false;
        }
        
        //отправляем уведомление на email
        $data = array(
            'IP' => $info['IP'], 
            'Info' => $info['Info'], 
            'Text' => '', 
            'Author' => '', 
            'Email' => '', 
            'Url' => '', 
            'Status' => '', 
            'Rate' => $commentRate
        );
        if (!$this->sendMail($data)) {
            $this->error = 'SendMail error';
            $this->errorCode = 9;
        }
        
        return true;
    }
    
    /**
     * Добавление новой оценки материала.
     * 
     * @return bool
     */
    public function addRate()
    {
        //проверка источника и сбор информации (IP и т.п.)
        $info = $this->check(1, array('rate'));
        if (!is_array($info)) {
            return false;
        }
        
        //сбор данных
        $rate = (int) $_POST['rate'];
        if ($rate > $this->settings->rateMax || $rate < $this->settings->rateMin) {
            $rate = $this->settings->rateDefault;
        }
        
        //запись данных в БД
        $sql = 'INSERT INTO ' . C_DB_TABLE_PREFIX . 'interaction_rates' . 
                ' (UserId,SectionId,ItemId,' . 
                  'DateCreate,IP,Info,Rate)' . 
                ' VALUES(' . 
                $this->userId . ',' . 
                $this->sectionId . ',' . 
                $this->itemId . ',' . 
                'NOW(),' . 
                "'" . long2ip(ip2long($info['IP'])) . "'," . 
                "'" . $this->db->addEscape($info['Info']) . "'," . 
                $rate . ')';
        if ($this->db->query($sql)) {
            $this->updateItemStatistic(1);
            $this->updateUserStatistic(1);
            if (0 != $this->settings->rateDelay && (0 == $this->userId || $this->settings->delayRegistered)) {
                $this->pushToStack($info['IP'], 1);
            }
            $cookieName = $this->settings->rateCookieName . ':s' . $this->sectionId . ':i' . $this->itemId;
            setcookie($cookieName, '1', time() + $this->settings->rateCookieLifetime);
        } else {
            $this->error = 'Database error';
            $this->errorCode = 8;
            return false;
        }
        
        //отправляем уведомление на email
        $data = array(
            'IP' => $info['IP'], 
            'Info' => $info['Info'], 
            'Text' => '', 
            'Author' => '', 
            'Email' => '', 
            'Url' => '', 
            'Status' => '', 
            'Rate' => $rate
        );
        if (!$this->sendMail($data)) {
            $this->error = 'SendMail error';
            $this->errorCode = 9;
        }
        
        return true;
    }
    
    /**
     * Проверка наличия IP адреса в черном списке.
     * @param string $ip
     * @return bool
     */
    protected function inBlackList($ip)
    {
        $sql =  'SELECT COUNT(*) AS Cnt FROM ' . C_DB_TABLE_PREFIX . 'interaction_blacklist ' . 
                "WHERE IP='" . long2ip(ip2long($ip)) . "'";
        return 0 < $this->db->getValue($sql, 'Cnt');
    }
    
    /**
     * Проверка наличия IP адреса в стеке.
     * @param string $ip
     * @param int $postType = 0
     * @param int $commentId = 0
     * @return bool
     */
    protected function inStack($ip, $postType = 0, $commentId = 0)
    {
        $this->clearStack($postType, $commentId);
        
        $sql =  'SELECT COUNT(*) AS Cnt' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'interaction_poststack' . 
                ' WHERE SectionId=' . $this->sectionId . 
                ' AND ItemId=' . $this->itemId;
        if (0 != $commentId) {
            $sql .= ' AND CommentId=' . $commentId;
        }
        $sql .= ' AND PostType=' . $postType;
        if (0 != $this->userId) {
            $sql .= ' AND UserId=' . $this->userId;
        } else {
            $sql .= " AND IP='" . long2ip(ip2long($ip)) . "'";
        }
        return 0 < $this->db->getValue($sql, 'Cnt');
    }
    
    /**
     * Очистка стека от устаревших записей.
     * @param int $postType = 0
     * @param int $commentId = 0
     * @return bool
     */
    protected function clearStack($postType = 0, $commentId = 0)
    {
        //TODO: думаю надо удалять записи без учета SectionId, ItemId/CommentId
        //поскольку время жизни стэка одинаково для всех разделов/элементов
        //и может различаться только в зависимости от PostType
        $sql =  'DELETE FROM ' . C_DB_TABLE_PREFIX . 'interaction_poststack' . 
                ' WHERE SectionId=' . $this->sectionId . 
                ' AND ItemId=' . $this->itemId;
        if (0 != $commentId) {
            $sql .= ' AND CommentId=' . $commentId;
        }
        $sql .= ' AND PostType=' . $postType;
        switch ($postType) {
            case 1:
                $sql .= ' AND TIME_TO_SEC(TIMEDIFF(NOW(), DatePost))>' . $this->settings->rateDelay;
                break;
            case 2:
                $sql .= ' AND TIME_TO_SEC(TIMEDIFF(NOW(), DatePost))>' . $this->settings->commentRateDelay;
                break;
            default:
                $sql .= ' AND TIME_TO_SEC(TIMEDIFF(NOW(), DatePost))>' . $this->settings->commentAddDelay;
        }
        return $this->db->query($sql);
    }
    
    /**
     * Занесение IP адреса в стек.
     * @param string $ip
     * @param int $postType = 0
     * @param int $commentId = 0
     * @return bool
     */
    protected function pushToStack($ip, $postType = 0, $commentId = 0)
    {
        $sql =  'INSERT INTO ' . C_DB_TABLE_PREFIX . 'interaction_poststack' . 
                ' (UserId, SectionId, ItemId, CommentId, PostType, IP, DatePost)' . 
                ' VALUES(' . $this->userId . 
                        ',' . $this->sectionId . 
                        ',' . $this->itemId . 
                        ',' . $commentId . 
                        ',' . $postType . 
                        ",'" . long2ip(ip2long($ip)) . 
                        "',NOW())";
        return $this->db->query($sql);
    }
    
    /**
     * Проверка источника комментария/оценки/... .
     *
     * @param int $postType тип данных статистики: 0 - комментарии, 1 - голоса, 2 - отметки комментариям (поставленные мною комментариям других), -1 - отметки комментариев (поставленные моим комментариям другими)
     * @param array $requiredFields список имен обязательных полей формы
     * @param int $commentId идентификатор комментария, который оценивается
     * @return false|array<string 'IP' IP адрес источника, string 'Info' HTTP заголовки источника>
     * @throws \Exception
     */
    protected function check($postType = 0, $requiredFields = array(), $commentId = 0)
    {
        //проверка источника
        if ($this->settings->checkReferer) {
            if (!isset($_SERVER['HTTP_REFERER'])) {
                $this->error = 'Referer unset';
                $this->errorCode = 1;
                return false;
            }
            if (false === strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST'])) {
                $this->error = 'Referer incorrect';
                $this->errorCode = 2;
                return false;
            }
        }
        $this->actionResult['source'] = true;
        
        //проверка CAPTCHA
        if (0 == $postType && $this->settings->checkCaptcha && null !== $this->captcha) {
            if (!$this->captcha->check()) {
                $this->error = 'CAPTCHA incorrect';
                $this->errorCode = 3;
                return false;
            }
        }
        $this->actionResult['human'] = true;
        
        //проверка зарегистрированного пользователя
        $requireRegistered = false;
        switch ($postType) {
            case 0:
                $requireRegistered = $this->settings->commentRequireRegistered;
                break;
            case 1:
                $requireRegistered = $this->settings->rateRequireRegistered;
                break;
            case 2:
                $requireRegistered = $this->settings->commentRateRequireRegistered;
                break;
            default:
                throw new \Exception('Unsupported parameter value $postType=' . $postType . '.');
        }
        if ($requireRegistered) {
            if (0 == $this->userId) {
                $this->error = 'Registered required';
                $this->errorCode = 4;
                return false;
            }
        }
        
        $ip = '';
        if (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        
        //проверка на черный список
        if ($this->inBlackList($ip)) {
            $this->error = 'IP blocked';
                $this->errorCode = 5;
            return false;
        }
        
        //проверка задержки между публикациями/голосованиями
        $addDelay = 0;
        switch ($postType) {
            case 0:
                $addDelay = $this->settings->commentAddDelay;
                break;
            case 1:
                $addDelay = $this->settings->rateDelay;
                break;
            case 2:
                $addDelay = $this->settings->commentRateDelay;
                break;
            default:
                throw new \Exception('Unsupported parameter value $postType=' . $postType . '.');
        }
        if (0 != $addDelay && (0 == $this->userId || $this->settings->delayRegistered)) {
            if ($this->inStack($ip, $postType, $commentId)) {
                $this->error = 'IP/User temporary blocked';
                $this->errorCode = 6;
                return false;
            }
        }
        
        //на наличие голоса/отметки для данного материала/комментария
        if (0 != $postType) {
            if ($this->voted($postType, $commentId)) {
                $this->error = 'User already voted';
                $this->errorCode = 11;
                return false;
            }
        }
        
        //проверка наличия данных
        foreach ($requiredFields as $field) {
            if (!isset($_POST[$field])) {
                $this->error = 'POST data unset';
                $this->errorCode = 7;
                return false;
            }
        }
        
        //сбор информации
        $info = 'HTTP_HOST:            ' . $_SERVER['HTTP_HOST'] . "\n" . 
                'REQUEST_URI:          ' . $_SERVER['REQUEST_URI'] . "\n" . 
                'HTTP_REFERER:         ' . (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '') . "\n" . 
                'REQUEST_METHOD:       ' . $_SERVER['REQUEST_METHOD'] . "\n" . 
                'REMOTE_ADDR:          ' . $_SERVER['REMOTE_ADDR'] . "\n" . 
                'HTTP_X-FORWARDED-FOR: ' . (isset($_SERVER['HTTP_X-FORWARDED-FOR']) ? $_SERVER['HTTP_X-FORWARDED-FOR'] : '') . "\n" . 
                'HTTP_ACCEPT_LANGUAGE: ' . (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : '') . "\n" . 
                'HTTP_USER_AGENT:      ' . $_SERVER['HTTP_USER_AGENT'] . "\n";
        
        return array('IP' => $ip, 'Info' => $info);
    }
    
    /**
     * Обновление статистики комментария при добавлении отметки комментарию.
     *
     * @param int $commentId идентификатор комментария
     * @return bool
     */
    protected function updateCommentRating($commentId)
    {
        $sql =  'SELECT COUNT(*) AS CntVal, SUM(Rate) AS SumVal' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'interaction_comments_rates' . 
                ' WHERE IsDisabled=0' . 
                ' AND CommentId=' . $commentId . 
                ' GROUP BY CommentId';
        $item = $this->db->getItem($sql);
        if (null === $item) {
            return false;
        }
        
        $sql =  'UPDATE ' . C_DB_TABLE_PREFIX . 'interaction_comments' . 
                ' SET RatesCnt=' . $item['CntVal'] . ',Rating=' . $item['SumVal'] . 
                ' WHERE Id=' . $commentId;
        return $this->db->query($sql);
    }
    
    /**
     * Получение списка периодов за которые нужно сохранять статистику.
     *
     * @return array<int идентификатор периода => array<string 'Code' кодовое название периода, int 'PeriodId' идентификатор периода, int 'Period' размер периода>>
     */
    protected function getPeriods()
    {
        $sql =  'SELECT u.Code, p.Id AS PeriodId, p.Period' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'interaction_stat_periods_units AS u' . 
                ' INNER JOIN ' . C_DB_TABLE_PREFIX . 'interaction_stat_periods AS p ON u.Id=p.UnitId' . 
                ' WHERE p.IsDisabled=0';
        $arr = $this->db->getItems($sql);
        if (null === $arr) {
            return array();
        }
        $periods = array();
        foreach ($arr as $a) {
            $periods[$a['PeriodId']] = $a;
        }
        //на всякий случай проверяем нет ли в БД данных с идентификатором 0
        //и если есть, убираем эти данные, 
        //поскольку 0 зарезервирован для статистики за все время
        if (array_key_exists(0, $periods)) {
            $arr = array();
            foreach ($periods as $key => $val) {
                if (0 != $key) {
                    $arr[$key] = $periods[$key];
                }
            }
            $periods = $arr;
        }
        return $periods;
    }
    
    /**
     * Обновление статистики материала.
     *  - при добавлении комментария на материал
     *  - при оставлении голоса за материал
     *
     * @param int $postType тип запроса: 0 - комментарий, 1 - голос
     * @return bool
     * @thorws \Exception
     */
    protected function updateItemStatistic($postType = 0)
    {
        $stats = array();
        //обсчет статистики за все время
        switch ($postType) {
            case 0:
                //статистика по комментариям
                $sql =  'SELECT COUNT(*) AS CntVal, AVG(CommentStatus) AS AvgVal, MAX(DateCreate) AS DtmVal' . 
                        ' FROM ' . C_DB_TABLE_PREFIX . 'interaction_comments' . 
                        ' WHERE SectionId=' . $this->sectionId . 
                        ' AND ItemId=' . $this->itemId . 
                        ' AND IsDisabled=0';
                break;
            case 1:
                //статистика по голосам
                $sql =  'SELECT COUNT(*) AS CntVal, AVG(Rate) AS AvgVal, MAX(DateCreate) AS DtmVal' . 
                        ' FROM ' . C_DB_TABLE_PREFIX . 'interaction_rates' . 
                        ' WHERE SectionId=' . $this->sectionId . 
                        ' AND ItemId=' . $this->itemId . 
                        ' AND IsDisabled=0';
                break;
            default:
                throw new \Exception('Unsupported parameter value $postType=' . $postType . '.');
        }
        if (null === $item = $this->db->getItem($sql)) {
            return false;
        }
        $stats[0]['Cnt'] = $item['CntVal'];
        $stats[0]['Avg'] = is_null($item['AvgVal']) ? 0 : $item['AvgVal'];
        $stats[0]['Dtm'] = is_null($item['DtmVal']) ? '0000-00-00 00:00:00' : $item['DtmVal'];
        
        //обсчет статистики для периодов
        $periods = $this->getPeriods();
        foreach ($periods as $periodId => $period) {
            switch ($postType) {
                case 0:
                    //статистика по комментариям
                    $sql =  'SELECT COUNT(*) AS CntVal, AVG(CommentStatus) AS AvgVal, MAX(DateCreate) AS DtmVal' . 
                            ' FROM ' . C_DB_TABLE_PREFIX . 'interaction_comments' . 
                            ' WHERE SectionId=' . $this->sectionId . 
                            ' AND ItemId=' . $this->itemId . 
                            ' AND IsDisabled=0' . 
                            ' AND DateCreate>=DATE_ADD(NOW(), INTERVAL - ' . $period['Period'] . ' ' . $period['Code'] . ')';
                    break;
                case 1:
                    //статистика по голосам
                    $sql =  'SELECT COUNT(*) AS CntVal, AVG(Rate) AS AvgVal, MAX(DateCreate) AS DtmVal' . 
                            ' FROM ' . C_DB_TABLE_PREFIX . 'interaction_rates' . 
                            ' WHERE SectionId=' . $this->sectionId . 
                            ' AND ItemId=' . $this->itemId . 
                            ' AND IsDisabled=0' . 
                            ' AND DateCreate>=DATE_ADD(NOW(), INTERVAL - ' . $period['Period'] . ' ' . $period['Code'] . ')';
                    break;
            }
            if (null === $item = $this->db->getItem($sql)) {
                return false;
            }
            $stats[$periodId]['Cnt'] = $item['CntVal'];
            $stats[$periodId]['Avg'] = is_null($item['AvgVal']) ? 0 : $item['AvgVal'];
            $stats[$periodId]['Dtm'] = is_null($item['DtmVal']) ? '0000-00-00 00:00:00' : $item['DtmVal'];
        }
        
        $ret = array();
        
        //обновление статистики за все время
        //обновление статистики для периодов
        foreach ($stats as $periodId => $stat) {
            $sql =  'SELECT Id FROM ' . C_DB_TABLE_PREFIX . 'interaction_stat_items' . 
                    ' WHERE SectionId=' . $this->sectionId . 
                    ' AND ItemId=' . $this->itemId . 
                    ' AND PeriodId=' . $periodId;
            if (null === $id = $this->db->getValue($sql, 'Id')) {
                $id = 0;
            }
            if (0 != $id) {
                switch ($postType) {
                    case 0:
                        $sql =  'UPDATE ' . C_DB_TABLE_PREFIX . 'interaction_stat_items' . 
                                " SET DateComment='" . $stat['Dtm'] . "'," . 
                                    ' DateCommentUpdate=NOW(),' . 
                                    ' CommentsCnt=' . $stat['Cnt'] . ',' . 
                                    " CommentsStatusAvg='" . $stat['Avg'] . "'" . 
                                ' WHERE Id=' . $id;
                        break;
                    case 1:
                        $sql =  'UPDATE ' . C_DB_TABLE_PREFIX . 'interaction_stat_items' . 
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
                        $sql =  'INSERT INTO ' . C_DB_TABLE_PREFIX . 'interaction_stat_items' . 
                                ' (SectionId,ItemId,PeriodId,DateComment,DateCommentUpdate,CommentsCnt,CommentsStatusAvg)' . 
                                ' VALUES(' . $this->sectionId . ',' . $this->itemId . ',' . $periodId . ',' . 
                                        "'" . $stat['Dtm'] . "',NOW()," . $stat['Cnt'] . ",'" . $stat['Avg'] . "')";
                        break;
                    case 1:
                        $sql =  'INSERT INTO ' . C_DB_TABLE_PREFIX . 'interaction_stat_items' . 
                                ' (SectionId,ItemId,PeriodId,DateRate,DateRateUpdate,RatesCnt,Rating)' . 
                                ' VALUES(' . $this->sectionId . ',' . $this->itemId . ',' . $periodId . ',' . 
                                        "'" . $stat['Dtm'] . "',NOW()," . $stat['Cnt'] . ",'" . $stat['Avg'] . "')";
                        break;
                }
            }
            $ret[$periodId] = $this->db->query($sql);
        }
        
        //возвращаем значение (ищем была ли ошибка, если нет возвращается true иначе false)
        return (false === array_search(false, $ret));
    }
    
    /**
     * Обновление статистики зарегистрированного пользователя.
     *  - при добавлении им комментария
     *  - при оставлении им голоса за материал
     *  - при отметке им других комментариев
     *  - при отметке другим пользователем комментария зарегистрированного пользователя
     *
     * @param int $postType тип запроса: 0 - комментарий, 1 - голос, 2 - отметка текущим пользователем чужого комментария, -1 - отметка другим пользователем комментария зарегистрированного пользователя, используется только совместно со вторым параметром $user_id
     * @param int $userId идентификатор зарегистрированного пользователя при отметке другим пользователем его комментария
     * @return bool
     * @thorws \Exception
     */
    protected function updateUserStatistic($postType = 0, $userId = 0)
    {
        if ((0 == $this->userId && -1 != $postType) || (-1 == $postType && 0 == $userId)) {
            return false;
        }
        
        //обсчет статистики за все время
        switch ($postType) {
            case 0:
                //статистика по комментариям
                $sql =  'SELECT COUNT(*) AS CntVal, AVG(CommentStatus) AS AvgVal, MAX(DateCreate) AS DtmVal' . 
                        ' FROM ' . C_DB_TABLE_PREFIX . 'interaction_comments' . 
                        ' WHERE IsDisabled=0 AND UserId=' . $this->userId;
                break;
            case 1:
                //статистика по голосам
                $sql =  'SELECT COUNT(*) AS CntVal, AVG(Rate) AS AvgVal, MAX(DateCreate) AS DtmVal' . 
                        ' FROM ' . C_DB_TABLE_PREFIX . 'interaction_rates' . 
                        ' WHERE IsDisabled=0 AND UserId=' . $this->userId;
                break;
            case 2:
                //статистика по отметкам других комментариев
                $sql =  'SELECT COUNT(*) AS CntVal, AVG(cr.Rate) AS AvgVal, MAX(cr.DateCreate) AS DtmVal' . 
                        ' FROM ' . C_DB_TABLE_PREFIX . 'interaction_comments_rates AS cr' . 
                        ' INNER JOIN ' . C_DB_TABLE_PREFIX . 'interaction_comments AS c ON cr.CommentId=c.Id' . 
                        ' WHERE c.IsDisabled=0 AND cr.IsDisabled=0 AND cr.UserId=' . $this->userId;
                break;
            case -1:
                //статистика по отметкам других пользователей комментариев зарегистрированного пользователя
                $sql =  'SELECT COUNT(*) AS CntVal, AVG(cr.Rate) AS AvgVal, AVG(c.Rating) AS Rating, MAX(cr.DateCreate) AS DtmVal' . 
                        ' FROM ' . C_DB_TABLE_PREFIX . 'interaction_comments_rates AS cr' . 
                        ' INNER JOIN ' . C_DB_TABLE_PREFIX . 'interaction_comments AS c ON cr.CommentId=c.Id' . 
                        ' WHERE c.IsDisabled=0 AND cr.IsDisabled=0 AND c.UserId=' . $userId;
                break;
            default:
                throw new \Exception('Unsupported parameter value $postType=' . $postType . '.');
        }
        if (null === $item = $this->db->getItem($sql)) {
            return false;
        }
        $stats = array();
        $stats[0]['Cnt'] = $item['CntVal'];
        $stats[0]['Avg'] = is_null($item['AvgVal']) ? 0 : $item['AvgVal'];
        if (-1 == $postType) {
            $stats[0]['Rating'] = is_null($item['Rating']) ? 0 : $item['Rating'];
        }
        $stats[0]['Dtm'] = is_null($item['DtmVal']) ? '0000-00-00 00:00:00' : $item['DtmVal'];
        
        //обсчет статистики для периодов
        $periods = $this->getPeriods();
        foreach ($periods as $periodId => $period) {
            switch ($postType) {
                case 0:
                    //статистика по комментариям
                    $sql =  'SELECT COUNT(*) AS CntVal, AVG(CommentStatus) AS AvgVal, MAX(DateCreate) AS DtmVal' . 
                            ' FROM ' . C_DB_TABLE_PREFIX . 'interaction_comments' . 
                            ' WHERE IsDisabled=0 AND UserId=' . $this->userId . 
                            ' AND DateCreate>=DATE_ADD(NOW(), INTERVAL - ' . $period['Period'] . ' ' . $period['Code'] . ')';
                    break;
                case 1:
                    //статистика по голосам
                    $sql =  'SELECT COUNT(*) AS CntVal, AVG(Rate) AS AvgVal, MAX(DateCreate) AS DtmVal' . 
                            ' FROM ' . C_DB_TABLE_PREFIX . 'interaction_rates' . 
                            ' WHERE IsDisabled=0 AND UserId=' . $this->userId . 
                            ' AND DateCreate>=DATE_ADD(NOW(), INTERVAL - ' . $period['Period'] . ' ' . $period['Code'] . ')';
                    break;
                case 2:
                    //статистика по отметкам других комментариев
                    $sql =  'SELECT COUNT(*) AS CntVal, AVG(cr.Rate) AS AvgVal, MAX(cr.DateCreate) AS DtmVal' . 
                            ' FROM ' . C_DB_TABLE_PREFIX . 'interaction_comments_rates AS cr' . 
                            ' INNER JOIN ' . C_DB_TABLE_PREFIX . 'interaction_comments AS c ON cr.CommentId=c.Id' . 
                            ' WHERE c.IsDisabled=0 AND cr.IsDisabled=0 AND cr.UserId=' . $this->userId . 
                            ' AND cr.DateCreate>=DATE_ADD(NOW(), INTERVAL - ' . $period['Period'] . ' ' . $period['Code'] . ')';
                    break;
                case -1:
                    //статистика по отметкам других пользователей комментариев зарегистрированного пользователя
                    $sql =  'SELECT COUNT(*) AS CntVal, AVG(cr.Rate) AS AvgVal, AVG(c.Rating) AS Rating, MAX(cr.DateCreate) AS DtmVal' . 
                            ' FROM ' . C_DB_TABLE_PREFIX . 'interaction_comments_rates AS cr' . 
                            ' INNER JOIN ' . C_DB_TABLE_PREFIX . 'interaction_comments AS c ON cr.CommentId=c.Id' . 
                            ' WHERE c.IsDisabled=0 AND cr.IsDisabled=0 AND c.UserId=' . $userId . 
                            ' AND cr.DateCreate>=DATE_ADD(NOW(), INTERVAL - ' . $period['Period'] . ' ' . $period['Code'] . ')';
                    break;
            }
            if (null === $item = $this->db->getItem($sql)) {
                return false;
            }
            $stats[$periodId]['Cnt'] = $item['CntVal'];
            $stats[$periodId]['Avg'] = is_null($item['AvgVal']) ? 0 : $item['AvgVal'];
            if (-1 == $postType) {
                $stats[$periodId]['Rating'] = is_null($item['Rating']) ? 0 : $item['Rating'];
            }
            $stats[$periodId]['Dtm'] = is_null($item['DtmVal']) ? '0000-00-00 00:00:00' : $item['DtmVal'];
        }
        
        $ret = array();
        
        //обновление статистики за все время
        //обновление статистики для периодов
        foreach ($stats as $periodId => $stat) {
            $id = 0;
            if (-1 != $postType) {
                $sql =  'SELECT Id FROM ' . C_DB_TABLE_PREFIX . 'interaction_stat_users' . 
                        ' WHERE UserId=' . $this->userId . 
                        ' AND PeriodId=' . $periodId;
            } else {
                $sql =  'SELECT Id FROM ' . C_DB_TABLE_PREFIX . 'interaction_stat_users' . 
                        ' WHERE UserId=' . $userId . 
                        ' AND PeriodId=' . $periodId;
            }
            $id = $this->db->getValue($sql, 'Id');
            
            if (0 != $id) {
                switch ($postType) {
                    case 0:
                        $sql =  'UPDATE ' . C_DB_TABLE_PREFIX . 'interaction_stat_users' . 
                                " SET DateComment='" . $stat['Dtm'] . "'," . 
                                    ' DateCommentUpdate=NOW(),' . 
                                    ' CommentsCnt=' . $stat['Cnt'] . ',' . 
                                    " CommentsStatusAvg='" . $stat['Avg'] . "'" . 
                                ' WHERE Id=' . $id;
                        break;
                    case 1:
                        $sql =  'UPDATE ' . C_DB_TABLE_PREFIX . 'interaction_stat_users' . 
                                " SET DateRate='" . $stat['Dtm'] . "'," . 
                                    ' DateRateUpdate=NOW(),' . 
                                    ' RatesCnt=' . $stat['Cnt'] . ',' . 
                                    " RateAvg='" . $stat['Avg'] . "'" . 
                                ' WHERE Id=' . $id;
                        break;
                    case 2:
                        $sql =  'UPDATE ' . C_DB_TABLE_PREFIX . 'interaction_stat_users' . 
                                " SET DateRateComment='" . $stat['Dtm'] . "'," . 
                                    ' DateRateCommentUpdate=NOW(),' . 
                                    ' RatesCommentsCnt=' . $stat['Cnt'] . ',' . 
                                    " RateCommentsAvg='" . $stat['Avg'] . "'" . 
                                ' WHERE Id=' . $id;
                        break;
                    case -1:
                        $sql =  'UPDATE ' . C_DB_TABLE_PREFIX . 'interaction_stat_users' . 
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
                        $sql =  'INSERT INTO ' . C_DB_TABLE_PREFIX . 'interaction_stat_users' . 
                                ' (UserId,PeriodId,DateComment,DateCommentUpdate,CommentsCnt,CommentsStatusAvg)' . 
                                ' VALUES(' . $this->userId . ',' . $periodId . ',' . 
                                        "'" . $stat['Dtm'] . "',NOW()," . $stat['Cnt'] . ",'" . $stat['Avg'] . "')";
                        break;
                    case 1:
                        $sql =  'INSERT INTO ' . C_DB_TABLE_PREFIX . 'interaction_stat_users' . 
                                ' (UserId,PeriodId,DateRate,DateRateUpdate,RatesCnt,RateAvg)' . 
                                ' VALUES(' . $this->userId . ',' . $periodId . ',' . 
                                        "'" . $stat['Dtm'] . "',NOW()," . $stat['Cnt'] . ",'" . $stat['Avg'] . "')";
                        break;
                    case 2:
                        $sql =  'INSERT INTO ' . C_DB_TABLE_PREFIX . 'interaction_stat_user' . 
                                ' (UserId,PeriodId,DateRateComment,DateRateCommentUpdate,RatesCommentsCnt,RateCommentsAvg)' . 
                                ' VALUES(' . $this->userId . ',' . $periodId . ',' . 
                                        "'" . $stat['Dtm'] . "',NOW()," . $stat['Cnt'] . ",'" . $stat['Avg'] . "')";
                        break;
                    case -1:
                        $sql =  'INSERT INTO ' . C_DB_TABLE_PREFIX . 'interaction_stat_users' . 
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
    
    /**
     * Отправка уведомления на почту.
     * 
     * @param array $data
     * @return bool
     */
    protected function sendMail(array $data)
    {
        if (null === $this->mailTo) {
            return false;
        }
        
        $messenger = new Messenger($this->config);
        $headers = '';
        if (null !== $this->mailFrom) {
            $headers .= 'From: ' . $this->mailFrom . "\r\n" . 
                        'Reply-To: ' . $this->mailFrom . "\r\n";
        }
        if ($this->settings->mailFormatHtml) {
            $headers .=  'Content-type: text/html; charset=utf-8 ';
        }
        $marksValues = array(
            $this->getUrl(), 
            date('Y.m.d H:i:s'), 
            $data['IP'], 
            $data['Text'], 
            $data['Author'], 
            $data['Email'], 
            $data['Url'], 
            $data['Status'], 
            $data['Rate'], 
        );
        if ('' == $headers) {
            return $messenger->sendEmail(
                $this->mailTo, 
                str_replace($this->messageMarks, $marksValues, $this->mailSubject), 
                str_replace($this->messageMarks, $marksValues, $this->mailBody)
            );
        } else {
            return $messenger->sendEmail(
                $this->mailTo, 
                str_replace($this->messageMarks, $marksValues, $this->mailSubject), 
                str_replace($this->messageMarks, $marksValues, $this->mailBody), 
                null, 
                $headers
            );
        }
    }
}
