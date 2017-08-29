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
    <ul>
    <li><a href="/">Главная</a></li>
    <?php foreach ($items as $item) { ?>
        <?php if (trim($item['path'], '/') != trim($params->pathRaw, '/')) { ?>
            <li><a href="<?=$item['path']?>"><?=htmlspecialchars($item['indic'])?></a></li>
        <?php } else { ?>
            <li><a><?=htmlspecialchars($item['indic'])?></a></li>
            <?php $this->children('menu2'); ?>
        <?php } ?>
    <?php } ?>
    </ul>
<?php } ?>
