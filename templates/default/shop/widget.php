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
    <ul class="widgetshop">
        <?php foreach ($items as $item) { ?>
        <li>
            <div class="title">
                <a href="<?=$item['path']?><?=$item['CategoryAlias']?>/<?=$item['Alias']?>"><?=htmlspecialchars($item['Title'])?></a>
                <span><?=$item['CategoryTitle']?></span>
            </div>
            <div class="info"><?=$item['ShortDesc']?></div>
            <div class="price">Цена: <?=$item['Price']?> руб.</div>
            <div class="basket"><a href="<?=$item['path']?>?order=add&id=<?=$item['Id']?>">Добавить в корзину</a></div>
        </li>
        <?php } ?>
    </ul>
    <div class="clear"></div>
<?php } ?>
</div>
