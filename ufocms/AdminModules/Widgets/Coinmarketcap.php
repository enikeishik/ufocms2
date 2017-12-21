<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\Widgets;

/**
 * Widget class
 */
class Coinmarketcap extends \Ufocms\AdminModules\Widget
{
    protected $source = 'https://api.coinmarketcap.com/v1/ticker/';
    /**
     * @see parent
     */
    protected function setFields()
    {
        $this->fields = [
            ['Type' => 'mslist',   'Name' => 'Currency',   'Value' => 'bitcoin',   'Title' => 'Криптовалюта',                      'Edit' => true,     'Items' => 'getCurrencies'],
            ['Type' => 'slist',    'Name' => 'Convert',    'Value' => '',          'Title' => 'Курс по отношению к валюте',        'Edit' => true,     'Items' => 'getConverts'],
            ['Type' => 'list',     'Name' => 'Lifetime',   'Value' => 60,          'Title' => 'Запрашивать данные через, мин.',    'Edit' => true,     'Items' => 'getLifetime'],
        ];
    }
    
    /**
     * @return array
     */
    protected function getCurrencies()
    {
        $arr = [
            ['Value' => 'bitcoin',              'Title' => 'Bitcoin BTC'], 
            ['Value' => 'ethereum',             'Title' => 'Ethereum ETH'], 
            ['Value' => 'bitcoin-cash',         'Title' => 'Bitcoin Cash BCH'], 
            ['Value' => 'litecoin',             'Title' => 'Litecoin LTC'], 
            ['Value' => 'ripple',               'Title' => 'Ripple XRP'], 
            ['Value' => 'iota',                 'Title' => 'IOTA MIOTA'], 
            ['Value' => 'dash',                 'Title' => 'Dash DASH'], 
            ['Value' => 'monero',               'Title' => 'Monero XMR'], 
            ['Value' => 'bitcoin-gold',         'Title' => 'Bitcoin Gold BTG'], 
            ['Value' => 'ethereum-classic',     'Title' => 'Ethereum Classic ETC'], 
            ['Value' => 'cardano',              'Title' => 'Cardano ADA'], 
        ];
        foreach ($arr as &$a) {
            $a['Value'] = $this->source . $a['Value'] . '/';
        }
        return $arr;
    }
    
    /**
     * @return array
     */
    protected function getConverts()
    {
        return [
            ['Value' => '',     'Title' => 'USD'], 
            ['Value' => 'EUR',  'Title' => 'Euro'], 
            ['Value' => 'RUB',  'Title' => 'Рубли'], 
        ];
    }
    
    /**
     * @return array
     */
    protected function getLifetime()
    {
        $items = [];
        for ($i = 10; $i <= 240; $i += 10) {
            $items[] = ['Value' => $i, 'Title' => $i];
        }
        return $items;
    }
}
