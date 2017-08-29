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
        <?php if (isset($item['path']) && trim($item['path'], '/') != trim($params->pathRaw, '/')) { ?>
        <li>
            <a href="<?=$item['path']?>"<?=(isset($item['target']) ? ' target="' . $item['target'] . '"' : '')?><?=(isset($item['title']) ? ' title="' . htmlspecialchars($item['title']) . '"' : '')?>><?=htmlspecialchars($item['indic'])?></a>
        </li>
        <?php } else { ?>
        <li>
            <span><?=htmlspecialchars($item['indic'])?></span>
        </li>
        <?php } ?>
    <?php } ?>
    </ol>
<?php } ?>
