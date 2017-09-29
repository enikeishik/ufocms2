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
<div class="defaultone">
<div class="section"><?=$section['indic']?></div>
<?php foreach ($item as $key => $val) { ?>
    <?php if (0 === stripos($key, 'passw')) { $val = '******'; } ?>
    <?php if (is_scalar($val)) { ?>
    <div><b><?=$key?>:</b> <?=$val?></div>
    <?php } else if (is_array($val)) { ?>
    <div><b><?=$key?>:</b>
        <?php foreach ($val as $k => $v) { ?>
            <blockquote>
            <?php if (is_scalar($v)) { ?>
            <div><b><?=$k?>:</b> <?=$v?></div>
            <?php } else { ?>
            <div><b><?=$k?>:</b> <?=gettype($v)?></div>
            <?php } ?>
            </blockquote>
        <?php } ?>
    </div>
    <?php } else { ?>
    <div><b><?=$key?>:</b> <?=gettype($val)?></div>
    <?php } ?>
<?php } ?>
<div class="all"><a href="<?=$section['path']?>">Все элементы</a></div>
</div>
