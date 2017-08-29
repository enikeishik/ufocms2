<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\Widgets;

use Ufocms\Frontend\Loader;

/**
 * Widget class
 */
class CurrencyCbrf extends \Ufocms\Modules\Widget
{
    /**
     * @see parent
     */
    protected function setContext()
    {
        parent::setContext();
        
        $items = array();
        if (is_array($this->params)) {
            $loader = new Loader($this->config, $this->debug);
            $loader->setUrl($this->params['Url']);
            $loader->setCacheLifetime($this->params['Lifetime'] * 60);
            $loader->setDataChecker(array(new \DOMDocument(), 'loadXML'));
            $items = $this->parseXml($loader->getDataFile());
        }
        
        $this->context = array_merge(
            $this->context, 
            array(
                'items' => $items
            )
        );
    }
    
    /**
     * Разбор XML.
     * @return array
     */
    protected function parseXml($dataFile)
    {
        $items = ['Currency' => ['Name' => '', 'Value' => '']];
        
        $dom = new \DOMDocument;
        if (!$dom->load($dataFile)) {
            return $items;
        }
        if (!$dom) {
            return $items;
        }
        
        $xitems = $dom->getElementsByTagName('Valute');
        if (0 == $xitems->length) {
            return $items;
        }
        foreach ($xitems as $xitem) {
            if (1 != $xitem->nodeType) {
                continue;
            }
            $code = '';
            $name = '';
            $value = '';
            foreach ($xitem->childNodes as $xsubitem) {
                if (1 != $xsubitem->nodeType) {
                    continue;
                }
                switch ($xsubitem->nodeName) {
                    case 'CharCode': $code = $xsubitem->nodeValue; break;
                    case 'Name': $name = $xsubitem->nodeValue; break;
                    case 'Value': $value = (float) str_replace(',', '.', $xsubitem->nodeValue); break;
                }
                //$this->debug->varDump($xsubitem, false, false);
            }
            $items[$code] = ['Name' => $name, 'Value' => $value];
        }
        return $items;
    }
}
