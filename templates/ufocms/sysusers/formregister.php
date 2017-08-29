<?php
/**
 * @var \Ufocms\Modules\View $this
 * @var \Ufocms\Frontend\Core $core
 * @var \Ufocms\Frontend\Tools $tools
 * @var array|null $site
 * @var array|null $section
 * @var array|null $settings
 */

?>
<form action="/users?action=register" method="post">
<p>Логин: <input type="text" name="login" value="" maxlength="255"></p>
<p>Пароль: <input type="password" name="password" value="" maxlength="255"></p>
<p>Повторите пароль: <input type="password" name="password2" value="" maxlength="255"></p>
<p>Email: <input type="text" name="email" value="" maxlength="255"></p>
<p>Отображаемое имя: <input type="text" name="title" value="" maxlength="255"></p>
<p><?php if ($settings['IsCaptcha']) $tools->getCaptcha()->show(); ?></p>
<p><input type="submit" value="Зарегистрироваться"></p>
</form>
