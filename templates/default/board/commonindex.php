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
<div class="board">
<h1><?=$section['indic']?></h1>
<?=$settings['BodyHead']?>
<?php if (0 < count($items)) { ?>
    <div class="items">
    <?php foreach ($items as $item) { ?>
        <dl>
            <dt>
                <?=date('d.m.Y H:i', strtotime($item['DateCreate']));?>
                <a href="<?=$item['path']?><?=$item['Id']?>"><?=strip_tags($item['Title'])?></a>
                // <a href="<?=$item['path']?>"><?=$item['indic']?></a>
            </dt>
            <dd>
                <?=strip_tags($item['Message'], '<br>')?>
                <blockquote><?=strip_tags($item['Contacts'], '<br>')?></blockquote>
            </dd>
        </dl>
    <?php } ?>
    </div>
    <?php if ($this->params->pageSize < $itemsCount) { ?>
        <div class="pagination"><?php $this->renderPagination(); ?></div>
    <?php } ?>
<?php } else { ?>
    <div class="none">Объявлений пока нет</div>
<?php } ?>
<?=$settings['BodyFoot']?>
</div>
