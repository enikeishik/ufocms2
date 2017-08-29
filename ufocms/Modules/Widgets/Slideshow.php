<?php
/**
 * @copyright
 */

namespace Ufocms\Modules\Widgets;

/**
 * Widget class
 */
class Slideshow extends \Ufocms\Modules\Widget
{
    protected $allowedExts = 'jpg,jpeg,gif,png';
    
    /**
     * @see parent
     */
    protected function setContext()
    {
        parent::setContext();
        
        $items = array();
        if (is_array($this->params)) {
            $dir = $this->config->rootPath . $this->params['Folder'];
            if (file_exists($dir)) {
                $exts = explode(',', $this->allowedExts);
                foreach ($exts as $ext) {
                    foreach (glob($dir . '/*.' . $ext) as $file) {
                        $items[] = $file;
                    }
                }
                array_walk(
                    $items,
                    function (&$item, $key, $len) {
                        $item = substr($item, $len);
                    },
                    strlen($dir = $this->config->rootPath)
                );
                if ($this->params['Random']) {
                    shuffle($items);
                } else {
                    sort($items);
                }
            }
        }
        
        $this->context = array_merge(
            $this->context, 
            array(
                'duration' => $this->params['Duration'],
                'items' => $items
            )
        );
    }
}
