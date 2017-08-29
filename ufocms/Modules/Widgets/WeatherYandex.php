<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\Widgets;

use Ufocms\Frontend\Loader;

/**
 * Widget class
 */
class WeatherYandex extends \Ufocms\Modules\Widget
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
        $items = [
            'title'  => null, 
            'country'  => null, 
            'sun_rise'  => null, 
            'sunset'    => null, 
            'date'      => null, 
            'day_part'  => [], 
        ];
        
        $dom = new \DOMDocument;
        if (!$dom->load($dataFile)) {
            return $items;
        }
        if (!$dom) {
            return $items;
        }
        
        $xitems = $dom->getElementsByTagName('day');
        if (0 == $xitems->length) {
            return $items;
        }
        $xitems = $xitems->item(0)->childNodes;
        foreach ($xitems as $xitem) {
            if (1 == $xitem->nodeType && array_key_exists($xitem->tagName, $items)) {
                switch ($xitem->nodeName) {
                    case 'title':
                    case 'country':
                    case 'sun_rise':
                    case 'sunset':
                        $items[$xitem->nodeName] = $xitem->nodeValue;
                        break;
                    case 'date':
                        $items[$xitem->nodeName] = $xitem->getAttribute('date');
                        break;
                    case 'day_part':
                        $subitem = ['type' => $xitem->getAttribute('type')];
                        foreach ($xitem->childNodes as $xsubitem) {
                            $subitem[$xsubitem->nodeName] = $xsubitem->nodeValue;
                        }
                        $items[$xitem->nodeName][] = $subitem;
                        break;
                }
                //$this->debug->varDump($xitem, false, false);
            }
        }
        return $items;
    }
}
