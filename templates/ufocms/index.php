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
?><!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title><?=$headTitle?></title>
<meta name="description" content="<?=$metaDesc?>">
<meta name="keywords" content="<?=$metaKeys?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="<?=$this->templateUrl?>/css/styles.css">
<?php if (null !== $this->themeStyle) { ?>
<link rel="stylesheet" type="text/css" href="<?=$this->templateUrl?>/css/<?=$this->themeStyle?>.css">
<?php } ?>
<?php $this->renderHead(); ?>
</head>
<body>
<div id="wrapper"><div id="document">
<div id="header">
    <div class="logo">
        <span><?=$site['SiteTitle']?><!-- UFOCMS<sup>beta</sup> --></span>
    </div>
    <div id="usersform"><?php $this->renderUsersForm(); ?></div>
    <div class="menu">
        <?php $menu->topSections('menu3'); ?>
    </div>
    <div id="social">
        <a href="#"><img src="<?=$this->templateUrl?>/images/vk-20.png" alt="VKontakte"></a>
        <a href="#"><img src="<?=$this->templateUrl?>/images/fb-20.png" alt="Facebook"></a>
        <a href="#"><img src="<?=$this->templateUrl?>/images/yt-20.png" alt="Youtube"></a>
        <a href="#"><img src="<?=$this->templateUrl?>/images/tw-20.png" alt="Twitter"></a>
        <a href="#"><img src="<?=$this->templateUrl?>/images/ok-20.png" alt="Odnoklassniki"></a>
        <a href="#"><img src="<?=$this->templateUrl?>/images/ig-20.png" alt="Instagram"></a>
    </div>
</div>
<div id="container">
    <div id="middle"><div id="content"><div id="contentinner">
        <?php $this->renderInsertions(['PlaceId' => 1]); ?>
        <?php $this->renderWidgets(['PlaceId' => 1]); ?>
        <?php $menu->breadcrumbs('breadcrumbs'); ?>
        <?php $this->renderModule(); ?>
        <?php $this->renderInsertions(); ?>
        <?php $this->renderWidgets(); ?>
        <?php $this->renderLinks(); ?>
    </div></div></div>
    <div id="left"><div id="leftinner">
        <?php $this->renderQuotes(['GroupId' => 1]); ?>
        <?php $this->renderWidgets(['PlaceId' => 3]); ?>
        <?php $this->renderInsertions(['PlaceId' => 12]); ?>
        <?php $this->renderQuotes(['GroupId' => 2]); ?>
    </div></div>
    <div id="right"><div id="rightinner">
        <?php $this->renderQuotes(['GroupId' => 3]); ?>
        <?php $this->renderWidgets(['PlaceId' => 2]); ?>
        <?php $this->renderInsertions(['PlaceId' => 2]); ?>
        <?php if ('/' == $this->params->sectionPath) { ?>
            <?php $this->renderInsertions(['PlaceId' => 5]); ?>
        <?php } else { ?>
            <?php $this->renderInsertions(['PlaceId' => 6]); ?>
        <?php } ?>
        <?php $this->renderQuotes(['GroupId' => 4]); ?>
    </div></div>
</div>
<div id="footer"><div id="footercontent">
    <?=$site['SiteCopyright']?><!-- Copyright &copy; 2017 -->
    <div class="menu">
        <?php
        $menuItems = array(
            array('path' => '/',        'indic' => 'Главная'), 
            array('path' => '/admin',   'indic' => 'Управление', 'target' => '_blank'), 
            array('path' => '/sitemap', 'indic' => 'Карта сайта'), 
        );
        $menu->custom($menuItems, 'menu2');
        ?>
    </div>
    <div class="menu">
        <?php
        $menuItems = array(
            array('indic' => 'Стиль:'), 
            array('path' => '?' . $config->themeStyleParam . '=default',  'indic' => 'D', 'title' => 'Стандартный цвет'), 
            array('path' => '?' . $config->themeStyleParam . '=red',      'indic' => 'R', 'title' => 'Красный цвет'), 
            array('path' => '?' . $config->themeStyleParam . '=green',    'indic' => 'G', 'title' => 'Зеленый цвет'), 
            array('path' => '?' . $config->themeStyleParam . '=blue',     'indic' => 'B', 'title' => 'Синий цвет'), 
        );
        $menu->custom($menuItems, 'menu2');
        ?>
    </div>
</div></div>
</div></div>
<?php $this->renderDebug(); ?>
</body>
</html>
