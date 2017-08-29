<?php
/**
 * @var \Ufocms\Modules\View $this
 * @var \Ufocms\Frontend\Core $core
 * @var \Ufocms\Frontend\Tools $tools
 * @var array|null $site
 * @var array|null $section
 * @var array|null $settings
 * @var array|null $items
 * @var int|null $itemsCount
 */
?>
<h1><?=$section['indic']?></h1>
<?php if (0 < $itemsCount) { ?>
    <div class="users">
    <?php foreach ($items as $item) { ?>
        <div class="user">
            <div><a href="/users/<?=$item['Id']?>"><?=$item['Title']?> (<?=$item['Login']?>)</a></div>
        </div>
    <?php } ?>
    </div>
    <?php if ($this->params->pageSize < $itemsCount) { ?>
        <div class="pagination"><?php $this->renderPagination(); ?></div>
    <?php } ?>
<?php } else { ?>
    <div class="none">Элементов нет</div>
<?php } ?>
