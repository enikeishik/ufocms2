<?php
/**
 * @var \Ufocms\Modules\View $this
 * @var \Ufocms\Frontend\Core $core
 * @var \Ufocms\Frontend\Tools $tools
 * @var \Ufocms\Frontend\Config $config
 * @var array|null $site
 * @var array|null $section
 * @var array|null $settings
 */

header('Content-type: text/html; charset=utf-8');
?><!doctype html>
<html amp lang="ru">
<head>
    <meta charset="utf-8">
    <title><?=htmlspecialchars($item['Title'])?></title>
    <link rel="canonical" href="<?=$section['path']?><?=$item['Id']?>">
    <meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1">
    <style amp-boilerplate>body{-webkit-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-moz-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-ms-animation:-amp-start 8s steps(1,end) 0s 1 normal both;animation:-amp-start 8s steps(1,end) 0s 1 normal both}@-webkit-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-moz-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-ms-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-o-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}</style><noscript><style amp-boilerplate>body{-webkit-animation:none;-moz-animation:none;-ms-animation:none;animation:none}</style></noscript>
    <script async src="https://cdn.ampproject.org/v0.js"></script>
</head>
<body>
    <div class="newsone">
        <div class="section"><?=$section['indic']?></div>
        <h1><?=$item['Title']?></h1>
        <div class="date"><?=date('d.m.Y H:i', strtotime($item['DateCreate']))?></div>
        <?php if ('' != $item['Icon']) { ?>
            <div class="icon"><amp-img src="<?=$tools->srcFromImg($item['Icon'])?>" alt="" width="400" height="300"></amp-img></div>
        <?php } ?>
        <div><?=$item['Body']?></div>
        <?php if ('' != $item['Author']) { ?>
            <div class="author"><?=$item['Author']?></div>
        <?php } ?>
        <div class="all"><a href="<?=$section['path']?>">Другие новости</a></div>
    </div>
</body>
</html>