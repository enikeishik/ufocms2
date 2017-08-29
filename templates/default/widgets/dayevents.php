<?php
/**
 * @var \Ufocms\Modules\Widget $this
 * @var \Ufocms\Frontend\Tools $tools
 * @var array $moduleParams
 * @var bool $showTitle
 * @var string $title
 * @var string $content
 * @var array $items
 * @var bool $showYesterday
 * @var bool $showTommorow
 */
?>
<div class="widget">
<?php if ($showTitle) { ?>
    <h3><?=$title?></h3>
<?php } ?>

<?php if (is_array($items) && 0 < count($items)) { ?>
    
    <?php if ($showYesterday && 0 < count($items['Yesterday'])) { ?>
    <p>Вчера</p>
    <ul>
    <?php foreach ($items['Yesterday'] as $item) { ?>
        <li><?=$item['Text']?></li>
    <?php } ?>
    </ul>
    <?php } ?>
    
    <?php if (0 < count($items['Today'])) { ?>
    <p>Сегодня</p>
    <ul>
    <?php foreach ($items['Today'] as $item) { ?>
        <li><?=$item['Text']?></li>
    <?php } ?>
    </ul>
    <?php } ?>
    
    <?php if ($showTommorow && 0 < count($items['Tommorow'])) { ?>
    <p>Завтра</p>
    <ul>
    <?php foreach ($items['Tommorow'] as $item) { ?>
        <li><?=$item['Text']?></li>
    <?php } ?>
    </ul>
    <?php } ?>
    
<?php } else { ?>
    
    <p>Данные недоступны.</p>
    
<?php } ?>
</div>
