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
.widgetnews2 { margin: 0px; padding: 0px; list-style-type: none; }
.widgetnews2 li { margin-bottom: 15px; }
.widgetnews2 li span { font-size: 12px; }
.widgetnews2 .info { text-align: right; margin-right: 20px; }
.widgetnews2 .info span { color: #999; padding-right: 5px; white-space: nowrap; }
</style>
<div class="widget">
<?php if ($showTitle) { ?>
<h3><?=$title?></h3>
<?php } ?>
<?php if (0 < count($items)) { ?>
    <ul class="widgetnews2">
        <?php foreach ($items as $item) { ?>
        <li>
            <div class="title">
                <a href="<?=$item['path']?><?=$item['Id']?>"><?=htmlspecialchars($item['Title'])?></a> // 
                <span><?=date('d.m.y', strtotime($item['DateCreate']))?></span>
            </div>
            <div class="announce"><?=$this->getAnnounce($item, $this->params)?></div>
            <div class="info">
                <?php if (null !== $item['CommentsCnt']) { ?>
                <span class="comments">комментариев <?=$item['CommentsCnt']?></span>
                <span class="comments">рейтинг <?=round($item['Rating'], 2)?></span>
                <span class="comments">голосов <?=$item['RatesCnt']?></span>
                <?php } ?>
                <span class="views">просмотров <?=$item['ViewedCnt']?></span>
            </div>
        </li>
        <?php } ?>
    </ul>
    <div class="clear"></div>
<?php } ?>
</div>
