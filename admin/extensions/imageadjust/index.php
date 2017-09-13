<?php
namespace Ufocms\Backend;

ini_set('display_errors', '1');
ini_set('error_reporting', E_ALL);
ini_set('scream.enabled', true);
date_default_timezone_set('Europe/Moscow');
setlocale(LC_ALL, 'ru_RU.utf-8', 'rus_RUS.utf-8', 'ru_RU.utf8');
mb_internal_encoding('UTF-8');

require_once '../../../config.php';
require_once '../../autoload.php';

$config = new Config();

$ret = '';
if (isset($_GET['adjust'])) {
    try {
        $image = array('path' => $_POST['path'], 
                       'srcx' => $_POST['srcx'], 
                       'srcy' => $_POST['srcy'], 
                       'srcw' => $_POST['srcw'], 
                       'srch' => $_POST['srch'], 
                       'dstw' => $_POST['dstw'], 
                       'dsth' => $_POST['dsth'], 
                       'jpegQquality' => 75);
        require_once 'ImageAdjuster.php';
        $ia = new Extensions\ImageAdjuster($config);
        $ret = $ia->adjust($image);
    } catch (\Exception $e) {
        $ret = 'Error: ' . $e->getMessage();
    }
} else if (isset($_GET['key'])) {
    require_once 'ImageAdjuster.php';
    $ia = new Extensions\ImageAdjuster($config);
    $ret = $ia->getKey();
}
echo $ret;
