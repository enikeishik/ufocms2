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
 * @var mixed $actionResult
 */
?>
<div class="newsform">
<div class="newsformtitle">Добавить новость</div>
<form action="action2" method="post">
<table border="0" cellpadding="10" cellspacing="0">
<tr><td>Заголовок:</td><td><input type="text" name="title" value="" maxlength="250"></td></tr>
<tr><td colspan="2">Анонс:<br /><textarea name="announce"></textarea></td></tr>
<tr><td colspan="2">Текст:<br /><textarea name="body"></textarea></td></tr>
<tr><td colspan="2" align="center"><input type="submit" value="Отправить"></td></tr>
</table>
</form>
</div>
