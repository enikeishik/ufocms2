<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\Shop;

/**
 * View model class
 */
class ViewExport extends \Ufocms\AdminModules\View
{
    const ERR_NO_ITEMS = 'There is no items';
    const ERR_CREATE_FILE = 'Can not create export file';
    const EXPORT_FILE_NAME = 'export.csv';
    
    public function render($layout = null, $ui = null, $uiParams = null, $uiParamsAppend = false)
    {
        $items = $this->model->getItems();
        if (is_null($items)) {
            throw new \Exception(self::ERR_NO_ITEMS);
        }
        
        $exportFile = $this->config->rootPath . '/tmp/' . self::EXPORT_FILE_NAME;
        if (false === $file = fopen($exportFile, 'w')) {
            throw new \Exception(self::ERR_CREATE_FILE);
        }
        foreach ($items as $item) {
            unset($item['itemid']);
            fputcsv($file, array_values($item));
        }
        fclose($file);
        
        ob_clean();
        header('Content-type: text/plain; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . self::EXPORT_FILE_NAME . '"'); 
        readfile($exportFile);
        exit();
    }
}
