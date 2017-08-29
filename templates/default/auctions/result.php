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
<div class="auctionsresult">
<h1><?=$section['indic']?></h1>
<?php if ($actionResult['success']) { ?>
    <div>Действие выполнено удачно</div>
<?php } else { ?>
    <div>Действие выполнено неудачно</div>
    <div>Текст ошибки: <?=$actionResult['error']?></div>
<?php } ?>
<div><a href="<?=$section['path']?><?=(0 != $this->params->itemId ? $this->params->itemId : '')?>?t=<?=time()?>">Вернуться назад</a></div>
</div>
