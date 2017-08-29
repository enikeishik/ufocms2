<?php
require_once 'presets.php';
if (isset($_GET['action'])) {
    $config = null;
    $captcha = new \Ufocms\Frontend\Captcha($config, $debug);
    switch ($_GET['action']) {
    case 'code':
        echo "document.write('" . addcslashes($captcha->get(), "\0..\37\"\'\\") . "');";
        break;
    case 'json':
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($captcha->get(), JSON_HEX_TAG);
        break;
    case 'image':
        $captcha->showImage();
        break;
    }
}
