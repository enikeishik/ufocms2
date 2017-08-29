<?php
/**
 * @var \Ufocms\Modules\View $this
 * @var \Ufocms\Frontend\Tools $tools
 * @var array|null $site
 * @var array|null $section
 * @var array|null $item
 * @var array|null $items
 * @var int|null $itemsCount
 * @var string $path
 * @var \Ufocms\Frontend\Params $this->params
 */

$count = $itemsCount;
$page = $this->params->page;
$pageSize = $this->params->pageSize;
$pagesShow = 10;
$pagesCount = (int) floor(($count - 1) / $pageSize) + 1;
$start = (floor(($page - 1) / $pagesShow) * $pagesShow + 1);
$flag  = (floor($pagesCount / $pagesShow) - floor(($page - 1) / $pagesShow));
if ($flag >= 1) {
    $stop = (floor(($page - 1) / $pagesShow) * $pagesShow + $pagesShow);
} else {
    $stop = $pagesCount;
}
?>
Всего записей: <?=$count?>
 | Страницы: 
<?php if ($page > 2) { ?>
    <a href="<?=$path?>page<?=($page - 1)?>">&laquo; Предыдущая</a>&nbsp;&nbsp;
<?php } else if ($page == 2) { ?>
    <a href="<?=$path?>">&laquo; Предыдущая</a>&nbsp;&nbsp;
<?php } ?>
<?php if ($page > $pagesShow) { ?>
    <a href="<?=$path?>page<?=($start - 1)?>">&lt;&lt;</a>
<?php } ?>
<?php for ($i = $start; $i <= $stop; $i++) { ?>
    <?php if ($i != $page) { ?>
        <?php if ($i == 1) { ?>
            <a href="<?=$path?>"><?=$i?></a>
        <?php } else { ?>
            <a href="<?=$path?>page<?=$i?>"><?=$i?></a>
        <?php } ?>
    <?php } else { ?>
        <b><?=$i?></b>
    <?php } ?>
<?php } ?>
<?php if ($flag >= 1) { ?>
    <a href="<?=$path?>page<?=($stop + 1)?>">&gt;&gt;</a>
<?php } ?>
<?php if ($page < $pagesCount) { ?>
    &nbsp;&nbsp;<a href="<?=$path?>page<?=($page + 1)?>">Следующая &raquo;</a>
<?php } ?>
