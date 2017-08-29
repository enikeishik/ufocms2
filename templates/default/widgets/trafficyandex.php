<?php
/**
 * @var \Ufocms\Modules\Widget $this
 * @var \Ufocms\Frontend\Tools $tools
 * @var array $moduleParams
 * @var bool $showTitle
 * @var string $title
 * @var string $content
 * @var array $item
 */
?>
<div class="widget">
<?php if ($showTitle) { ?>
    <h3><?=$title?></h3>
<?php } ?>

<?php if (is_array($item) && 0 != count($item)) { ?>

    <div><?=$item['title']?></div>
    <div>Уровень: <?=$item['level']?> (<?=mb_strtolower($item['hint'])?>) на <?=$item['time']?>.</div>

<?php } else { ?>
    
    <p>Данные недоступны.</p>
    
<?php } ?>
</div>
