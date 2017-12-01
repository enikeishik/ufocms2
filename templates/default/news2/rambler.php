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

header('Content-type: application/rss+xml; charset=utf-8');
?>
<?xml version="1.0" encoding="utf-8"?>
<rss xmlns:rambler="http://news.rambler.ru" version="2.0">
<channel>
    <title><?=htmlspecialchars($site['SiteTitle'], ENT_QUOTES)?></title>
    <link>http://<?=$_SERVER['HTTP_HOST']?></link>
    <description><?=htmlspecialchars($site['SiteMetaDescription'], ENT_QUOTES)?></description>
    <?php foreach ($items as $item) { ?>
        <item>
            <title><?=htmlspecialchars($item['Title'], ENT_QUOTES)?></title>
            <link>http://<?=$_SERVER['HTTP_HOST'] . (isset($item['path']) ? $item['path'] : $section['path']) . $item['Id']?></link>
            <author><?=htmlspecialchars($item['Author'], ENT_QUOTES)?></author>
            <category><?=htmlspecialchars(isset($item['indic']) ? $item['indic'] : $section['indic'], ENT_QUOTES)?></category>
            <pubDate><?=date('r', strtotime($item['DateCreate']))?></pubDate>
            <?php if (strlen($item['Icon'])) { ?>
                <enclosure type="image/jpeg" url="http://<?=$_SERVER['HTTP_HOST'] . $tools->srcFromImg($item['Icon'])?>" />
            <?php } ?>
            <description><?=htmlspecialchars($item['Announce'], ENT_QUOTES)?></description>
            <rambler:fulltext><?=htmlspecialchars($item['Body'], ENT_QUOTES)?></rambler:fulltext>
        </item>
    <?php } ?>
</channel>
</rss>
