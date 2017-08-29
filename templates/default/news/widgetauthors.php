<?php
/**
 * @var \Ufocms\Modules\Widget $this
 * @var \Ufocms\Frontend\Tools $tools
 * @var array $moduleParams
 * @var bool $showTitle
 * @var string $title
 * @var string $content
 * @var array $items
 * @var bool $showCount
 * @var string|null $sourcePath
 */
?>
<style type="text/css">
.widgetnewsauthors { margin: 0px; padding: 0px; list-style-type: none; }
.widgetnewsauthors li { margin-bottom: 10px; }
.widgetnewsauthors li span { font-size: 12px; }
</style>
<div class="widget">
<?php if ($showTitle) { ?>
<h3><?=$title?></h3>
<?php } ?>

<?php if (0 < count($items)) { ?>
    <ul class="widgetnewsauthors">
        <?php foreach ($items as $item) { ?>
        <li><a href="<?php if (null !== $sourcePath) { ?><?=$sourcePath?><?php } else { ?>/modules/news<?php } ?>?author=<?=urlencode($item['Author'])?>"><?=htmlspecialchars($item['Author'])?></a> <span>материалов: <?=$item['Cnt']?></span></li>
        <?php } ?>
    </ul>
    <div class="clear"></div>
<?php } ?>
</div>
