<?php require_once 'templates/layout-begin.php'; ?>

<h2>Установка модуля</h2>
<form action="<?=$this->basePath?>&action=installmodule" method="post">
<input type="url" name="url" value="" maxlength="250" placeholder="http://github.com/author/repository/archive/master.zip" required size="60">
<input type="submit" value="Установить">
</form>

<?php require_once 'templates/layout-end.php'; ?>