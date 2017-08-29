<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\Widgets;

/**
 * Widget class
 */
class WeatherYandex extends \Ufocms\AdminModules\Widget
{
    use Yandex;
    
    /**
     * @see parent
     */
    protected function setFields()
    {
        $this->fields = array(
            array('Type' => 'combo',    'Name' => 'Url',        'Value' => '',  'Title' => 'Путь к источнику данных',           'Edit' => true,     'Items' => 'getSource'),
            array('Type' => 'list',     'Name' => 'Lifetime',   'Value' => 60,  'Title' => 'Запрашивать данные через, мин.',    'Edit' => true,     'Items' => 'getLifetime'),
        );
    }
}
