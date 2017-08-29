<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\Widgets;

use Ufocms\Frontend\Loader;

/**
 * Widget class
 */
class DayEvents extends \Ufocms\Modules\Widget
{
    /**
     * @see parent
     */
    protected function setContext()
    {
        parent::setContext();
        
        $this->context = array_merge(
            $this->context, 
            array(
                'items'         => $this->getItems(), 
                'showYesterday' => $this->params['Yesterday'], 
                'showTommorow'  => $this->params['Tommorow'], 
            )
        );
    }
    
    /**
     * Разбор XML.
     * @return array
     */
    protected function getItems()
    {
        $items = [
            'Yesterday' => [], 
            'Today'     => [], 
            'Tommorow'  => [], 
        ];
        $file = $this->config->rootPath . $this->params['EventsFile'];
        if (!file_exists($file)) {
            return $items;
        }
        
        $content = @file_get_contents($file);
        if (!$content) {
            return $items;
        }
        
        $yesterday = date('m-d', strtotime('-1 day'));
        $today = date('m-d');
        $tommorow = date('m-d', strtotime('+1 day'));
        $lines = explode("\n", $content);
        foreach ($lines as $line) {
            if ('' == $line) {
                continue;
            }
            $event = explode("\t", trim($line));
            if (2 == count($event)) {
                if (10 != strlen($event[0])) {
                    continue;
                }
                $monthday = substr($event[0], 5);
                if ($monthday == $yesterday) {
                    $items['Yesterday'][] = ['Date' => $event[0], 'Text' => $event[1]];
                } else if ($monthday == $today) {
                    $items['Today'][] = ['Date' => $event[0], 'Text' => $event[1]];
                } else if ($monthday == $tommorow) {
                    $items['Tommorow'][] = ['Date' => $event[0], 'Text' => $event[1]];
                }
            }
        }
        
        return $items;
    }
}
