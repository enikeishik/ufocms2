<?php
/**
 * @var \Ufocms\Modules\View $this
 * @var \Ufocms\Frontend\Tools $tools
 * @var array|null $site
 * @var array|null $section
 * @var array|null $settings
 * @var array|null $items
 * @var int|null $itemsCount
 * @var int|null $categories
 */

header('Content-type: text/xml; charset=utf-8');
?>
<?xml version="1.0" encoding="utf-8"?>
<yml_catalog date="<?php echo date('Y-m-d H:i'); ?>">
<shop>
<name><?=htmlspecialchars($section['title'], ENT_QUOTES)?></name>
<company>[Полное наименование компании]</company>
<url>http://<?=$_SERVER['HTTP_HOST'] . $section['path']?></url>

<currencies>
<currency id="RUR" rate="1"/>
<currency id="USD" rate="CBRF"/>
</currencies>

<categories>
<?php if (null !== $categories) { foreach ($categories as $category) { ?>
<category id="<?=$category['Id']?>"<?=(0 != $category['ParentId'] ? ' parentId="' . $category['ParentId'] . '"' : '')?>><?=htmlspecialchars($category['Title'], ENT_QUOTES)?></category>
<?php } } ?>
</categories>

<offers>
<?php if (null !== $items) { foreach ($items as $item) { ?>
<offer id="12341">
    <url>http://<?=$_SERVER['HTTP_HOST'] . $section['path'] . $item['CategoryAlias'] . '/' . $item['Alias']?></url>
    <price><?=$item['Price']?></price>
    <currencyId>RUR</currencyId>
    <categoryId><?=$item['CategoryId']?></categoryId>
    <name><?=htmlspecialchars($item['Title'], ENT_QUOTES)?></name>
    <description>
<![CDATA[
<?=$item['ShortDesc']?>
]]>
  </description>
</offer>
<?php } } ?>
</offers>

</shop>
</yml_catalog>
