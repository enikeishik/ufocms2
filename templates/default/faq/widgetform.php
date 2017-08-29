<?php
/**
 * @var \Ufocms\Modules\Widget $this
 * @var \Ufocms\Frontend\Tools $tools
 * @var array $moduleParams
 * @var bool $showTitle
 * @var string $title
 * @var string $content
 * @var array $items
 * @var bool $isCaptcha
 */
?>
<?php if (0 < count($items)) { ?>
<style type="text/css">
.widgetfaqform { text-align: center; }
.widgetfaqform input, .widgetfaqform select, .widgetfaqform textarea { width: 90%; }
.widgetfaqform form label { font-size: 12px; }
</style>
<div class="widget">
<?php if ($showTitle) { ?>
<h3><?=$title?></h3>
<?php } ?>
<div class="widgetboardform">
<script type="text/javascript">
function checkFaqFormContent(txt)
{
    var re = new RegExp("(http://)|(https://)|(ftp://)|(www\.[^\.]+\.[A-Za-z]+)", "i");
    var re2 = new RegExp("[А-Яа-я]+", "i");
    return !re.test(txt) && re2.test(txt);
}
function checkFaqForm(f)
{
    if (0 == f.section.selectedIndex) {
        alert('Укажите раздел для публикации');
        f.section.focus();
        return false;
    } else if (0 == f.elements['sign'].value.length) {
        alert('Укажите имя');
        f.elements['sign'].focus();
        return false;
    } else if (0 == f.elements['message'].value.length) {
        alert('Введите текст сообщения');
        f.elements['message'].focus();
        return false;
    } else if (!checkFaqFormContent(f.elements['sign'].value)) {
        alert("Поле имени содержит недопустимые символы");
        f.elements['sign'].focus();
        return false;
    } else if (!checkFaqFormContent(f.elements['message'].value)) {
        alert("Поле сообщения содержит недопустимые символы");
        f.elements['message'].focus();
        return false;
    }
    f.action = f.section.options[f.section.selectedIndex].value + 'action2';
    return true;
}
</script>
<form action="" method="post" onsubmit="return checkFaqForm(this)">
<p><select name="section"><option>Выберите раздел для публикации</option>
<?php foreach ($items as $item) { ?>
    <option value="<?=$item['path']?>"><?=htmlspecialchars($item['indic'])?></option>
<?php } ?>
</select>
<p><label>Автор<sup style="color: #f00">*</sup></label><br><input type="text" name="sign" value="" maxlength="250" size="20"></p>
<p><label>Email</label><br><input type="text" name="email" value="" maxlength="250" size="20"></p>
<p><label>WWW</label><br><input type="text" name="url" value="" maxlength="250" size="20"></p>
<p><label>Вопрос<sup style="color: #f00">*</sup></label><br><textarea name="message" rows="6" cols="20"></textarea></p>
<?php
if ($isCaptcha) {
    $tools->getCaptcha()->show();
}
?>
<p><input type="submit" value="Добавить вопрос"></p>
</form>
</div>
</div>
<?php } ?>
