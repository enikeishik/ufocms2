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
<div class="congratsitem">

<h1><?=$section['indic']?></h1>

<div class="date"><?=strftime('%e %B %G', strtotime($item['DateStart']))?></div>
<div class="views">Просмотров: <?=$item['ViewedCnt']?></div>
<div class="clear"></div>

<?php if ('' != $item['Image']) { ?>
    <div class="image"><?=$item['Image']?></div>
<?php } ?>

<div><?=$item['FullDesc']?></div>

</div>

<?php if ($section['shcomments']) { ?>
    <?php $this->renderComments(); ?>
<?php } ?>
