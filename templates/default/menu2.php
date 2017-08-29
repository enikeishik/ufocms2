<?php
/**
 * @var \Ufocms\Frontend\Menu $this
 * @var \Ufocms\Frontend\Tools $tools
 * @var \Ufocms\Frontend\Params $params
 * @var array|null $site
 * @var array|null $section
 * @var array|null $items
 */
?>
<?php if (0 < count($items)) { ?>
    <ol>
    <?php foreach ($items as $item) { ?>
        <li><a<?php if (trim($item['path'], '/') != trim($params->pathRaw, '/')) { ?> href="<?=$item['path']?>"<?php } ?>><?=$item['indic']?></a></li>
    <?php } ?>
    </ol>
<?php } ?>
