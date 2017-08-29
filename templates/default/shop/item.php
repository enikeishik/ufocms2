<?php
/**
 * @var \Ufocms\Modules\View $this
 * @var \Ufocms\Frontend\Tools $tools
 * @var array|null $site
 * @var array|null $section
 * @var array|null $settings
 * @var array|null $item
 */
?>
<div class="shopitem">

<?php include_once 'menu.php'; ?>

<h1><?=$item['Title'];?></h1>

<?php if ($item['Image']) { ?><div class="image"><?=$item['Image'];?></div><?php } ?>
<div class="info"><?=$item['FullDesc'];?></div>
<div class="price">Цена: <?=$item['Price'];?></div>

<div class="basket"><a href="?order=add&id=<?=$item['Id']?>">Добавить в корзину</a></div>

<div class="date"><?=strftime('%e %B %G', strtotime($item['DateCreate']))?></div>
<div class="views">Просмотров: <?=$item['ViewedCnt']?></div>
<div class="clear"></div>

<div class="all"><a href="<?=$section['path']?><?=$item['CategoryAlias']?>">Другие товары в категории «<?=$item['CategoryTitle']?>»</a></div>

</div>
