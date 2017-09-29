<?php
/**
 * @var \Ufocms\Modules\View $this
 * @var \Ufocms\Frontend\Menu $menu
 * @var \Ufocms\Frontend\Core $core
 * @var \Ufocms\Frontend\Tools $tools
 * @var \Ufocms\Frontend\Config $config
 * @var array|null $site
 * @var array|null $section
 * @var array|null $item
 * @var array|null $items
 * @var int|null $itemsCount
 * @var string $headTitle
 * @var string $metaDesc
 * @var string $metaDesc
 */

header('Content-type: text/html; charset=utf-8');
if (null === $this->debug) {
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s', time() - 600) . ' GMT'); //10 min ago
    header('Cache-Control: max-age=' . 3600);
    header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 3600) . ' GMT');
} else {
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s', time() - 999999) . ' GMT');
    header('Cache-Control: no-store, no-cache, must-revalidate');
    header('Expires: ' . gmdate('D, d M Y H:i:s', time() - 999999) . ' GMT');
    header('Pragma: no-cache'); 
}
?><!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title><?=$headTitle?></title>
<meta name="description" content="<?=$metaDesc?>">
<meta name="keywords" content="<?=$metaKeys?>">
<link rel="stylesheet" type="text/css" href="<?=$this->templateUrl?>/styles.css">
<?php $this->renderHead(); ?>
</head>
<body>
<div id="wrapper">
<div id="header">
    <div class="menu">
        <b>custom</b>
        <?php
        $menuItems = array(
            array('path' => '/', 'indic' => 'Главная'), 
            array('path' => '/news', 'indic' => 'Новости 2'), 
            array('path' => '/novosti', 'indic' => 'Новости'), 
            array('path' => '/sitemap', 'indic' => 'Карта сайта'), 
        );
        $menu->custom($menuItems, 'menu2');
        ?>
    </div>
    <div id="usersform"><?php $this->renderUsersForm(); ?></div>
</div>
<div id="container">
    <div id="middle"><div id="content"><div id="contentinner">
        <?php $this->renderInsertions(['PlaceId' => 1]); ?>
        <?php $menu->breadcrumbs('breadcrumbs'); ?>
        <?php $this->renderModule(); ?>
        <?php $this->renderInsertions(); ?>
        <?php $this->renderWidgets(); ?>
        <div class="quotes"><script type='text/javascript'>var quote7='';</script><script type='text/javascript' src='/quotes.php?groupid=7'></script><script type='text/javascript'>document.write(quote7.replace(/<!/g,'<'));</script></div>
    </div></div></div>
    <div id="left"><div id="leftinner">
        <div class="menu">
            <b>top</b>
            <?php $menu->topSections(); ?>
        </div>
        <?php $this->renderQuotes(['GroupId' => 17]); ?>
        <?php $this->renderInsertions(['PlaceId' => 12]); ?>
        <?php $this->renderWidgets(['PlaceId' => 1]); ?>
    </div></div>
    <div id="right"><div id="rightinner">
        <div class="menu">
            <b>children</b>
            <?php $menu->children('menu2'); ?>
        </div>
        <div class="menu">
            <b>siblings</b>
            <?php $menu->siblings('menu2'); ?>
        </div>
        <?php $this->renderQuotes(['GroupId' => 18]); ?>
        <?php $this->renderInsertions(['PlaceId' => 2]); ?>
        <?php if ('/' == $this->params->sectionPath) { ?>
            <?php $this->renderInsertions(['PlaceId' => 5]); ?>
        <?php } else { ?>
            <?php $this->renderInsertions(['PlaceId' => 6]); ?>
        <?php } ?>
        <?php $this->renderWidgets(['PlaceId' => 2]); ?>
    </div></div>
</div>
<div id="footer">
    Copyright &copy; 2017
</div>
</div>
<?php $this->renderDebug(); ?>
</body>
</html>
