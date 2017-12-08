<?php require_once 'templates/layout-begin.php'; ?>

<h2>Обновлениe системы</h2>

<p>Последнее обновление системы: <?=$this->model->getLocalSystemDate()?></p>
<p>Последнее обновление репозитория: <?=$this->model->getRepositoryDate()?></p>

<?php if ($this->model->isOutofdate()) { ?>
    <?php /* if ($this->model->isCanAutoUpdate()) { ?>
        <p>Можно <a href="<?=$this->basePath?>&action=update" onclick="return confirm('Начать процедуру обновления?')">провести автоматическое обновление системы</a>.</p>
    <?php } else { */ ?>
        <p>Можно провести обновление системы в ручном режиме, для этого<br>
        скачайте архив с последней версией системы из <a href="<?=$this->config->repositoryUrl?>" target="_blank">репозитория</a>.</p>
    <?php //} ?>
    <p>Перед проведением обновления системы настоятельно рекомендуется<br>
    произвести резервное копирование</a> системы и пользовательских настроек.</p>
<?php } else { ?>
    <p>Обновления системы не требуется</p>
<?php } ?>

<?php require_once 'templates/layout-end.php'; ?>