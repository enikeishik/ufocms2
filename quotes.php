<?php
require_once 'presets.php';
if (!isset($_GET['groupid'])) {
    exit();
}
$groupId = (int) $_GET['groupid'];
$db = new \Ufocms\Frontend\Db($debug);
$quotes = new \Ufocms\Frontend\Quotes($db, $debug);
header('Content-type: application/json; charset=utf-8');
echo 'quote' . $groupId . '=', json_encode($quotes->get($groupId), JSON_HEX_TAG), ';';
