<?php
require_once 'presets.php';
$config = new \Ufocms\Frontend\Config();
$db = new \Ufocms\Frontend\Db($debug);
$xsm = new \Ufocms\Frontend\XmlSitemap($config, $db, $debug);
header('Content-type: text/html; charset=utf-8');
$xsm->generate();
