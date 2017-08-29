<?php
/**
 * @var \Ufocms\Modules\View $this
 * @var \Ufocms\Frontend\Tools $tools
 * @var array|null $site
 * @var array|null $section
 * @var array|null $items
 */
 ?>
<h1><?=$section['indic'];?></h1>
<?php if (0 < count($items)) { ?>
    <?php foreach ($items as $item) { ?>
        <?php if (-1 == $item['levelid']) { ?>
            <div style="margin-bottom: 5px;">
        <?php } else if (0 == $item['levelid']) { ?>
            <div style="margin-bottom: 5px; margin-top: 15px;">
        <?php } else { ?>
            <div style="margin-left: <?=(20 * $item['levelid'])?>px; margin-bottom: 5px;">
        <?php } ?>
                <div style="padding: 2px; background-color: #EEEEEE;"><a href="<?=$item['path']?>"><b><?=$item['indic']?></b></a></div>
                <div style="padding: 2px;"><?=$item['metadesc']?></div>
                <div style="padding: 2px; color: #666666">http://<?=$_SERVER['HTTP_HOST']?><?=$item['path']?></div>
            </div>
    <?php } ?>
<?php } ?>
