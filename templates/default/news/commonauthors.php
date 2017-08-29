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
<div class="news">
<h1><?=$section['indic']?></h1>
<?php if (0 < count($items)) { ?>
    <div class="items">
    <?php foreach ($items as $item) { ?>
        <div class="item">
            <div class="title">
                <a href="<?=$section['path']?>?author=<?=urlencode($item['Author'])?>"><?=htmlspecialchars($item['Author'])?></a>
                <span>материалов: <?=$item['Cnt']?></span>
            </div>
        </div>
    <?php } ?>
    </div>
    <?php if ($this->params->pageSize < $itemsCount) { ?>
        <div class="pagination"><?php $this->renderPagination(); ?></div>
    <?php } ?>
<?php } else { ?>
    <div class="none">Данных нет</div>
<?php } ?>
</div>
