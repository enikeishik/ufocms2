<?php
/**
 * @var \Ufocms\Modules\Widget $this
 * @var \Ufocms\Frontend\Tools $tools
 * @var array $moduleParams
 * @var bool $showTitle
 * @var string $title
 * @var string $content
 * @var int $duration
 * @var array $items
 */
?>
<style type="text/css">
.slideshow { margin: 0px; padding: 10px; list-style-type: none; }
.slideshow li { float: left; margin-right: 20px; margin-bottom: 20px; border: solid #999 1px; padding: 10px; }
</style>
<div class="widget">
<?php if ($showTitle) { ?>
    <h3><?=$title?></h3>
<?php } ?>
<?php if (0 < count($items)) { ?>
    <ul class="slideshow">
        <?php foreach ($items as $item) { ?>
        <li><a href="<?=$item?>" target="slideshow"><img src="<?=$item?>" alt="" style="width: 200px; height: 160px;"></a></li>
        <?php } ?>
    </ul>
    <div class="clear"></div>
<?php } ?>
</div>
