<?php
/**
 * @var \Ufocms\Modules\View $this
 * @var \Ufocms\Frontend\Tools $tools
 * @var array|null $site
 * @var array|null $section
 * @var array|null $settings
 * @var array|null $item
 * @var array|null $items
 */
?>
<div class="shoporderform">

<?php include_once 'menu.php'; ?>

<h1>Заказ</h1>

<form action="<?=$section['path']?>action?order=confirm&rnd=<?=microtime(true)?>" method="post">
<table>
<tr><th>Адрес<sup>*</sup></th><td><input type="text" name="address" maxlength="255" value="" required></td></tr>
<tr><th>Email</th><td><input type="text" name="email" maxlength="255" value=""></td></tr>
<tr><th>Телефон</th><td><input type="text" name="phone" maxlength="255" value=""></td></tr>
<tr><th>Комментарий</th><td><input type="text" name="comment" maxlength="255" value=""></td></tr>
<tr><td colspan="2" style="text-align: center;"><input type="submit" value="Отправить"></td></tr>
</table>
</form>

</div>
