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
<h5>Восстановление пароля</h5>
<form action="/users?action=recover" method="post">
<p>Логин: <input type="text" name="login" value="" maxlength="255"></p>
<p><?php if ($settings['IsCaptcha']) $tools->getCaptcha()->show(); ?></p>
<p><input type="submit" value="Восстановить пароль"></p>
</form>
