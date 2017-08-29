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
<rss xmlns:yandex="http://news.yandex.ru" xmlns:media="http://search.yahoo.com/mrss/" version="2.0">
<channel>
    <title><?=htmlspecialchars($site['SiteTitle'], ENT_QUOTES)?></title>
    <link>http://<?=$_SERVER['HTTP_HOST']?></link>
    <description><?=htmlspecialchars($site['SiteMetaDescription'], ENT_QUOTES)?></description>
    <image>
        <url>http://<?=$_SERVER['HTTP_HOST']?><?=$this->templateUrl?>/logo.jpg</url>
        <title><?=htmlspecialchars($site['SiteTitle'], ENT_QUOTES)?></title>
        <link>http://<?=$_SERVER['HTTP_HOST']?></link>
    </image>
    <?php foreach ($items as $item) { ?>
        <item>
            <title><?=htmlspecialchars($item['Title'], ENT_QUOTES)?></title>
            <link>http://<?=$_SERVER['HTTP_HOST'] . $item['path'] . $item['Id']?></link>
            <author><?=htmlspecialchars($item['Author'], ENT_QUOTES)?></author>
            <category><?=htmlspecialchars($section['title'], ENT_QUOTES)?></category>
            <yandex:genre>message</yandex:genre>
            <pubDate><?=date('r', strtotime($item['DateCreate']))?></pubDate>
            <?php if (strlen($item['Icon'])) { ?>
                <enclosure type="image/jpeg" url="http://<?=$_SERVER['HTTP_HOST'] . $tools->srcFromImg($item['Icon'])?>" />
            <?php } ?>
            <description><?=htmlspecialchars($item['Announce'], ENT_QUOTES)?></description>
            <yandex:full-text><?=api_ReplaceXmlTags($item['Body'])?></yandex:full-text>
        </item>
    <?php } ?>
</channel>
</rss>
