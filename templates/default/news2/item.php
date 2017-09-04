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
<div class="newsone">
<?php if (is_array($item['AnotherSections']) && 0 < count($item['AnotherSections'])) { ?>
<div class="sections">
    <span><?=$section['indic']?></span>
    <?php foreach ($item['AnotherSections'] as $as) { ?>
        <a href="<?=$as['path']?>"><?=$as['indic']?></a>
    <?php } ?>
</div>
<?php } else { ?>
<div class="section"><?=$section['indic']?></div>
<?php } ?>
<h1><?=$item['Title']?></h1>
<div class="date"><?=date('d.m.Y H:i', strtotime($item['DateCreate']))?></div>
<?php if ('' != $item['Icon']) { ?>
    <div class="icon"><?=$item['Icon']?></div>
<?php } ?>
<div><?=$item['Body']?></div>
<div>Просмотров: <?=$item['ViewedCnt']?></div>
<?php if ('' != $item['Author']) { ?>
    <div class="author"><?=$item['Author']?></div>
<?php } ?>
<?php $tags = $this->getItemTags(); if (0 < count($tags)) { ?>
    <div class="tags">Тэги:
        <?php foreach ($tags as $tag) { ?>
            <span class="tag"><a href="<?=$section['path']?>tag<?=$tag['Id']?>"><?=htmlspecialchars($tag['Tag'])?></a></span>
        <?php } ?>
    </div>
<?php } ?>
<?php $similar = $this->getSimilarItems(); if (0 < count($similar)) { ?>
    <div class="similar">Похожие новости:
        <?php foreach ($similar as $si) { ?>
            <div class="similaritem"><a href="<?=$section['path']?><?=$si['Id']?>"><?=htmlspecialchars($si['Title'])?></a></div>
        <?php } ?>
    </div>
<?php } ?>
<div class="all"><a href="<?=$section['path']?>">Другие новости</a></div>
</div>
<?php if ($section['shcomments']) { ?>
    <?php $this->renderInteraction(); ?>
<?php } ?>
