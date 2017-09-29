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
<div class="votings">
<h1><?=$section['indic']?></h1>
<?=$settings['BodyHead']?>
<?php if (0 < $itemsCount) { ?>
    <div class="items">
    <?php foreach ($items as $item) { ?>
        <div class="item<?php if ($item['IsClosed']) { ?> closed<?php } ?>">
            <div class="date"><?=date('d.m.Y H:i', strtotime($item['DateStart']))?> - <?=date('d.m.Y H:i', strtotime($item['DateStop']))?></div>
            <?php if ('' != $item['Image']) { ?>
                <div class="image"><?=$item['Image']?></div>
            <?php } ?>
            <div class="title"><a href="<?=$section['path']?><?=$item['Id']?>"><?=$item['Title']?></a></div>
            <div class="clear"></div>
        </div>
    <?php } ?>
    </div>
    <?php if ($this->params->pageSize < $itemsCount) { ?>
        <div class="pagination"><?php $this->renderPagination(); ?></div>
    <?php } ?>
<?php } else { ?>
    <div class="none">Материалов пока нет</div>
<?php } ?>
<?=$settings['BodyFoot']?>
</div>
