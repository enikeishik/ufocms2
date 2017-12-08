<?php require_once 'templates/layout-begin.php'; ?>

<h2>Резервные копии системы</h2>
<p><a href="<?=$this->basePath?>&action=backupsystem">Скачать резервную копию системы</a></p>
<p><a href="<?=$this->basePath?>&action=backupuser">Скачать резервную копию пользовательских шаблонов и настроек</a></p>

<?php require_once 'templates/layout-end.php'; ?>