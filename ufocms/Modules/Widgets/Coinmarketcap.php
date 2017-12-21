<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\Widgets;

use Ufocms\Frontend\Loader;

/**
 * Widget class
 */
class Coinmarketcap extends \Ufocms\Modules\Widget
{
    /**
     * @see parent
     */
    protected function setContext()
    {
        parent::setContext();
        
        $items = array();
        if (is_array($this->params)) {
            $items = $this->getItems();
        }
        
        $this->context = array_merge(
            $this->context, 
            [
                'items' => $items, 
                'priceField' => 'price_' . ('' != $this->params['Convert'] ? strtolower($this->params['Convert']) : 'usd'), 
                'priceTitle' => '' != $this->params['Convert'] ? $this->params['Convert'] : 'USD', 
            ]
        );
    }
    
    /**
     * @return array
     */
    protected function getItems()
    {
        $items = [];
        $loader = new Loader($this->config, $this->debug);
        $loader->setCacheLifetime($this->params['Lifetime'] * 60);
        if (!is_array($this->params['Currency'])) {
            $this->params['Currency'] = [$this->params['Currency']];
        }
        $convert = '';
        if ('' != $this->params['Convert']) {
            $convert = '?convert=' . $this->params['Convert'];
        }
        foreach ($this->params['Currency'] as $url) {
            $loader->setUrl($url . $convert);
            if (null !== $item = $this->parseData($loader->getData())) {
                $items[] = $item;
            }
        }
        return $items;
    }
    
    /**
     * Разбор приходящих данных.
     * @return array|null
     */
    protected function parseData($data)
    {
        return json_decode($data, true);
    }
}
