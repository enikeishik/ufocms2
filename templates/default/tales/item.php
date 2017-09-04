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
<div class="talesone">
<h1><?=$item['Title']?></h1>
<div class="date"><?=date('d.m.Y H:i', strtotime($item['DateCreate']))?></div>
<?php if ('' != $item['Icon']) { ?>
    <div class="image"><?=$item['Icon']?></div>
<?php } ?>
<div><?=$item['Body']?></div>
<div class="views">Просмотров: <?=$item['ViewedCnt']?></div>
<?php if ('' != $item['Author']) { ?>
    <div class="author"><?=$item['Author']?></div>
<?php } ?>
<div class="all"><a href="<?=$section['path']?>">Другие материалы раздела «<?=$section['indic']?>»</a></div>
</div>
<?php if ($section['shcomments']) { ?>
    <?php $this->renderInteraction(); ?>
<?php } ?>
