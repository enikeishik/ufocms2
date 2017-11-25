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
<rss xmlns:yandex="http://news.yandex.ru" xmlns:media="http://search.yahoo.com/mrss/" version="2.0">
<channel>
    <title><?=htmlspecialchars($site['SiteTitle'], ENT_QUOTES)?></title>
    <link>http://<?=$_SERVER['HTTP_HOST']?></link>
    <description><?=htmlspecialchars($site['SiteMetaDescription'], ENT_QUOTES)?></description>
    <language>ru</language>
<?php if (isset($config->counters)) { ?>
    <?php if (!empty($config->counters['Yandex'])) { ?>
    <yandex:analytics type="Yandex" id="<?=$config->counters['Yandex']?>"></yandex:analytics>
    <?php } ?>
    <?php if (!empty($config->counters['LiveInternet'])) { ?>
    <yandex:analytics type="LiveInternet"></yandex:analytics>
    <?php } ?>
    <?php if (!empty($config->counters['Google'])) { ?>
    <yandex:analytics type="Google" id="<?=$config->counters['Google']?>"></yandex:analytics>
    <?php } ?>
    <?php if (!empty($config->counters['MailRu'])) { ?>
    <yandex:analytics type="MailRu" id="<?=$config->counters['MailRu']?>"></yandex:analytics>
    <?php } ?>
    <?php if (!empty($config->counters['Rambler'])) { ?>
    <yandex:analytics type="Rambler" id="<?=$config->counters['Rambler']?>"></yandex:analytics>
    <?php } ?>
    <?php if (!empty($config->counters['Mediascope'])) { ?>
    <yandex:analytics type="Mediascope" id="<?=$config->counters['Mediascope']?>"></yandex:analytics>
    <?php } ?>
<?php } ?>
    
<?php foreach ($items as $item) { ?>
    <item turbo="true">
        <title><?=htmlspecialchars($item['Title'], ENT_QUOTES)?></title>
        <link>http://<?=$_SERVER['HTTP_HOST'] . (isset($item['path']) ? $item['path'] : $section['path']) . $item['Id']?></link>
        <pubDate><?=date('r', strtotime($item['DateCreate']))?></pubDate>
        <author><?=htmlspecialchars($item['Author'], ENT_QUOTES)?></author>
        <turbo:content><![CDATA[<?=$item['Body']?>]]></turbo:content>
    </item>
<?php } ?>
</channel>
</rss>
