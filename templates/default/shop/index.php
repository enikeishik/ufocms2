<?php
/**
 * @var \Ufocms\Modules\View $this
 * @var \Ufocms\Frontend\Tools $tools
 * @var array|null $site
 * @var array|null $section
 * @var array|null $settings
 * @var array|null $category
 * @var array|null $items
 * @var int|null $itemsCount
 */
?>
<div class="shopitems">

<?php include_once 'menu.php'; ?>

<?php if (null !== $this->category) { ?>
<h1><?=htmlspecialchars($category['Title'])?></h1>
<?php } else { ?>
<h1><?=$section['indic']?></h1>
<?php } ?>

<?=$settings['BodyHead']?>

<?php $this->renderCategories(); ?>

<?php if (0 < count($items)) { ?>
    <div class="items">
    <?php foreach ($items as $item) { ?>
        <div class="item">
            <?php if ($item['Thumbnail']) { ?><div class="thumbnail"><a href="<?=$section['path']?><?=$item['CategoryAlias']?>/<?=$item['Alias']?>"><?=$item['Thumbnail']?></a></div><?php } ?>
            <div class="title"><a href="<?=$section['path']?><?=$item['CategoryAlias']?>/<?=$item['Alias']?>"><?=$item['Title']?></a></div>
            <div class="info"><?=$item['ShortDesc']?></div>
            <div class="price">Цена: <?=$item['Price']?></div>
            <div class="basket"><a href="?order=add&id=<?=$item['Id']?>">Добавить в корзину</a></div>
            <div class="date"><?=strftime('%e %B %G', strtotime($item['DateCreate']))?></div>
            <div class="views">Просмотров: <?=$item['ViewedCnt']?></div>
            <div class="clear"></div>
        </div>
    <?php } ?>
    </div>
    <?php if ($this->params->pageSize < $itemsCount) { ?>
        <div class="pagination"><?php $this->renderPagination(); ?></div>
    <?php } ?>
<?php } else { ?>
    <div class="none">Товаров нет</div>
<?php } ?>

<?=$settings['BodyFoot']?>

</div>
