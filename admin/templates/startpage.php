<?php require_once 'layout-begin.php'; ?>

<?php if (C_DB_READONLY) { ?>
<div style="border: solid #090 2px; padding: 5px; margin-bottom: 10px;">Database in readonly mode</div>
<?php } ?>

<?php
$sections = $this->core->getSections('id');
if (null === $sections || 0 == count($sections)) {
?>
<div class="widget">
<div class="caption">Структура сайта</div>
<p>Сайт еще не содержит разделов. Сформировать структуру сайта можно 
в разделе <a href="?<?=$this->config->paramsNames['coreModule']?>=sections">Структура сайта</a>.</p>
</div>
<?php
}
unset($sections);
?>

<?php
$site = $this->core->getSite();
if (!isset($site['SiteTitle']) || '' == $site['SiteTitle']['PValue']) {
?>
<div class="widget">
<div class="caption">Параметры сайта</div>
<p>У сайта отсутствуют такие общие параметры как название. Задать параметры сайта можно 
в разделе <a href="?<?=$this->config->paramsNames['coreModule']?>=site">Параметры сайта</a>.</p>
</div>
<?php
}
unset($site);
?>

<?php
foreach (glob($this->config->rootPath . '/ufocms/AdminModules/*', GLOB_ONLYDIR) as $dirname) {
    if (file_exists($dirname . '/AdminWidget.php')) {
        $this->adminWidget(basename($dirname))->render();
    }
}
?>

<?php require_once 'layout-end.php'; ?>