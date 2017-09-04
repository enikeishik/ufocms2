<?php
/**
 * @var \Ufocms\Modules\View $this
 * @var \Ufocms\Frontend\Tools $tools
 * @var array|null $site
 * @var array|null $section
 * @var array|null $settings
 * @var array|null $item
 * @var array|null $items
 * @var int|null $itemsCount
 */

//для списка выводим ссылку на RSS, для элемента - устанавливаем JS переменные интерактива
?>
<?php if (null === $item) { ?>
<link rel="alternate" href="<?=$section['path']?>rss" type="application/rss+xml" title="<?=htmlspecialchars($section['title'])?>">
<?php } ?>
