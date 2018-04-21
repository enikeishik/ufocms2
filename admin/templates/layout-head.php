<?php
/**
 * @var \Ufocms\AdminModules\View $this
 * @var string $layout
 */

header('Content-type: text/html; charset=utf-8');
$this->ui->headers();
?><!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title><?=$this->ui->headTitle()?></title>
<link rel="stylesheet" type="text/css" href="templates/styles.css" />
<script type="text/javascript" src="templates/scripts.js"></script>
<?=$this->ui->headCode()?>
</head>
