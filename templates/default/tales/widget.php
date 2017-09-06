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
    <div class="widgettales">
        <?php foreach ($items as $item) { ?>
        <div class="item">
            <div class="date"><?=strftime('%e %B %G', strtotime($item['DateCreate']))?></div>
            <?php if ('' != $item['Icon']) { ?>
                <div class="image"><a href="<?=$item['path']?><?=$item['Url']?>"><img src="<?=$tools->srcFromImg($item['Icon'])?>" alt="<?=htmlspecialchars($item['Title'])?>"></a></div>
            <?php } ?>
            <div class="title"><a href="<?=$item['path']?><?=$item['Url']?>"><?=$item['Title']?></a></div>
            <div class="info"><?=strip_tags($this->getAnnounce($item, $this->params), '<br>')?></div>
            <div class="views">Просмотров: <?=$item['ViewedCnt']?></div>
            <div class="more"><a href="<?=$item['path']?><?=$item['Url']?>">Смотреть полный текст</a></div>
            <div class="clear"></div>
        </div>
        <?php } ?>
    </div>
    <div class="clear"></div>
<?php } ?>
</div>
