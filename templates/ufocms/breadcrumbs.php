<?php
/**
 * @var \Ufocms\Frontend\Menu $this
 * @var \Ufocms\Frontend\Tools $tools
 * @var array|null $site
 * @var array|null $section
 * @var array|null $items
 */
?>
<?php if (0 < count($items)) { ?>
    <div class="breadcrumbs">
    <?php foreach ($items as $item) { ?>
        <span><a<?php if ($item['path'] != $section['path']) { ?> href="<?=$item['path']?>"<?php } ?>><?=$item['indic']?></a><?php if ($item['path'] != $section['path']) { ?> \ <?php } ?></span>
    <?php } ?>
    </div>
<?php } ?>
