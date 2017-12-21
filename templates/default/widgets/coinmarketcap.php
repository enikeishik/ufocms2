<?php
/**
 * @var \Ufocms\Modules\Widget $this
 * @var \Ufocms\Frontend\Tools $tools
 * @var bool $showTitle
 * @var string $title
 * @var string $content
 * @var array $items
 * @var array $priceField
 * @var array $priceTitle
 */
?>
<div class="widget">
<?php if ($showTitle) { ?>
    <h3><?=$title?></h3>
<?php } ?>

<?php if (is_array($items) && 0 != count($items)) { ?>
<style type="text/css">
.coinmarketcap .cmcitem { border-bottom: dashed #999 1px; margin-bottom: 5px; }
.coinmarketcap .cmcitem .cmcitemtitle { display: inline-block; width: 200px; }
.coinmarketcap .cmcitem .cmcitemvalue { float: right; }
</style>
<div class="coinmarketcap">
    <?php foreach ($items as $itm) { $item = $itm[0]; ?>
    <div class="cmcitem">
        <span class="cmcitemtitle"><?=$item['name']?> <?=$item['symbol']?>:</span>
        <span class="cmcitemvalue"><?=number_format($item[$priceField], 2, ',', ' ')?> <?=$priceTitle?></span>
    </div>
    <div class="clear"></div>
    <?php } ?>
</div>
<?php } else { ?>
    
    <p>Данные недоступны.</p>
    
<?php } ?>

</div>
