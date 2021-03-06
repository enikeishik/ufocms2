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
<div class="oldurls">
<h1><?=$this->getSectionTitle()?></h1>
<?=$settings['BodyHead']?>
<?php if (0 < $itemsCount) { ?>
    <div class="items">
    <?php foreach ($items as $item) { ?>
        <div class="item">
            <div class="title"><a href="<?=$item['path']?><?=$item['Url']?>"><?=$item['Title']?></a></div>
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
