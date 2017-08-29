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
<style type="text/css">
.widgetboard { margin: 0px; padding: 0px; list-style-type: none; }
.widgetboard li { margin-bottom: 10px; }
.widgetboard li span { font-size: 12px; }
</style>
<div class="widget">
<?php if ($showTitle) { ?>
<h3><?=$title?></h3>
<?php } ?>
<?php if (0 < count($items)) { ?>
    <ul class="widgetboard">
        <?php foreach ($items as $item) { ?>
        <li>
            <div class="title">
                <span><?=date('d.m.y', strtotime($item['DateCreate']))?></span>
                <a href="<?=$item['path']?><?=$item['Id']?>"><?=strip_tags($item['Title'])?></a>
                <span>// <a href="<?=$item['path']?>"><?=htmlspecialchars($item['indic'])?></a></span>
            </div>
            <div class="message"><?=strip_tags($item['Message'], '<br>')?></div>
        </li>
        <?php } ?>
    </ul>
    <div class="clear"></div>
<?php } ?>
</div>
