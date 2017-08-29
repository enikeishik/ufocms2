<?php
/**
 * @var \Ufocms\Modules\View $this
 * @var \Ufocms\Frontend\Tools $tools
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
<?php foreach ($items as $item) { ?>
    <item>
        <title><?=htmlspecialchars($item['Title'], ENT_QUOTES)?></title>
        <link>http://<?=$_SERVER['HTTP_HOST'] . $section['path'] . $item['Id']?></link>
        <category><?=htmlspecialchars($section['title'], ENT_QUOTES)?></category>
        <pubDate><?=date('r', strtotime($item['DateCreate']))?></pubDate>
        <description><?=htmlspecialchars(strip_tags($item['Message']), ENT_QUOTES)?></description>
    </item>
<?php } ?>
</channel>
</rss>
