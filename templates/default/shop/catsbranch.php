<?php
/**
 * @var \Ufocms\Modules\View $this
 * @var \Ufocms\Frontend\Tools $tools
 * @var array|null $site
 * @var array|null $section
 * @var array|null $settings
 * @var array|null $items
 */
?>
<?php if (0 < count($items)) { ?>
    <div class="shopcategoriestree">
    <?php foreach ($items as $item) { ?>
        <div class="category" style="margin-left: <?=($item['LevelId'] - 1) * 20?>px;">
            <?php if (!$item['Current']) { ?>
            <a href="<?php echo $section['path']; ?><?php echo $item['Alias']; ?>"><?php echo $item['Title']; ?></a>
            <?php } else { ?>
            &#9658;<?php echo $item['Title']; ?>
            <?php } ?>
            <span><?php echo $item['TotalItemsCount']; ?></span>
            <?php if (array_key_exists('Children', $item)) { ?>
                <?php foreach ($item['Children'] as $child) { ?>
                    <div class="subcategory" style="margin-left: <?=($child['LevelId'] - 1) * 20?>px;">
                        <a href="<?php echo $section['path']; ?><?php echo $child['Alias']; ?>"><?php echo $child['Title']; ?></a> <span><?php echo $child['TotalItemsCount']; ?></span>
                    </div>
                <?php } ?>
            <?php } ?>
        </div>
    <?php } ?>
    </div>
    <div class="clear"></div>
<?php } ?>
