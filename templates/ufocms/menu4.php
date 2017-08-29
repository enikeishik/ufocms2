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
    <ul class="submenu">
    <?php foreach ($items as $item) { ?>
        <li>
        <?php if (trim($item['path'], '/') != trim($params->pathRaw, '/')) { ?>
            <a href="<?=$item['path']?>"<?=(isset($item['target']) ? ' target="' . $item['target'] . '"' : '')?>><?=htmlspecialchars($item['indic'])?></a>
        <?php } else { ?>
            <span><?=htmlspecialchars($item['indic'])?></span>
        <?php } ?>
        </li>
    <?php } ?>
    </ul>
<?php } ?>
