<?php
/**
 * @var \Ufocms\Modules\Widget $this
 * @var \Ufocms\Frontend\Tools $tools
 * @var array $moduleParams
 * @var bool $showTitle
 * @var string $title
 * @var string $content
 * @var array $items
 */
?>
<div class="widget">
<?php if ($showTitle) { ?>
    <h3><?=$title?></h3>
<?php } ?>

<?php if (is_array($items) && 0 != count($items)) { ?>

    <div><?=$items['title']?>, <?=$items['country']?></div>
    <div>Дата: <?=date('d-m-y', strtotime($items['date']));?></div>
    <div>Восход: <?=$items['sun_rise']?></div>
    <div>Закат: <?=$items['sunset']?></div>
    <?php foreach ($items['day_part'] as $item) { ?>
        <div><?=$item['type']?>: <?=(isset($item['temperature']) ? $item['temperature'] : $item['temperature_from'] . ' – ' . $item['temperature_to'])?></div>
    <?php } ?>

<?php } else { ?>
    
    <p>Данные недоступны.</p>
    
<?php } ?>
</div>
