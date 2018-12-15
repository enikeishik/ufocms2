<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\News;

/**
 *  Класс получения данных из RSS потока в виде массива элементов.
 */
class RssReader
{
    protected $root = '';
    protected $url = '';
    protected $socket_timeout = 5;
    protected $user_agent = '';
    protected $attempts_count = 5;
    protected $arr_items = array();
    
    public function __construct($url)
    {
        $this->url = $url;
        for ($i = 0; $i < $this->attempts_count; $i++) {
            $xml = $this->loadXml();
            if ($this->checkXml($xml)) {
                $this->openXml($xml);
                break;
            }
        }
    }
    
    protected function loadXml()
    {
        @ini_set('allow_url_fopen', 1);
        @ini_set('default_socket_timeout', $this->socket_timeout);
        //@ini_set('user_agent', $this->user_agent);
        $context = stream_context_create(
            array(
                'http'  => array(
                    'header'    => 'Connection: close', 
                    'timeout'   => $this->socket_timeout
                ),
                'ssl'   => array(
                    'verify_peer'       => false, 
                    'verify_peer_name'  => false
                ),
            )
        );
        return file_get_contents($this->url, false, $context);
    }
    
    protected function checkXml($xml)
    {
        $dom = new \DOMDocument;
        if (@$dom->loadXML($xml)) {
            return $dom;
        } else {
            return false;
        }
    }
    
    protected function openXml($xml)
    {
        $dom = new \DOMDocument;
        if (!@$dom->loadXML($xml) || !$dom) {
            return false;
        }
        //echo "xmlEncoding: " . $dom->xmlEncoding . "<br>\n";
        $items = $dom->getElementsByTagName("item");
        for ($i = 0; $i < $items->length; $i++) {
            $child = $items->Item($i)->firstChild;
            $item = array();
            while ($child) {
                $val = $child->nodeValue;
                /*
                //XML в PHP всегда идет в UTF-8, поэтому перекодируем
                //echo $val . " > ";
                if (function_exists('mb_convert_variables')) {
                    mb_convert_variables("windows-1251", "UTF-8", $val);
                } else if (function_exists('iconv')) {
                    $val = iconv("UTF-8", "windows-1251", $val);
                } else {
                    return false;
                }
                //echo $val . "<br>\n";
                */
                if ('enclosure' != $child->nodeName) {
                    $item[$child->nodeName] = $val;
                } else {
                    $item[$child->nodeName] = $child->getAttribute('url');
                }
                $child = $child->nextSibling;
            }
            $this->arr_items[] = $item;
        }
    }
    
    public function getItems()
    {
        return $this->arr_items;
    }
}
