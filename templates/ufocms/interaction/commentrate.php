<?php
/**
 * @var \Ufocms\Modules\View $this
 * @var \Ufocms\Frontend\Tools $tools
 * @var array|null $site
 * @var array|null $section
 * @var array|null $items
 * @var array|null $item
 * @var int|null $itemsCount
 * @var array|null $rating
 * @var string $path
 * @var array|null $options
 */


$bgcolor = '#eeeeee';
if (0 > $item['Rating']) {
    $bgcolor = '#ffcccc';
} else if (0 < $item['Rating']) {
    $bgcolor = '#99ff99';
}
//форма реализована посредством JavaScript не только 
//чтобы не перезагружать страницу, но и чтобы случайно не учитывать ботов
?>
<style type="text/css">
.commentsrateform {
    display: inline-block; 
}
.comment-rate-button { 
    border: 0px; width: 20px; height: 20px; 
    color: #ffffff; font-family: Arial; font-size: 11px; font-weight: normal; 
    vertical-align: top; 
}
.comment-rating {
    display: inline-block; height: 20px; padding: 0px 2px; 
    line-height: 20px; 
}
</style>
<span class="commentsrateform" id="commentRateFormContainer<?=$item['Id']?>">
<script type="text/javascript">
document.write('<form action="?rnd=<?=time()?>#comments" method="post" id="commentRateForm<?=$item['Id']?>" onsubmit="return sendCommentRateForm(this)">' + 
               '<input type="hidden" name="commentid" value="<?=$item['Id']?>">' + 
               '<input type="submit" class="comment-rate-button" style="background-color: #990000;" name="commentrate" value="-1" onclick="setCommentRateValue(this.value)" title="Нажмите чтобы оценить комментарий">' + 
               '<span id="commentRatingValue<?=$item['Id']?>" class="comment-rating" style="background-color: <?=$bgcolor?>;"><b><?=$item['Rating']?></b> (<?=$item['RatesCnt']?>)</span>' + 
               '<input type="submit" class="comment-rate-button" style="background-color: #009900;" name="commentrate" value="+1" onclick="setCommentRateValue(this.value)" title="Нажмите чтобы оценить комментарий">' + 
               '</form>');
</script>
<noscript>Для оценки комментария необходимо включить в броузере поддержку JavaScript</noscript>
</span>
