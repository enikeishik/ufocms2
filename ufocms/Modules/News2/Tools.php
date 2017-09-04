<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\News2;

/**
 * Module helpful functionality, append to View, Insertion and Widget
 */
trait Tools
{
    /**
     * @param array $item
     * @param array $settings
     * @return string
     */
    protected function getAnnounce($item, $settings)
    {
        if ('' != $item['Announce']) {
            return strip_tags($item['Announce']);
        } else if (false !== $pos = strpos($item['Body'], C_SITE_PAGEBREAK_SEPERATOR)) {
            return $this->tools->getTextPartBySeparator($item['Body'], C_SITE_PAGEBREAK_SEPERATOR);
        } else {
            if (array_key_exists('AnnounceLength', $settings)) {
                $cutLen = (int) $settings['AnnounceLength'];
            } else if (array_key_exists('ItemsLength', $settings)) {
                $cutLen = (int) $settings['ItemsLength'];
            } else {
                $cutLen = 0;
            }
            if (0 < $cutLen) {
                return $this->tools->cutNice($item['Body'], $cutLen);
            } else if (-1 == $cutLen) {
                return $this->tools->getFirstParagraph($item['Body']);
            } else {
                return '';
            }
        }
    }
}
