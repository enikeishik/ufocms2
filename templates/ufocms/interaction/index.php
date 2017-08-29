<?php
/**
 * @var \Ufocms\Modules\View $this
 * @var \Ufocms\Frontend\Tools $tools
 * @var array|null $site
 * @var array|null $section
 * @var array|null $items
 * @var int|null $itemsCount
 * @var array|null $rating
 * @var string $path
 * @var array|null $options
 */

?>
<div class="rating" id="ratingView">
    <a name="rating"></a>
    <b>Оценка:</b> 
    <span id="ratingStars"><?php include 'ratingimages.php'; ?></span> 
    (<span id="ratingValue"><?=round($rating['Rating'], 2)?></span>, 
    проголосовало: <span id="ratesCountValue"><?php echo (int) $rating['RatesCnt']; ?></span>)
    <?php include 'rateform.php'; ?>
</div>
<?php if (0 < count($items)) { ?>
    <a name="comments"></a>
    <h5>Комментарии</h5>
    <div class="comments">
    <?php foreach ($items as $item) { ?>
        <div class="comment">
            <div class="commentinfo">
                <?=$item['DateCreate']?>
                
                <?php if ('' != $item['CommentAuthor']) { ?>
                    <?php if (0 == $item['UserId']) { ?>
                    | <b><?=$item['CommentAuthor']?></b>
                    <?php } else { ?>
                    | <a href="/users/<?=$item['UserId']?>"><b><?=$item['CommentAuthor']?></b></a>
                    <?php } ?>
                <?php } else { ?>
                    <?php if (0 == $item['UserId']) { ?>
                    | <b>(не представился)</b>
                    <?php } else { ?>
                    | <a href="/users/<?=$item['UserId']?>"><b>(не представился)</b></a>
                    <?php } ?>
                <?php } ?>
                
                <?php if ('' != $item['CommentEmail']) { ?>
                | <?=$item['CommentEmail']?>
                <?php } ?>
                
                <?php if ('' != $item['CommentUrl']) { ?>
                | <?=$item['CommentUrl']?>
                <?php } ?>
                
                <?php if (-1 == $item['CommentStatus']) { ?>
                | <span style="font-family: 'Courier New'; padding: 2px; background-color: #ffeeee;">:(</span>
                <?php } else if (0 == $item['CommentStatus']) { ?>
                | <span style="font-family: 'Courier New'; padding: 2px; background-color: #eeeeee;">:|</span>
                <?php } else if (1 == $item['CommentStatus']) { ?>
                | <span style="font-family: 'Courier New'; padding: 2px; background-color: #eeffee;">:)</span>
                <?php } ?>
                
                | IP: <?=substr($item['IP'], 0, 6)?>***
                
                | <?php include 'commentrate.php'; ?>
            </div>
            <div><?=$item['CommentText']?></div>
            
            <?php if ('' != $item['AnswerText']) { ?>
            <blockquote class="answer">
                <div><b><?=$item['AnswerAuthor']?></b> &nbsp; <?=$item['AnswerEmail']?> &nbsp; <?=$item['AnswerUrl']?></div>
                <div><?=$item['AnswerText']?></div>
            </blockquote>
            <?php } ?>
        </div>
    <?php } ?>
    </div>
    <div class="commentspages"><?php $this->renderInteractionPagination(); ?></div>
<?php } ?>

<?php
$captcha = $tools->getCaptcha()->getData();
$users = $this->core->getUsers();
$user = $users->getCurrent();
$userName = $user ? $user['Title'] : '';
?>
<div class="commentsform" id="commentsFormContainer">
<div class="commentsformtitle">Добавить комментарий</div>
<br>
<script type="text/javascript">
function checkCommentForm(f)
{
    if (0 == f.elements['text'].value.length) {
        alert('Оставьте комментарий');
        f.elements['text'].focus();
        return false;
    }
    if (0 == f.elements['<?=$captcha['PostFieldValue']?>'].value.length) {
        alert('Введите проверочный код');
        f.elements['<?=$captcha['PostFieldValue']?>'].focus();
        return false;
    }
    return sendCommentForm(f, '<?=$captcha['PostFieldKey']?>', '<?=$captcha['PostFieldValue']?>');
}
</script>
<form method="post" action="?rnd=<?=time()?>#comments" id="commentForm" onsubmit="return checkCommentForm(this)">
<textarea name="text" rows="5" cols="20" style="width: 360px;"></textarea>
<br><br>
Статус комментария
<input type="radio" name="status" value="-1" id="status-1"><label for="status-1">плохой</label>
<input type="radio" name="status" value="0" id="status0"><label for="status0">нейтральный</label>
<input type="radio" name="status" value="1" id="status1"><label for="status1">хороший</label>
<br><br>
Имя <input type="text" name="author" value="<?=htmlspecialchars($userName)?>" maxlength="255" style="width: 360px;">
<br><br>
Email <input type="text" name="email" value="" maxlength="255" style="width: 150px;">
WWW <input type="text" name="url" value="" maxlength="255" style="width: 150px;">
<br>
<?php $tools->getCaptcha()->show(); ?>
<input id="commentFormSubmit" type="submit" value=" Отправить ">
</form>
</div>
