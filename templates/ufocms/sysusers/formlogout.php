<?php
/**
 * @var \Ufocms\Modules\View $this
 * @var \Ufocms\Frontend\Core $core
 * @var \Ufocms\Frontend\Tools $tools
 * @var array|null $site
 * @var array|null $section
 * @var string $path
 */

$user = $core->getUsers()->getCurrent();
if (null === $user) {
    return;
}
?>
<form action="/users?action=logout" method="post">
<input type="hidden" name="from" value="<?=$path?>">
Добро пожаловать, <?=$user['Title']?> <input type="submit" value="Выйти">
</form>
