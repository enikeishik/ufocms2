<?php
/**
 * @var \Ufocms\Modules\View $this
 * @var \Ufocms\Frontend\Tools $tools
 * @var array|null $site
 * @var array|null $section
 * @var array|null $settings
 * @var array|null $item
 * @var array|null $currentUser
 */
?>
<div class="auctionsitem">
    <div class="section"><?=$section['indic']?></div>
    
    <?php if ($item['IsClosed']) { ?>
    <div class="closed">Аукцион закончен</div>
    <div class="clear"></div>
    <?php } ?>
    <div class="date">
        <span class="start">Начало <?=date('d.m.Y H:i', strtotime($item['DateStart']))?></span>
        <span class="stop">Окончание <?=date('d.m.Y H:i', strtotime($item['DateStop']))?></span>
    </div>
    <div class="clear"></div>
    
    <h1><?=$item['Title']?></h1>
    
    <?php if ('' != $item['Image']) { ?>
        <div class="image"><?=$item['Image']?></div>
    <?php } ?>
    
    <div class="info">
    <div class="lead"><?=$item['ShortDesc']?></div>
    <?=$item['FullDesc']?>
    </div>
    
    <?php if (!$item['IsClosed']) { ?>
        <?php if (2 == $settings['UpdateType']) { ?>
        <script type="text/javascript" src="/templates/default/auctions/jquery-3.2.1.min.js"></script>
        <script type="text/javascript">
        var currentUserId = '<?=$currentUser['Id']?>';
        document.write('<div id="informer" class="informer"></div>');
        </script>
        <script type="text/javascript" src="/templates/default/auctions/auctions.js"></script>
        <noscript>
        <meta http-equiv="refresh" content="<?=$settings['UpdateTimeout']?>; URL=?t=<?=time()?>">
        <?php $noscript = 1; ?>
        <?php include_once '_informer.php'; ?>
        </noscript>
        <?php } else if (1 == $settings['UpdateType']) { ?>
        <meta http-equiv="refresh" content="<?=$settings['UpdateTimeout']?>; URL=?t=<?=time()?>">
        <?php include_once '_informer.php'; ?>
        <?php } else { ?>
        <div class="informer">
            <iframe id="informerframe" src="<?=$section['path']?><?=$item['Id']?>?type=iframe&t=<?=time()?>" style="border: 0px; width: 100%; height: 190px;" seamless></iframe>
        </div>
        <?php } ?>
    <?php } else { ?>
        <?php include_once '_informer.php'; ?>
    <?php } ?>

    <div class="views">Просмотров: <?=$item['ViewedCnt']?></div>
    
    <div class="all"><a href="<?=$section['path']?>">Другие аукционы</a></div>
</div>
