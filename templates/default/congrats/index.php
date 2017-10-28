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
<div class="congrats">
<h1><?=$this->getSectionTitle()?></h1>
<?=$settings['BodyHead']?>
<?php if (0 < count($items)) { ?>
    <div class="items">
    <?php foreach ($items as $item) { ?>
        <div class="item<?=($item['IsPinned'] ? ' pinned' : '')?><?=($item['IsHighlighted'] ? ' ishighlighted' : '')?>">
            <div class="date"><?=strftime('%e %B %G', strtotime($item['DateStart']))?></div>
            <div class="views">Просмотров: <?=$item['ViewedCnt']?></div>
            <div class="clear"></div>
            <?php if ('' != $item['Thumbnail']) { ?>
                <div class="image"><a href="<?=$section['path']?><?=$item['Id']?>"><?=$item['Thumbnail']?></a></div>
            <?php } ?>
            <div class="info"><?=$item['ShortDesc']?></div>
            <div class="more"><a href="<?=$section['path']?><?=$item['Id']?>">Смотреть полный текст</a></div>
            <div class="clear"></div>
        </div>
    <?php } ?>
    </div>
    <?php if ($this->params->pageSize < $itemsCount) { ?>
        <div class="pagination"><?php $this->renderPagination(); ?></div>
    <?php } ?>
<?php } else { ?>
    <div class="none">Поздравлений пока нет</div>
<?php } ?>
<?=$settings['BodyFoot']?>
</div>
