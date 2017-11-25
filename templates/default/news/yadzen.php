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
<rss version="2.0"
xmlns:content="http://purl.org/rss/1.0/modules/content/"
xmlns:dc="http://purl.org/dc/elements/1.1/"
xmlns:media="http://search.yahoo.com/mrss/"
xmlns:atom="http://www.w3.org/2005/Atom"
xmlns:georss="http://www.georss.org/georss">
<channel>
    <title><?=htmlspecialchars($site['SiteTitle'], ENT_QUOTES)?></title>
    <link>http://<?=$_SERVER['HTTP_HOST']?></link>
    <description><?=htmlspecialchars($site['SiteMetaDescription'], ENT_QUOTES)?></description>
    <language>ru</language>
    <?php foreach ($items as $item) { ?>
        <item>
            <title><?=htmlspecialchars($item['Title'], ENT_QUOTES)?></title>
            <link>http://<?=$_SERVER['HTTP_HOST'] . (isset($item['path']) ? $item['path'] : $section['path']) . $item['Id']?></link>
            <guid><?=$item['Id']?></guid>
            <pubDate><?=date('r', strtotime($item['DateCreate']))?></pubDate>
            <author><?=htmlspecialchars($item['Author'], ENT_QUOTES)?></author>
            <category><?=htmlspecialchars(isset($item['indic']) ? $item['indic'] : $section['indic'], ENT_QUOTES)?></category>
            <?php if (strlen($item['Icon'])) { ?>
                <enclosure type="image/jpeg" url="http://<?=$_SERVER['HTTP_HOST'] . $tools->srcFromImg($item['Icon'])?>" />
            <?php } ?>
            <description><?=htmlspecialchars($item['Announce'], ENT_QUOTES)?></description>
            <content:encoded><![CDATA[<?=$item['Body']?>]]></content:encoded>
        </item>
    <?php } ?>
</channel>
</rss>
