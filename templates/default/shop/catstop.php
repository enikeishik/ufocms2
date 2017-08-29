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
    <div class="shopcategories">
    <?php foreach ($items as $item) { ?>
        <div class="category">
            <div class="title"><a href="<?php echo $section['path']; ?><?php echo $item['Alias']; ?>"><?php echo $item['Title']; ?></a> <span><?php echo $item['TotalItemsCount']; ?></span></div>
            <?php if (0 < count($item['Children'])) { ?>
                <?php foreach ($item['Children'] as $child) { ?>
                    <div class="subcategory">
                        <a href="<?php echo $section['path']; ?><?php echo $child['Alias']; ?>"><?php echo $child['Title']; ?></a> <span><?php echo $child['TotalItemsCount']; ?></span>
                    </div>
                <?php } ?>
            <?php } else if ($item['Thumbnail']) { ?>
                <div class="image"><a href="<?php echo $section['path']; ?><?php echo $item['Alias']; ?>"><?=$item['Thumbnail']?></a></div>
            <?php } ?>
        </div>
    <?php } ?>
    </div>
    <div class="clear"></div>
<?php } ?>
