<p>Учетные данные для соединения с существующей базой данных уже присутствуют в файле конфигурации</p>
<form action="?r=<?=time()?>" method="post">
<input type="hidden" name="step" value="2">
<table>
<tr><td>Сервер базы данных / Host</td><td><?=C_DB_SERVER?></td></tr>
<tr><td>Имя пользователя / User</td><td><?=C_DB_USER?></td></tr>
<tr><td>Имя базы данных / Base name</td><td><?=C_DB_NAME?></td></tr>
<tr><td>Префикс имен таблиц / Tables prefix</td><td><?=C_DB_TABLE_PREFIX?></td></tr>
<tr><th colspan="2"><input type="submit" value="далее / next"></th></tr>
</table>
</form>
