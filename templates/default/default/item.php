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
    <?php if (!is_string($val)) { continue; } ?>
    <div><b><?=$key?>:</b> <?=$val?></div>
<?php } ?>
<div class="all"><a href="<?=$section['path']?>">Все элементы</a></div>
</div>
