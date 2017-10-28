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
<div class="default">
<h1><?=$this->getSectionTitle()?></h1>
<?php if (0 < $itemsCount) { ?>
    <div class="items">
    <?php foreach ($items as $item) { ?>
        <div class="item">
            <?php foreach ($item as $key => $val) { ?>
                <?php if (0 === stripos($key, 'passw')) { $val = '******'; } ?>
                <?php if (is_scalar($val)) { ?>
                <div><b><?=$key?>:</b> <?=$val?></div>
                <?php } else { ?>
                <div><b><?=$key?>:</b> <?=gettype($val)?></div>
                <?php } ?>
            <?php } ?>
            <div><a href="<?=$section['path']?><?=$item['Id']?>">Просмотр элемента</a></div>
        </div>
    <?php } ?>
    </div>
    <?php if ($this->params->pageSize < $itemsCount) { ?>
        <div class="pagination"><?php $this->renderPagination(); ?></div>
    <?php } ?>
<?php } else { ?>
    <div class="none">Элементов нет</div>
<?php } ?>
</div>
