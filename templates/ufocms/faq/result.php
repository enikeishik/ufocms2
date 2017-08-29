<?php
/**
 * @var \Ufocms\Modules\View $this
 * @var \Ufocms\Frontend\Tools $tools
 * @var array|null $site
 * @var array|null $section
 * @var array|null $settings
 * @var array|null $item
 * @var array|null $items
 * @var int|null $itemsCount
 * @var mixed $actionResult
 */
?>
<div class="defaultresult">
<h1><?=$section['indic']?></h1>
<?php if ($actionResult['db']) { ?>
    <?php if (0 < strlen($settings['PostMessage'])) { ?>
    <?=$settings['PostMessage']?>
    <?php } else { ?>
    <div>Действие выполнено удачно</div>
    <?php } ?>
<?php } else if ($actionResult['correct'] && $actionResult['human']) { ?>
    <?php if (0 < strlen($settings['PostMessageErr'])) { ?>
    <?=$settings['PostMessageErr']?>
    <?php } else { ?>
    <div>Действие выполнено неудачно</div>
    <?php } ?>
<?php } else { ?>
    <?php if (0 < strlen($settings['PostMessageBad'])) { ?>
    <?=$settings['PostMessageBad']?>
    <?php } else { ?>
    <div>Действие выполнено неудачно</div>
    <?php } ?>
<?php } ?>
</div>
