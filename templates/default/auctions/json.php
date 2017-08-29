<?php
/**
 * @var \Ufocms\Modules\View $this
 * @var \Ufocms\Frontend\Tools $tools
 * @var array|null $site
 * @var array|null $section
 * @var array|null $settings
 * @var array|null $item
 */

//JSON only output
if (!$item['IsClosed']) {
    if ($item['IsStarted']) {
        $displayFields = array('UserId', 'IsClosed', 'IsStarted', 'DateStart', 'DateStop', 'DateStep', 'Step', 'StepTime', 'PriceStart', 'PriceCurrent', 'ViewedCnt');
    } else {
        $displayFields = array('UserId', 'IsClosed', 'IsStarted', 'DateStart', 'DateStop', 'ViewedCnt');
    }
} else {
    $displayFields = array('UserId', 'IsClosed', 'IsStarted', 'DateStart', 'DateStop', 'PriceStart', 'PriceCurrent', 'ViewedCnt');
}
$jsonData = array('UpdateTimeout' => $settings['UpdateTimeout']);
foreach ($item as $field => $value) {
    if (in_array($field, $displayFields)) {
        $jsonData[$field] = $value;
    }
}
header('Content-type: application/json; charset=utf-8');
echo json_encode($jsonData);
