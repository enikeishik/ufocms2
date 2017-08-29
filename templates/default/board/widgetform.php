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
.widgetboardform { text-align: center; }
.widgetboardform input, .widgetboardform select, .widgetboardform textarea { width: 90%; }
.widgetboardform form label { font-size: 12px; }
</style>
<div class="widget">
<?php if ($showTitle) { ?>
<h3><?=$title?></h3>
<?php } ?>
<div class="widgetboardform">
<script type="text/javascript">
function checkBoardFormContent(txt)
{
    var re = new RegExp("(http://)|(https://)|(ftp://)|(www\.[^\.]+\.[A-Za-z]+)", "i");
    var re2 = new RegExp("[А-Яа-я]+", "i");
    return !re.test(txt) && re2.test(txt);
}
function checkBoardForm(f)
{
    if (0 == f.section.selectedIndex) {
        alert('Укажите раздел для публикации');
        f.section.focus();
        return false;
    } else if (0 == f.elements['title'].value.length) {
        alert('Укажите заголовок сообщения');
        f.elements['title'].focus();
        return false;
    } else if (0 == f.elements['message'].value.length) {
        alert('Введите текст сообщения');
        f.elements['message'].focus();
        return false;
    } else if (0 == f.elements['contacts'].value.length) {
        alert('Укажите контактную информацию');
        f.elements['contacts'].focus();
        return false;
    } else if (!checkBoardFormContent(f.elements['title'].value)) {
        alert("Поле заголовка содержит недопустимые символы");
        f.elements['title'].focus();
        return false;
    } else if (!checkBoardFormContent(f.elements['message'].value)) {
        alert("Поле сообщения содержит недопустимые символы");
        f.elements['message'].focus();
        return false;
    } else if (!checkBoardFormContent(f.elements['contacts'].value)) {
        alert("Поле контактной информации содержит недопустимые символы");
        f.elements['contacts'].focus();
        return false;
    }
    f.action = f.section.options[f.section.selectedIndex].value + 'action2';
    return true;
}
</script>
<form action="" method="post" onsubmit="return checkBoardForm(this)">
<p><select name="section"><option>Выберите раздел для публикации</option>
<?php foreach ($items as $item) { ?>
    <option value="<?=$item['path']?>"><?=htmlspecialchars($item['indic'])?></option>
<?php } ?>
</select>
<p><label>Заголовок:</label><br><input maxlength="255" name="title" size="20" type="text" value=""></p>
<p><label>Сообщение:</label><br><textarea name="message" rows="6" cols="20"></textarea></p>
<p><label>Контактная информация:</label><br><textarea name="contacts" rows="6" cols="20"></textarea></p>
<?php
if ($isCaptcha) {
    $tools->getCaptcha()->show();
}
?>
<p><input type="submit" value="Добавить сообщение"></p>
</form>
</div>
</div>
<?php } ?>
