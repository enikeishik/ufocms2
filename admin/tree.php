<?php
/**
 * @copyright
 */

namespace Ufocms\Backend;

require_once 'presets.php';

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
