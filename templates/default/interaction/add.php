<?php
/**
 * @var \Ufocms\Modules\View $this
 * @var \Ufocms\Frontend\Tools $tools
 * @var array|null $site
 * @var array|null $section
 * @var array|null $settings
 * @var mixed $actionResult
 * @var string $error                   only for debug, do not use on production
 * @var string $path
 * @var array|null $options
 */
?>
<div class="commentsadd">
    <a name="comments"></a>
    <h5>Комментарии</h5>
<?php if (!$actionResult['source']) { ?>
    <div>Ошибка проверки источника <?=$error?></div>
    
<?php } else if (!$actionResult['human']) { ?>
    <div>Некорректное подтверждение кода CAPTCHA</div>
    
<?php } else if (!$actionResult['db']) { ?>
    <div>Ошибка записи в базу</div>
    
<?php } else { ?>
    <div>Действие выполнено удачно</div>
<?php } ?>
</div>
