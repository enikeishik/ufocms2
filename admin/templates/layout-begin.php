<?php require_once 'layout-head.php'; ?>
<body>
<div id="frameleft"><div class="framewrap">
<?php include_once 'block-navigation.php'; ?>
</div></div>
<div id="clop"><a>&lt;</a></div>
<div id="framemain"><div class="framewrap">
<?=$this->ui->frameMainHeader()?>
<?=$this->ui->masterHeader()?>
<?php if (isset($this->model) && !is_null($result = $this->model->getResult())) { ?>
<div class="result">
    <span class="close" title="Скрыть" onclick="this.parentNode.style.display='none'">X</span>
    <?=$result?>
</div>
<?php } ?>
