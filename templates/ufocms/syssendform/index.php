<?php
/**
 * @var \Ufocms\Modules\View $this
 * @var \Ufocms\Frontend\Tools $tools
 * @var array|null $site
 * @var array|null $section
 * @var array|null $items
 * @var mixed $actionResult
 */
 ?>
<h1><?=$section['indic'];?></h1>
<?php if (!$actionResult['source']) { ?>
    <div>Некорректный источник</div>
    
<?php } else if (!$actionResult['method']) { ?>
    <div>Форма должна использовать метод POST</div>
    
<?php } else if (!$actionResult['data']) { ?>
    <div>Форма не содержит полей</div>
    
<?php } else if (!$actionResult['human']) { ?>
    <div>Некорректное подтверждение кода CAPTCHA</div>
    
<?php } else if (!$actionResult['db']) { ?>
    <div>Ошибка записи в базу</div>
    
<?php } else { ?>
    <?php if (0 < count($items)) { ?>
        <div>Данные формы:</div>
        <?php foreach ($items as $field => $value) { ?>
            <div><b><?=$field?>:</b><?=nl2br($value, false)?></div>
        <?php } ?>
    <?php } else { ?>
        <div>Данные формы отсутствуют</div>
    <?php } ?>
    
<?php } ?>
