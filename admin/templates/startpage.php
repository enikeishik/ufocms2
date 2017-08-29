<?php require_once 'layout-begin.php'; ?>

<?php if(!isset($_COOKIE['disablewarning'])) { ?>
<div style="border: solid #f90 2px; padding: 5px; margin-bottom: 10px;">
<span class="close" title="Скрыть" onclick="this.parentNode.style.display='none';document.cookie='disablewarning=1;expires=<?=date(DATE_COOKIE, time() + (3600 * 24 * 7))?>'">X</span>
<p><b style="color: #f90;">Бета-версия!</b> Система находится в стадии бета-версии.</p>
<p>Это означает что реализованы основные функции системы, но различные удобства и вспомогательные функции могут быть недоступны.</p>
<p>Система может работать нестабильно, рекомендуется использовать систему только в целях тестирования.</p>
</div>
<?php } ?>

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