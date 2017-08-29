<?php
/**
 * @var \Ufocms\Modules\Widget $this
 * @var \Ufocms\Frontend\Tools $tools
 * @var bool $showTitle
 * @var string $title
 * @var string $content
 * @var array $items
 */
?>
<style type="text/css">
.widgetdocs { margin: 0px; padding: 0px; list-style-type: none; }
.widgetdoc li { margin-bottom: 20px; border: solid #999 1px; padding: 10px; }
</style>
<div class="widget">
<?php if ($showTitle) { ?>
<h3><?=$title?></h3>
<?php } ?>
<?php if (0 < count($items)) { ?>
    <ul class="widgetdocs">
        <?php foreach ($items as $item) { ?>
        <h4><?=$item['indic']?></h4>
        <?=$item['body']?>
        <?php } ?>
    </ul>
    <div class="clear"></div>
<?php } ?>
</div>
