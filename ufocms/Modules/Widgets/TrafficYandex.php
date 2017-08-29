<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\Widgets;

use Ufocms\Frontend\Loader;

/**
 * Widget class
 */
class TrafficYandex extends \Ufocms\Modules\Widget
{
    /**
     * @see parent
     */
    protected function setContext()
    {
        parent::setContext();
        
        $item = array();
        if (is_array($this->params)) {
            $loader = new Loader($this->config, $this->debug);
            $loader->setUrl($this->params['Url']);
            $loader->setCacheLifetime($this->params['Lifetime'] * 60);
            $loader->setDataChecker(array(new \DOMDocument(), 'loadXML'));
            $item = $this->parseXml($loader->getDataFile());
        }
        
        $this->context = array_merge(
            $this->context, 
            array(
                'item' => $item
            )
        );
    }
    
    /**
     * Разбор XML.
     * @return array
     */
    protected function parseXml($dataFile)
    {
        $dom = new \DOMDocument;
        if (!$dom->load($dataFile)) {
            return array();
        }
        if (!$dom) {
            return array();
        }
        $item = array();
        $xitems = $dom->getElementsByTagName('region');
        if (0 < $xitems->length) {
            $xitem = $xitems->item(0);
            if (1 == $xitem->nodeType) {
                $childs = $xitem->childNodes;
                for ($j = 0; $j < $childs->length; $j++) {
                    $child = $childs->item($j);
                    if (1 == $child->nodeType && !isset($item[$child->nodeName])) {
                        $item[$child->nodeName] = $child->nodeValue;
                    }
                }
            }
            
            $xitem = $xitems->item(1);
            if (1 == $xitem->nodeType) {
                $childs = $xitem->childNodes;
                for ($j = 0; $j < $childs->length; $j++) {
                    $child = $childs->item($j);
                    if (1 == $child->nodeType && !isset($item[$child->nodeName])) {
                        $item[$child->nodeName] = $child->nodeValue;
                    }
                }
            }
        }
        return $item;
    }
}
