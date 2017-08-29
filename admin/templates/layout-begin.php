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
<script type="text/javascript" src="scripts.js"></script>
<?php if ((false !== stripos($layout, 'form') && !isset($_GET['code'])) || isset($_GET['wysiwyg'])) { ?>
<script type="text/javascript" src="extensions/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript" src="extensions/tiny_mce_init.php"></script>
<?php } ?>
<?=$this->ui->headCode()?>
</head>
<body>
<div id="frameleft"><div class="framewrap">
<?php include_once 'block-navigation.php'; ?>
</div></div>
<div id="clop"><a>&lt;</a></div>
<div id="framemain"><div class="framewrap">
<?=$this->ui->frameMainHeader()?>
<?php if (!is_null($result = $this->model->getResult())) { ?>
<div class="result"><?=$result?><span class="close" title="Скрыть" onclick="this.parentNode.style.display='none'">X</span></div>
<?php } ?>
