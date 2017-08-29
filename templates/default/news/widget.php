<?php
/**
 * @var \Ufocms\Modules\Widget $this
 * @var \Ufocms\Frontend\Tools $tools
 * @var array $moduleParams
 * @var bool $showTitle
 * @var string $title
 * @var string $content
 * @var array $items
 */
?>
<style type="text/css">
.widgetnews { margin: 0px; padding: 0px; list-style-type: none; }
.widgetnews li { margin-bottom: 10px; }
.widgetnews li span { font-size: 12px; }
</style>
<div class="widget">
<?php if ($showTitle) { ?>
<h3><?=$title?></h3>
<?php } ?>
<?php if (0 < count($items)) { ?>
    <ul class="widgetnews">
        <?php foreach ($items as $item) { ?>
        <li>
            <div class="title">
                <a href="<?=$item['path']?><?=$item['Id']?>"><?=htmlspecialchars($item['Title'])?></a> // 
                <span><?=date('d.m.y', strtotime($item['DateCreate']))?></span>
            </div>
            <div class="announce"><?=$this->getAnnounce($item, $this->params)?></div>
        </li>
        <?php } ?>
    </ul>
    <div class="clear"></div>
<?php } ?>
</div>
