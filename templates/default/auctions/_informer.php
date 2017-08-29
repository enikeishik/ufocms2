<?php
$dtm = null;
if (null !== $item['DateStep']) {
    $remaining = $item['StepTime'] - time() + strtotime($item['DateStep']);
    $dtmFormat = 3600 < $remaining ? 'H ч., i мин., s сек.' : (60 < $remaining ? 'i мин., s сек.' : 's сек.');
    $dtm = new DateTime('@' . $remaining);
}
?>
<div class="informer">
    <?php if (!$item['IsClosed']) { ?>
    
    <div class="pricestart">Стартовая цена: <?=$item['PriceStart']?> руб.</div>
    <div class="step">Шаг цены: <?=(0 < $item['Step'] ? '+' : '')?><?=$item['Step']?> руб.</div>
    <div class="steptime">Время шага: <?=$item['StepTime']?> сек.</div>
    <div class="pricecurrent">Текущая цена: <?=$item['PriceCurrent']?> руб.</div>
    
    <?php if ($item['IsStarted']) { ?>
        <?php if ($currentUser['Id'] != $item['UserId']) { ?>
        <div class="makestep"><a href="?action=step&t=<?=time()?>" target="_top">Сделать ставку</a></div>
        <?php } else { ?>
        <div class="makestep"><span>Последняя ставка сделана Вами</span></div>
        <?php } ?>
        
        <?php if (null !== $dtm) { ?>
        <div class="remaining">Если ставок не будет в течении <span><?=$dtm->format($dtmFormat)?></span>, аукцион будет завершен.</div>
        <?php } ?>
    <?php } else { ?>
        <div class="notstarted">Аукцион еще не начат, начало аукциона <?=date('d.m.Y H:i', strtotime($item['DateStart']))?></div>
    <?php } ?>
    
    <?php if (!isset($noscript)) { ?>
    <script type="text/javascript">
    var remainingTime = <?=$settings['UpdateTimeout']?>;
    setInterval('document.getElementById("updatetime").innerHTML = --remainingTime', 999);
    document.write('<div class="updatetime">До обновления информации осталось <span id="updatetime"><?=$settings['UpdateTimeout']?></span> сек.</div>');
    </script>
    <noscript><div class="updatetime">Информация обновляется каждые <?=$settings['UpdateTimeout']?> сек.</div></noscript>
    <?php } else { ?>
    <div class="updatetime">Информация обновляется каждые <?=$settings['UpdateTimeout']?> сек.</div>
    <?php } ?>
    
    <?php } else { ?>
    
    <div class="pricestart">Стартовая цена: <?=$item['PriceStart']?> руб.</div>
    <div class="pricecurrent">Конечная цена: <?=$item['PriceCurrent']?> руб.</div>
    <?php if ($currentUser['Id'] == $item['UserId']) { ?>
    <div class="makestep"><span>Последняя ставка сделана Вами</span></div>
    <?php } ?>
    
    <?php } ?>
</div>
