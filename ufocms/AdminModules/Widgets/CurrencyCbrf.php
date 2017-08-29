<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\Widgets;

/**
 * Widget class
 */
class CurrencyCbrf extends \Ufocms\AdminModules\Widget
{
    /**
     * @see parent
     */
    protected function setFields()
    {
        $this->fields = array(
            array('Type' => 'combo',    'Name' => 'Url',        'Value' => '',  'Title' => 'Путь к источнику данных',           'Edit' => true,     'Items' => 'getSources'),
            array('Type' => 'list',     'Name' => 'Lifetime',   'Value' => 60,  'Title' => 'Запрашивать данные через, мин.',    'Edit' => true,     'Items' => 'getLifetime'),
        );
    }
    
    /**
     * @return array
     */
    protected function getSources()
    {
        return array(
            array('Value' => 'http://www.cbr.ru/scripts/XML_daily.asp', 'Title' => 'ЦБ РФ'), 
        );
    }
    
    /**
     * @return array
     */
    protected function getLifetime()
    {
        $items = array();
        for ($i = 10; $i <= 240; $i += 10) {
            $items[] = array('Value' => $i, 'Title' => $i);
        }
        return $items;
    }
}
