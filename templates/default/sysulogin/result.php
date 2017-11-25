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
<div class="uloginresult">
<h1><?=$section['indic']?></h1>
<?php if (!$actionResult['method']) { ?>
    <div>Некорректный источник</div>
<?php } else if (!$actionResult['data']) { ?>
    <div>Некорректные данные</div>
<?php } else if (!$actionResult['service']) { ?>
    <div>Ошибка сервиса <?=$error?></div>
<?php } else { ?>
<div>Действие выполнено удачно</div>
<div>Через пять секунд страница будет перенаправлена на <a href="<?=$from?>">предыдущую страницу</a>.</div>
<script type="text/javascript">setTimeout("location.href='<?=$from?>'", 5000);</script>
<?php } ?>
</div>
