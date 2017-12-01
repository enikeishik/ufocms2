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
    <?php if ('' != trim($params->pathRaw, '/')) { ?>
        <li><a href="/">Главная</a></li>
    <?php } else { ?>
        <li><span>Главная</span></li>
    <?php } ?>
    <?php foreach ($items as $item) { ?>
        <li>
        <?php if (trim($item['path'], '/') != trim($params->pathRaw, '/')) { ?>
            <a href="<?=$item['path']?>"<?=(isset($item['target']) ? ' target="' . $item['target'] . '"' : '')?>><?=htmlspecialchars($item['indic'])?></a>
        <?php } else { ?>
            <span><?=htmlspecialchars($item['indic'])?></span>
        <?php } ?>
        <?php if (0 < count($item['children'])) { ?>
            <ul class="submenu">
            <?php foreach ($item['children'] as $child) { ?>
                <li>
                <?php if (trim($child['path'], '/') != trim($params->pathRaw, '/')) { ?>
                    <a href="<?=$child['path']?>"<?=(isset($child['target']) ? ' target="' . $child['target'] . '"' : '')?>><?=htmlspecialchars($child['indic'])?></a>
                <?php } else { ?>
                    <span><?=htmlspecialchars($child['indic'])?></span>
                <?php } ?>
                </li>
            <?php } ?>
            </ul>
        <?php } ?>
        </li>
    <?php } ?>
    </ul>
<?php } ?>
