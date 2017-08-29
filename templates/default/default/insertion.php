<?php
/**
 * @var \Ufocms\Modules\Insertion $this
 * @var \Ufocms\Frontend\Tools $tools
 * @var array|null $section
 * @var array|null $item
 * @var array|null $items
 * @var array|null $this->data         insertion settings
 * @var array|null $this->options      insertions call [in template] options
 */
?>
<div class="widgetdefault">
<h3><?=$section['indic']?></h3>
<?php if (0 < count($items)) { ?>
    <div class="items">
    <?php foreach ($items as $item) { ?>
        <div class="item">
            <?php foreach ($item as $key => $val) { ?>
                <div><b><?=$key?>:</b> <?=$val?></div>
            <?php } ?>
            <div><a href="<?=$section['path']?><?=$item['Id']?>">Просмотр элемента</a></div>
        </div>
    <?php } ?>
    </div>
    <div class="all"><a href="<?=$section['path']?>">Все элементы</a></div>
<?php } else { ?>
    <div class="none">Элементов нет</div>
<?php } ?>
</div>
