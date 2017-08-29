<?php
require_once 'presets.php';
$controller = new \Ufocms\Frontend\Main($debug);
$controller->dispatch();
