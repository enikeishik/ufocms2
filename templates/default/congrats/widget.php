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
<?php if (0 < count($items)) { ?>
    <div class="widgetcongrats">
        <?php foreach ($items as $item) { ?>
        <div class="item<?=($item['IsPinned'] ? ' pinned' : '')?><?=($item['IsHighlighted'] ? ' ishighlighted' : '')?>">
            <?php if ('' != $item['Thumbnail']) { ?>
                <div class="image"><a href="<?=$item['path']?><?=$item['Id']?>"><img src="<?=$tools->srcFromImg($item['Thumbnail'])?>" width="100" height="100" alt=""></a></div>
            <?php } ?>
            <div class="info"><?=$item['ShortDesc']?></div>
            <div class="more"><a href="<?=$item['path']?><?=$item['Id']?>">Смотреть полный текст</a></div>
            <div class="date"><?=strftime('%e %B %G', strtotime($item['DateStart']))?></div>
            <div class="views">Просмотров: <?=$item['ViewedCnt']?></div>
            <div class="clear"></div>
        </div>
        <?php } ?>
    </div>
    <div class="clear"></div>
<?php } ?>
</div>
