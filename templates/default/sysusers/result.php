<?php
/**
 * @var \Ufocms\Modules\View $this
 * @var \Ufocms\Frontend\Core $core
 * @var \Ufocms\Frontend\Tools $tools
 * @var array|null $site
 * @var array|null $section
 * @var array|null $settings
 * @var mixed $actionResult
 * @var string $from
 * @var string $error                   only for debug, do not use on production
 */
?>
<div class="usersresult">
<h1><?=$section['indic']?></h1>
<?php if (!$actionResult['referer']) { ?>
    <div>Некорректный источник</div>
<?php } else if (!$actionResult['form']) { ?>
    <div>Не заполнены обязательные поля</div>
<?php } else if (!$actionResult['human']) { ?>
    <div>Неверный код подтверждения</div>
<?php } else if (!$actionResult['correct']) { ?>
    <div>Некорректные данные</div>
    <?php if ('login' == $actionResult['method']) { ?>
    <?php include 'formrecover.php'; ?>
    <?php } ?>
<?php } else if (!$actionResult['enabled']) { ?>
    <div>Учетная запись заблокирована</div>
<?php } else if (!$actionResult['db']) { ?>
    <div>Ошибка базы данных <?=$error?></div>
<?php } else { ?>
<div>Действие выполнено удачно</div>
<div>Через пять секунд страница будет перенаправлена на <a href="<?=$from?>">предыдущую страницу</a>.</div>
<script type="text/javascript">setTimeout("location.href='<?=$from?>'", 5000);</script>
<?php } ?>
</div>
