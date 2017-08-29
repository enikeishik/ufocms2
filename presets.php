<?php
ini_set('display_errors', '1');
ini_set('error_reporting', E_ALL);
ini_set('scream.enabled', true);
date_default_timezone_set('Europe/Moscow');
setlocale(LC_ALL, 'ru_RU.utf-8', 'rus_RUS.utf-8', 'ru_RU.utf8');
mb_internal_encoding('UTF-8');

ob_implicit_flush(false);
ob_start();

require_once 'autoload.php';
require_once 'config.php';
$debug = null;
if (defined('C_DEBUG') && C_DEBUG) {
    $debug = new \Ufocms\Frontend\Debug();
    //set_error_handler(array('\Ufocms\Frontend\Debug', 'errorHandler'));
}
