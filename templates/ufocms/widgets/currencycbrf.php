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
    
    <?php if (isset($items['GBP'])) { ?><?=$items['GBP']['Name']?> – <?=round($items['GBP']['Value'], 2)?><br><?php } ?>
    <?php if (isset($items['USD'])) { ?><?=$items['USD']['Name']?> – <?=round($items['USD']['Value'], 2)?><br><?php } ?>
    <?php if (isset($items['EUR'])) { ?><?=$items['EUR']['Name']?> – <?=round($items['EUR']['Value'], 2)?><br><?php } ?>
    
<?php } else { ?>
    
    <p>Данные недоступны.</p>
    
<?php } ?>
</div>
