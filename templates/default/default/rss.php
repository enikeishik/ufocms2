<?php
/**
 * @var \Ufocms\Modules\View $this
 * @var \Ufocms\Frontend\Core $core
 * @var \Ufocms\Frontend\Tools $tools
 * @var \Ufocms\Frontend\Config $config
 * @var array|null $site
 * @var array|null $section
 * @var array|null $settings
 * @var array|null $items
 * @var int|null $itemsCount
 */

header('Content-type: text/xml; charset=utf-8');
?>
<?xml version="1.0" encoding="utf-8"?>
<rss version="2.0" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:annotate="http://purl.org/rss/1.0/modules/annotate/" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#">
<channel>
<title><?=htmlspecialchars($site['SiteTitle'], ENT_QUOTES)?></title>
<link>http://<?=$_SERVER['HTTP_HOST']?></link>
<?php if (is_array($items)) { foreach ($items as $item) { ?>
    <item>
        <?php foreach ($item as $key => $val) { ?>
        <<?=$key?>><?=htmlspecialchars($val, ENT_QUOTES)?></<?=$key?>>
        <?php } ?>
    </item>
<?php } } elseif (is_array($item)) { ?>
    <item>
        <?php foreach ($item as $key => $val) { ?>
        <<?=$key?>><?=htmlspecialchars($val, ENT_QUOTES)?></<?=$key?>>
        <?php } ?>
    </item>
<?php } ?>
</channel>
</rss>
