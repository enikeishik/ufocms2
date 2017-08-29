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
        <link>http://<?=$_SERVER['HTTP_HOST'] . $section['path'] . $item['Id']?></link>
        <question>
            <date><?=date('r', strtotime($item['DateCreate']))?></date>
            <sign><?=htmlspecialchars($item['USign'], ENT_QUOTES)?></sign>
            <email><?=htmlspecialchars($item['UEmail'], ENT_QUOTES)?></email>
            <url><?=htmlspecialchars($item['UUrl'], ENT_QUOTES)?></url>
            <text><?=htmlspecialchars(strip_tags($item['UMessage']), ENT_QUOTES)?></text>
        </question>
        <answer>
            <date><?=date('r', strtotime($item['DateAnswer']))?></date>
            <sign><?=htmlspecialchars($item['ASign'], ENT_QUOTES)?></sign>
            <email><?=htmlspecialchars($item['AEmail'], ENT_QUOTES)?></email>
            <url><?=htmlspecialchars($item['AUrl'], ENT_QUOTES)?></url>
            <text><?=htmlspecialchars(strip_tags($item['AMessage']), ENT_QUOTES)?></text>
        </answer>
    </item>
<?php } ?>
</channel>
</rss>
