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
        <?php $this->itemChildren($item['id'], 'menu4'); ?>
        </li>
    <?php } ?>
    </ul>
<?php } ?>
