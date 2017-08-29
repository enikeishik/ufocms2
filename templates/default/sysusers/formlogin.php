<?php
/**
 * @var \Ufocms\Modules\View $this
 * @var \Ufocms\Frontend\Core $core
 * @var \Ufocms\Frontend\Tools $tools
 * @var array|null $site
 * @var array|null $section
 * @var string $path
 */

?>
<form action="/users?action=login" method="post">
<input type="hidden" name="from" value="<?=$path?>">
<input type="text" name="login" value="" placeholder="логин" maxlength="255">
<input type="password" name="password" value="" placeholder="пароль" maxlength="255">
<input type="submit" value="Войти">
</form>
<div><a href="/users?form=register">регистрация</a></div>
<div>
<script src="//ulogin.ru/js/ulogin.js"></script><a href="#" id="uLogin" data-ulogin="display=window;theme=classic;fields=first_name,last_name;providers=;hidden=;redirect_uri=http%3A%2F%2Fufo%2Fulogin%3Faction%3Dulogin%26from%3D<?=urlencode($path)?>;mobilebuttons=0;"><img src="http://ulogin.ru/img/button.png?version=img.2.0.0" width=187 height=30 alt="МультиВход"/></a>
</div>
