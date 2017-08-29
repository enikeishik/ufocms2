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

    Курсы валют:
    <?=$items['USD']['Name']?> – <?=round($items['USD']['Value'], 2)?>;
    <?=$items['EUR']['Name']?> – <?=round($items['EUR']['Value'], 2)?>.

<?php } else { ?>
    
    <p>Данные недоступны.</p>
    
<?php } ?>

</div>
