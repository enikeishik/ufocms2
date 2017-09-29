<?php
/**
 * @var \Ufocms\Modules\Widget $this
 * @var \Ufocms\Frontend\Tools $tools
 * @var array $moduleParams
 * @var bool $showTitle
 * @var string $title
 * @var string $content
 * @var array $items
 */

if (!isset($items) && isset($item)) {
    $items = array($item);
}
?>
<div class="widgetdefault">
<?php if ($showTitle) { ?>
<h3><?=$title?></h3>
<?php } ?>
<?php if (0 < count($items)) { ?>
    <div class="widgetitems">
    <?php foreach ($items as $item) { ?>
        <div class="widgetitem">
            <?php foreach ($item as $key => $val) { ?>
                <?php if (0 === stripos($key, 'passw')) { $val = '******'; } ?>
                <?php if (is_scalar($val)) { ?>
                <div><b><?=$key?>:</b> <?=$val?></div>
                <?php } else { ?>
                <div><b><?=$key?>:</b> <?=gettype($val)?></div>
                <?php } ?>
            <?php } ?>
            <div><a href="<?=$item['path']?><?=$item['Id']?>">Просмотр элемента</a></div>
        </div>
    <?php } ?>
    </div>
<?php } ?>
</div>
