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
<?php if (0 < count($items)) { ?>
    <a name="comments"></a>
    <h5>Комментарии</h5>
    <div class="rating">Рейтинг: <?=round($rating['val'], 2)?>, голосов: <?=$rating['cnt']?></div>
    <div class="comments">
    <?php foreach ($items as $item) { ?>
        <div class="comment">
            <div><?=$item['comment']?></div>
            <div><?=$item['comment_sign']?></div>
        </div>
    <?php } ?>
    </div>
    <div class="commentspages"><?php $this->renderCommentsPagination(); ?></div>
<?php } ?>

<?php
$captcha = $tools->getCaptcha()->getData();
?>
<div class="commentsform">
<div class="commentsformtitle">Добавить комментарий и проголосовать</div>
<br>
<script type="text/javascript">
function Comments_CheckForm(f)
{
    if (0 == f.elements['comment'].value.length)
    {
        alert('Оставьте комментарий');
        f.elements['comment'].focus();
        return false;
    }
    /*
    if (!document.getElementById('rate5').checked
        && !document.getElementById('rate4').checked
        && !document.getElementById('rate3').checked
        && !document.getElementById('rate2').checked
        && !document.getElementById('rate1').checked)
    {
        alert('Оцените материал страницы');
        return false;
    }
    */
    if ('undefined' != typeof(f.elements['<?=$captcha['PostFieldValue']?>']) 
    && 0 == f.elements['<?=$captcha['PostFieldValue']?>'].value.length)
    {
        alert('Введите проверочный код');
        f.elements['<?=$captcha['PostFieldValue']?>'].focus();
        return false;
    }
    return true;
}
</script>
<form method="post" action="?commentsadd=1&rnd=<?=time()?>#comments" onsubmit="return Comments_CheckForm(this)">
<textarea name="comment" rows="5" cols="10" style="width: 360px;"></textarea>
<br><br>
Имя <input type="text" name="sign" value="" maxlength="255" style="width: 360px;">
<br><br>
Email <input type="text" name="email" value="" maxlength="255" style="width: 150px;">
WWW <input type="text" name="url" value="" maxlength="255" style="width: 150px;">
<br><br>
Оценка<br>
<label><input type="radio" name="rate" value="5">&nbsp;5&nbsp;</label>
<label><input type="radio" name="rate" value="4">&nbsp;4&nbsp;</label>
<label><input type="radio" name="rate" value="3">&nbsp;3&nbsp;</label>
<label><input type="radio" name="rate" value="2">&nbsp;2&nbsp;</label>
<label><input type="radio" name="rate" value="1">&nbsp;1&nbsp;</label>
<br>
<?php $tools->getCaptcha()->show(); ?>
<input type="submit" value=" Отправить ">
</form>
</div>
