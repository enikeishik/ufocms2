<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\Widgets;

use Ufocms\Frontend\Loader;

/**
 * Widget class
 */
class WeatherGismeteo extends \Ufocms\Modules\Widget
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
        $dom = new \DOMDocument();
        if (!@$dom->load($dataFile)) {
            return array();
        }
        if (!$dom) {
            return array();
        }
        $items = array();
        $xitems = $dom->getElementsByTagName('TOWN');
        if (0 < $xitems->length) {
            $xitem = $xitems->item(0);
            $items['TOWN'] = array(
                'sname' => iconv('CP1251', 'UTF-8', urldecode($xitem->getAttribute('sname'))), 
                'latitude' => $xitem->getAttribute('latitude'), 
                'longitude' => $xitem->getAttribute('longitude'), 
            );
        }
        $xitems = $dom->getElementsByTagName('FORECAST');
        for ($i = 0; $i < $xitems->length; $i++) {
            $xitem = $xitems->item($i);
            if (1 == $xitem->nodeType) {
                $item = array();
                
                //echo $i . $xitem->nodeName . '<br>';
                $item['FORECASTday']     = $xitem->getAttribute('day');
                $item['FORECASTmonth']   = $xitem->getAttribute('month');
                $item['FORECASTyear']    = $xitem->getAttribute('year');
                $item['FORECASThour']    = $xitem->getAttribute('hour');
                $item['FORECASTtod']     = $xitem->getAttribute('tod');
                $item['FORECASTpredict'] = $xitem->getAttribute('predict');
                $item['FORECASTweekday'] = $xitem->getAttribute('weekday');
                
                $childs = $xitem->childNodes;
                for ($j = 0; $j < $childs->length; $j++) {
                    $child = $childs->item($j);
                    if (1 == $child->nodeType) {
                        //echo $child->nodeName . '<br>';
                        switch ($child->nodeName) {
                            case 'PHENOMENA':
                                $item['PHENOMENAcloudiness']    = $child->getAttribute('cloudiness');
                                $item['PHENOMENAprecipitation'] = $child->getAttribute('precipitation');
                                $item['PHENOMENArpower']        = $child->getAttribute('rpower');
                                $item['PHENOMENAspower']        = $child->getAttribute('spower');
                                break;
                            case 'PRESSURE':
                                $item['PRESSUREmax'] = $child->getAttribute('max');
                                $item['PRESSUREmin'] = $child->getAttribute('min');
                                break;
                            case 'TEMPERATURE':
                                $item['TEMPERATUREmax'] = $child->getAttribute('max');
                                $item['TEMPERATUREmin'] = $child->getAttribute('min');
                                break;
                            case 'RELWET':
                                $item['RELWETmax'] = $child->getAttribute('max');
                                $item['RELWETmin'] = $child->getAttribute('min');
                                break;
                            case 'HEAT':
                                $item['HEATmax'] = $child->getAttribute('max');
                                $item['HEATmin'] = $child->getAttribute('min');
                                break;
                            case 'WIND':
                                $item['WINDmax'] = $child->getAttribute('max');
                                $item['WINDmin'] = $child->getAttribute('min');
                                $item['WINDdirection'] = $child->getAttribute('direction');
                                break;
                        }
                    }
                }
                $items[] = $item;
            }
        }
        return $items;
        /*
        Описание формата:

        TOWN информация о пункте прогнозирования: 
            index уникальный пятизначный код города 
            sname закодированное название города 
            latitude широта в целых градусах 
            longitude долгота в целых градусах 
        FORECAST информация о сроке прогнозирования: 
            day, month, year дата, на которую составлен прогноз в данном блоке 
            hour местное время, на которое составлен прогноз 
            tod время суток, для которого составлен прогноз: 0 - ночь 1 - утро, 2 - день, 3 - вечер 
            weekday день недели, 1 - воскресенье, 2 - понедельник, и т.д. 
            predict заблаговременность прогноза в часах 
        PHENOMENA  атмосферные явления: 
            cloudiness облачность по градациям:  0 - ясно, 1- малооблачно, 2 - облачно, 3 - пасмурно 
            precipitation тип осадков: 4 - дождь, 5 - ливень, 6,7 – снег, 8 - гроза, 9 - нет данных, 10 - без осадков 
            rpower интенсивность осадков, если они есть. 0 - возможен дождь/снег, 1 - дождь/снег 
            spower вероятность грозы, если прогнозируется: 0 - возможна гроза, 1 - гроза 
        PRESSURE атмосферное давление, в мм.рт.ст. 
            min, max
        TEMPERATURE температура воздуха, в градусах Цельсия 
            min, max
        WIND приземный ветер 
            min, max минимальное и максимальное значения средней скорости ветра, без порывов 
            direction  направление ветра в румбах, 0 - северный, 1 - северо-восточный,  и т.д. 
        RELWET относительная влажность воздуха, в % 
            min, max
        HEAT комфорт - температура воздуха по ощущению одетого по сезону человека, выходящего на улицу 
            min, max
        */
    }
}
