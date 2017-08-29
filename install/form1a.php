<p>Укажите учетные данные для соединения с существующей базой данных</p>
<form action="?r=<?=time()?>" method="post">
<input type="hidden" name="step" value="2">
<table>
<tr><td>Сервер базы данных / Host</td><td><input type="text" name="host" value="" required></td></tr>
<tr><td>Имя пользователя / User</td><td><input type="text" name="user" value="" required></td></tr>
<tr><td>Пароль / Password</td><td><input type="password" name="password" value=""></td></tr>
<tr><td>Подтверждение пароля / Password confirmation</td><td><input type="password" name="password2" value=""></td></tr>
<tr><td>Имя базы данных / Base name</td><td><input type="text" name="base" value="" required></td></tr>
<tr><td>Префикс имен таблиц / Tables prefix</td><td><input type="text" name="prefix" value=""></td></tr>
<tr><th colspan="2"><input type="submit" value="далее / next"></th></tr>
</table>
</form>
