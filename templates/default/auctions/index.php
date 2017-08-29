<?php
/**
 * @var \Ufocms\Modules\View $this
 * @var \Ufocms\Frontend\Tools $tools
 * @var array|null $site
 * @var array|null $section
 * @var array|null $settings
 * @var array|null $items
 * @var int|null $itemsCount
 */
?>
<div class="auctions">
<h1><?=$section['indic']?></h1>
<?=$settings['BodyHead']?>
<?php if (0 < count($items)) { ?>
    <div class="items">
    <?php foreach ($items as $item) { ?>
        <div class="item<?php if ($item['IsClosed']) { ?> closed<?php } ?>">
            <?php if ($item['IsClosed']) { ?>
            <div class="closed">Аукцион закончен</div>
            <div class="clear"></div>
            <?php } ?>
            <div class="date"><?=date('d.m.Y H:i', strtotime($item['DateStart']))?> - <?=date('d.m.Y H:i', strtotime($item['DateStop']))?></div>
            <div class="views">Просмотров: <?=$item['ViewedCnt']?></div>
            <div class="clear"></div>
            <div class="title"><a href="<?=$section['path']?><?=$item['Id']?>"><?=$item['Title']?></a></div>
            <?php if ('' != $item['Thumbnail']) { ?>
                <div class="image"><a href="<?=$section['path']?><?=$item['Id']?>"><?=$item['Thumbnail']?></a></div>
            <?php } ?>
            <div class="info"><?=$item['ShortDesc']?></div>
        </div>
    <?php } ?>
    </div>
    <?php if ($this->params->pageSize < $itemsCount) { ?>
        <div class="pagination"><?php $this->renderPagination(); ?></div>
    <?php } ?>
<?php } else { ?>
    <div class="none">Аукционов пока нет</div>
<?php } ?>
<?=$settings['BodyFoot']?>
</div>
