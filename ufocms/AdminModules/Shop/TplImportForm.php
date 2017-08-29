<?php
/**
 * @var \Ufocms\AdminModules\Shop\UIImportForm $this
 * @var int $categoryId
 * @var array $categories
 */
?>
<p>Файл с данными должен быть в формате CSV (данные с разделителями) и содержать следующий список полей:<br />
<code>Title, Thumbnail, Image, ShortDesc, FullDesc, Price</code></p>
<form action="<?=$this->formHandler()?>" method="post" enctype="multipart/form-data">
<table border="1" cellpadding="3" cellspacing="1">
<tr><td>Операция</td><td>
<select name="operation" required>
<option value=""></option>
<option value="insert">Добавление новых данных</option>
<option value="update">Изменение существующих данных</option>
</select>
</td></tr>
<tr><td>Раздел магазина</td><td>
<select name="categoryid">
<?php foreach ($categories as $category) { ?>
<option value="<?php echo $category['Value']; ?>"<?php if ($category['Parent']) { echo ' style="color: #999;"'; } ?><?php if ($categoryId == $category['Value']) { ?> selected<?php } ?>><?php echo $category['Title']; ?></option>
<?php } ?>
</select>
</td></tr>
<input type="hidden" name="relatedinfoid" value="0">
<tr><td>Файл с данными</td><td><input type="file" name="file" required></td></tr>
<tr><td>Символ разделителя данных</td><td>
<select name="delimiter">
<option value=",">,</option>
<option value=";">;</option>
<option value=":">:</option>
<option value="|">|</option>
<option value="tab">TAB</option>
</select>
</td></tr>
<tr><td>Символ ограничителя поля</td><td>
<select name="enclosure">
<option value='"'>&quot;</option>
<option value="'">&#39;</option>
</select>
</td></tr>
<tr><td>Удалить старые данные</td><td>
<input type="checkbox" name="replace" value="1">
</td></tr>
<tr><td colspan="2" align="center"><input type="submit"></td></tr>
</table>
</form>
