<?php
/**
 * @copyright
 */

namespace Ufocms\Backend;

ini_set('display_errors', '1');
ini_set('error_reporting', E_ALL);
date_default_timezone_set('Europe/Moscow');
setlocale(LC_ALL, 'ru_RU.utf-8', 'rus_RUS.utf-8', 'ru_RU.utf8');
mb_internal_encoding('UTF-8');

ob_start();

require_once 'autoload.php';

require_once '../config.php';

$debug = null;
if (defined('C_DEBUG') && C_DEBUG) {
    $debug = new \Ufocms\Frontend\Debug();
    //set_error_handler(array('\Ufocms\Frontend\Debug', 'errorHandler'));
}
$params = new Params();
$params->sectionId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$last = isset($_GET['last']) ? (int) $_GET['last'] : 0;
$config = new Config();
$audit = new Audit($config);
$db = new Db($audit, $debug);
$core = new Core($config, $params, $db, $debug);
$tree = new Tree($config, $params, $core, $debug);
header('Content-type: text/html; charset=utf-8');
$tree->render($core->getSectionChildren($params->sectionId), $last);
