<?php
/**
 * @var \Ufocms\Modules\View $this
 * @var \Ufocms\Frontend\Tools $tools
 * @var array|null $site
 * @var array|null $section
 * @var array|null $settings
 * @var array|null $item
 * @var array|null $items
 * @var int|null $itemsCount
 */
?>
<div class="boardform">
<script type="text/javascript">
function containLinks(txt)
{
    var re = new RegExp("(http://)|(https://)|(ftp://)|(www\.[^\.]+\.[A-Za-z]+)", "i");
    return re.test(txt);
}
function CheckBrdForm(f)
{
    if (0 == f.elements['sign'].value.length) {
        alert('Укажите автора вопроса');
        f.elements['sign'].focus();
        return false;
    } else if (0 == f.elements['message'].value.length) {
        alert('Введите вопрос');
        f.elements['message'].focus();
        return false;
    } else if (containLinks(f.elements['sign'].value)) {
        alert("Поле автора содержит недопустимые символы");
        f.elements['sign'].focus();
        return false;
    } else if (containLinks(f.elements['message'].value)) {
        alert("Поле вопроса содержит недопустимые символы");
        f.elements['message'].focus();
        return false;
    }
    return true;
}
</script>
<h4 align="center">Добавить вопрос</h4>
<form action="<?=$section['path']?>action2" method="post" onsubmit="return CheckBrdForm(this)">
<table border="0" cellpadding="10" cellspacing="0" align="center">
<tr><td>Автор<sup style="color: #f00">*</sup>:</td><td><input type="text" name="sign" value="" maxlength="250" size="40" style="width: 250px;"></td></tr>
<tr><td>Email:</td><td><input type="text" name="email" value="" maxlength="250" size="40" style="width: 250px;"></td></tr>
<tr><td>WWW:</td><td><input type="text" name="url" value="" maxlength="250" size="40" style="width: 250px;"></td></tr>
<tr><td colspan="2">Вопрос<sup style="color: #f00">*</sup>:<br /><textarea name="message" rows="6" cols="40" style="width: 350px;"></textarea></td></tr>
<tr><td colspan="2" align="center"><?php if ($settings['IsCaptcha']) $tools->getCaptcha()->show(); ?></td></tr>
<tr><td colspan="2" align="center"><input type="submit" value="Отправить" style="width: 150px;"></td></tr>
</table>
</form>
</div>
