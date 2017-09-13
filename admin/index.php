<?php
require_once 'presets.php';
$controller = new \Ufocms\Backend\Main($debug);
$controller->dispatch();
