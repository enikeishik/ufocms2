<?php
/**
 * @var \Ufocms\Modules\Insertion $this
 * @var \Ufocms\Frontend\Tools $tools
 * @var array|null $section
 * @var array|null $item
 * @var array|null $items
 * @var array|null $this->data         insertion settings
 * @var array|null $this->options      insertions call [in template] options
 */
?>
<style type="text/css">
.insertionnews2 .info { text-align: right; }
.insertionnews2 .info span { color: #999; font-size: 11px; padding-right: 5px; white-space: nowrap; }
</style>
<div class="insertionnews2">
<h3><?=$this->data['Title']?></h3>
<?php if (0 < count($items)) { ?>
    <div class="items">
    <?php foreach ($items as $item) { ?>
        <div class="item">
            <div class="date"><?=date('d.m.Y H:i', strtotime($item['DateCreate']));?></div>
            <div class="title"><a href="<?=$item['path']?><?=$item['Id']?>"><?=$item['Title']?></a></div>
            <?php if ('' != $item['Icon']) { ?>
                <div class="icon"><?=$item['Icon'];?></div>
            <?php } ?>
            <div class="announce"><?=$this->getAnnounce($item, $this->data)?></div>
            <div class="info">
                <span class="views">просмотров <?=$item['ViewedCnt']?></span>
                <?php if (null !== $item['CommentsCnt']) { ?>
                <span class="comments">комментариев <?=$item['CommentsCnt']?></span>
                <span class="comments">рейтинг <?=round($item['Rating'], 2)?></span>
                <span class="comments">голосов <?=$item['RatesCnt']?></span>
                <?php } ?>
            </div>
        </div>
    <?php } ?>
    </div>
    <div class="all"><a href="<?=$section['path']?>">Все материалы</a></div>
<?php } else { ?>
    <div class="none">Новостей пока нет</div>
<?php } ?>
</div>
