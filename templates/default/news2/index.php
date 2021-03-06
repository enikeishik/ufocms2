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
<h1><?=$this->getSectionTitle()?></h1>
<?=$settings['BodyHead']?>
<?php if (0 < $itemsCount) { ?>
    <div class="items">
    <?php foreach ($items as $item) { ?>
        <div class="item">
            <div class="date"><?=date('d.m.Y H:i', strtotime($item['DateCreate']))?></div>
            <?php if ('' != $item['Icon']) { ?>
                <div class="icon"><?=$item['Icon']?></div>
            <?php } ?>
            <div class="title"><a href="<?=$item['path']?><?=$item['Id']?>"><?=$item['Title']?></a></div>
            <div class="announce"><?=$this->getAnnounce($item, $settings)?></div>
        </div>
    <?php } ?>
    </div>
    <?php if ($this->params->pageSize < $itemsCount) { ?>
        <div class="pagination"><?php $this->renderPagination(); ?></div>
    <?php } ?>
<?php } else { ?>
    <div class="none">Новостей пока нет</div>
<?php } ?>
<?=$settings['BodyFoot']?>
</div>
