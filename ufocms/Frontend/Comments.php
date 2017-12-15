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
 * Класс комментариев.
 */
class Comments
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
     * @var array
     */
    protected $settings = null;
    
    /**
     * @var string
     */
    protected $url = null;
    
    /**
     * @var int
     */
    protected $itemsCount = null;
    
    /**
     * @var string
     */
    protected $error = null;
    
    /**
     * @var mixed
     */
    protected $actionResult = null;
    
    /**
     * Объект работы со стэком.
     * @var Stack
     */
    protected $stack = null;
    
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
        $this->error = '';
        $this->actionResult = array(
            'source'    => false, 
            'human'     => false, 
            'db'        => false, 
            'dbrating'  => false, 
            'email'     => false, 
        );
        
        $this->settings = array(
            'commentLimit'      => 1048576, 
            'rateRequired'      => false, 
            'rateMin'           => 1, 
            'rateMax'           => 5, 
            'rateDefault'       => 3, 
            'premoderated'      => false, 
            'blacklistEnabled'  => true, 
            'delayRequired'     => true, 
            'stackFile'         => '~comments_stack.txt', 
            'stackLifetime'     => 6, 
            'mailTo'            => '', 
            'mailSubj'          => '', 
            'mailBody'          => '', 
            'mailHtml'          => true, 
            'marks'             => array('{URL}', '{DTM}', '{IP}', '{RATE}', '{COMMENT}'), 
        );
        
        $uri = $_SERVER['REQUEST_URI'];
        //убираем постраничный вывод списка комментариев из URL
        $uri = preg_replace('/\/comments[0-9]+$/', '', $uri);
        if (false !== $pos = strrpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        //BOOKMARK: close slash
        $this->url = $uri . '/';
        
        $this->stack = new Stack($this->config, $this->debug);
        $this->stack->set($this->settings['stackFile'], $this->settings['stackLifetime']);
    }
    
    /**
     * Установка параметров конфигурации.
     * @param array $settings
     */
    public function set(array $settings)
    {
        $this->settings = array_merge(
            $this->settings, 
            $settings
        );
    }
    
    /**
     * Получение параметров конфигурации.
     * @return array
     */
    public function getSettings()
    {
        return $this->settings;
    }
    
    /**
     * Получение очищенного от добавок класса URL.
     * @return string
     */
    public function getUrl()
    {
        //BOOKMARK: close slash
        return rtrim($this->url, '/');
    }
    
    /**
     * Получение списка комментариев.
     * @param int $page = 1
     * @param int $pageSize = 10
     * @param bool $sortDesc = false
     * @return array|null
     */
    public function getItems($page = 1, $pageSize = 10, $sortDesc = false)
    {
        $cnt = $this->getItemsCount();
        if (null === $cnt) {
            return null;
        }
        if (0 == $cnt) {
            return array();
        }
        
        $sql =  'SELECT id,dtm,ip,' . 
                'comment,comment_sign,comment_email,comment_url,' . 
                'answer,answer_sign,answer_email,answer_url' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'comments' . 
                " WHERE disabled=0 AND url='" . $this->db->addEscape($this->url) . "'" . 
                ' ORDER BY dtm' . ($sortDesc ? ' DESC' : '');
        
        $sql .= ' LIMIT ' . ($page - 1) * $pageSize . ', ' . $pageSize;
        
        return $this->db->getItems($sql);
    }
    
    /**
     * Получение общего количества комментариев.
     * @return int|null
     */
    public function getItemsCount()
    {
        if (null == $this->itemsCount) {
            $sql =  'SELECT COUNT(*) AS Cnt FROM ' . C_DB_TABLE_PREFIX . 'comments' . 
                    " WHERE disabled=0 AND url='" . $this->db->addEscape($this->url) . "'";
            $this->itemsCount = $this->db->getValue($sql, 'Cnt');
        }
        return $this->itemsCount;
    }
    
    /**
     * Получение данных рейтинга.
     */
    public function getRating()
    {
        $sql =  'SELECT id,val,cnt FROM ' . C_DB_TABLE_PREFIX . 'comments_rating ' . 
                "WHERE url='" . $this->db->addEscape($this->url) . "'";
        return $this->db->getItem($sql);
    }
    
    /**
     * Проверка источника и корректности данных при добавлении комменария.
     * @return bool
     */
    protected function check()
    {
        if (!isset($_SERVER['HTTP_REFERER'])) {
            $this->error = 'Referer unset';
            return false;
        }
        if (false === strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST'])) {
            $this->error = 'Referer incorrect';
            return false;
        }
        if (!isset($_POST['comment'])) {
            $this->error = 'POST data unset';
            return false;
        }
        if ($this->settings['rateRequired']) {
            if (!isset($_POST['rate'])) {
                $this->error = 'POST data unset';
                return false;
            }
        }
        if ($this->settings['delayRequired']) {
            $this->stack->clearOld();
            if ($this->stack->dataExists($_SERVER['REMOTE_ADDR'])) {
                $this->error = 'IP temporary blocked';
                return false;
            }
        }
        if ($this->settings['blacklistEnabled']) {
            if ($this->inBlackList($_SERVER['REMOTE_ADDR'])) {
                $this->error = 'IP blocked';
                return false;
            }
        }
        return true;
    }
    
    /**
     * Проверка наличия адреса в черном списке.
     * @param string $ip
     * @return bool
     */
    protected function inBlackList($ip)
    {
        $sql =  'SELECT COUNT(*) AS cnt FROM ' . C_DB_TABLE_PREFIX . 'comments_blacklist ' . 
                "WHERE ip='" . $ip . "' AND (url='" . $this->url . "' OR url='*')";
        if (null === $cnt = $this->db->getValue($sql, 'cnt')) {
            return false;
        }
        return 0 < $cnt;
    }
    
    /**
     * Добавление комментария.
     */
    public function add()
    {
        if (!$this->check()) {
            return;
        }
        $this->actionResult['source'] = true;
        
        //...CAPTCHA
        $this->actionResult['human'] = true;
        
        $item = array();
        if (isset($_POST['rate'])) {
            $rate = (int) $_POST['rate'];
            if ($rate < $this->settings['rateMin'] || $rate > $this->settings['rateMax']) {
                $rate = $this->settings['rateDefault'];
            }
        } else {
            $rate = -1;
        }
        $item['ip'] = $_SERVER['REMOTE_ADDR'];
        $item['info'] = 'HTTP_HOST:            ' . $_SERVER['HTTP_HOST'] . "\n" . 
                        'REQUEST_URI:          ' . $_SERVER['REQUEST_URI'] . "\n" . 
                        'HTTP_REFERER:         ' . $_SERVER['HTTP_REFERER'] . "\n" . 
                        'REQUEST_METHOD:       ' . $_SERVER['REQUEST_METHOD'] . "\n" . 
                        'REMOTE_ADDR:          ' . $_SERVER['REMOTE_ADDR'] . "\n" . 
                        'HTTP_X-FORWARDED-FOR: ' . (isset($_SERVER['HTTP_X-FORWARDED-FOR']) ? $_SERVER['HTTP_X-FORWARDED-FOR'] : '') . "\n" . 
                        'HTTP_ACCEPT_LANGUAGE: ' . (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : '') . "\n" . 
                        'HTTP_USER_AGENT:      ' . $_SERVER['HTTP_USER_AGENT'] . "\n";
        $item['rate'] = $rate;
        $item['comment'] = $_POST['comment'];
        $item['sign'] = isset($_POST['sign']) ? $_POST['sign'] : '';
        $item['email'] = isset($_POST['email']) ? $_POST['email'] : '';
        $item['url'] = isset($_POST['url']) ? $_POST['url'] : '';
        $sql =  'INSERT INTO ' . C_DB_TABLE_PREFIX . 'comments ' . 
                '(url,dtm,ip,info,comment,comment_sign,comment_email,comment_url,rate,disabled) ' . 
                "VALUES('" . $this->db->addEscape($this->url) . "'," . 
                "NOW()," . 
                "'" . $this->db->addEscape(substr($item['ip'], 0, 15)) . "'," . 
                "'" . $this->db->addEscape(htmlspecialchars($item['info'])) . "'," . 
                "'" . $this->db->addEscape(nl2br(htmlspecialchars(substr($item['comment'], 0, $this->settings['commentLimit'])), false)) . "'," . 
                "'" . $this->db->addEscape(nl2br(htmlspecialchars(substr($item['sign'], 0, 255)), false)) . "'," . 
                "'" . $this->db->addEscape(nl2br(htmlspecialchars(substr($item['email'], 0, 255)), false)) . "'," . 
                "'" . $this->db->addEscape(nl2br(htmlspecialchars(substr($item['url'], 0, 255)), false)) . "'," . 
                $rate . ',' . 
                (int) $this->settings['premoderated'] . ')';
        if (!$this->db->query($sql)) {
            return;
        }
        $this->actionResult['db'] = true;
        
        if (0 < $rate) {
            $this->addRating($rate);
        }
        
        $this->sendMail($item);
        
        if ($this->settings['delayRequired']) {
            $this->stack->push($_SERVER['REMOTE_ADDR']);
        }
    }
    
    protected function addRating($rate)
    {
        //check if rating exists
        $sql =  'SELECT COUNT(*) AS cnt FROM ' . C_DB_TABLE_PREFIX . 'comments_rating ' . 
                "WHERE url='" . $this->db->addEscape($this->url) . "'";
        if (null === $cnt = $this->db->getValue($sql, 'cnt')) {
            return;
        }
        
        //create or update rating
        if (0 < $cnt) {
            //get rating info
            $sql =  'SELECT SUM(rate) AS rsm, COUNT(*) AS cnt FROM ' . C_DB_TABLE_PREFIX . 'comments ' . 
                    "WHERE disabled=0 AND rate>0 AND url='" . $this->db->addEscape($this->url) . "'";
            if (null === $item = $this->db->getItem($sql)) {
                return;
            }
            $sql =  'UPDATE ' . C_DB_TABLE_PREFIX . 'comments_rating ' . 
                    "SET val='" . $item['rsm'] / $item['cnt'] . "', cnt=" . $item['cnt'] . ' ' . 
                    "WHERE url='" . $this->db->addEscape($this->url) . "'";
        } else {
            $sql =  'INSERT INTO ' . C_DB_TABLE_PREFIX . 'comments_rating ' . 
                    '(url,val,cnt) ' . 
                    "VALUES('" . $this->db->addEscape($this->url) . "','" . $rate ."',1)";
        }
        $this->actionResult['dbrating'] = $this->db->query($sql);
    }
    
    /**
     * Отправка уведомления по электронной почте.
     * @param array $item
     */
    protected function sendMail($item)
    {
        if ('' == $this->settings['mailTo']) {
            return;
        }
        $values = array(
            $this->url, 
            date('Y.m.d H:i:s'), 
            $item['ip'], 
            $item['rate'], 
            $item['comment']
        );
        $this->actionResult['db'] = mail(
            $this->settings['mailTo'], 
            str_replace($this->settings['marks'], $values, $this->settings['mailSubj']), 
            str_replace($this->settings['marks'], $values, $this->settings['mailBody'])
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
     * @return mixed
     */
    public function getActionResult()
    {
        return $this->actionResult;
    }
}
