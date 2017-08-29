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
h3 { padding-bottom: 5px; }
.widgetfaq { margin: 0px; padding: 0px; list-style-type: none; }
.widgetfaq li { margin-bottom: 15px; border-top: solid #ccc 1px; padding-top: 5px; }
.widgetfaq li span { font-size: 12px; }
.widgetfaq li div.title { padding-top: 5px; }
</style>
<div class="widget">
<?php if ($showTitle) { ?>
<h3><?=$title?></h3>
<?php } ?>
<?php if (0 < count($items)) { ?>
    <ul class="widgetfaq">
        <?php foreach ($items as $item) { ?>
        <li>
            <div class="message"><?=strip_tags($item['UMessage'], '<br>')?></div>
            <div class="title">
                <span><?=date('d.m.y', strtotime($item['DateCreate']))?></span>
                <?=strip_tags($item['USign'])?>
                | <a href="<?=$item['path']?><?=$item['Id']?>">Ответ</a>
                <span>// <a href="<?=$item['path']?>"><?=htmlspecialchars($item['indic'])?></a></span>
            </div>
        </li>
        <?php } ?>
    </ul>
    <div class="clear"></div>
<?php } ?>
</div>
