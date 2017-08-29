<?php
header('Content-type: text/html; charset=utf-8');
?><!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Установка UFOCMS / Installation of UFOCMS</title>
<style>
body { margin: 0px auto; width: 800px; font-family: Tahoma; font-size: 12px; }
table { margin: 0px auto; }
.info { color: #999; }
div.error { border: solid #f66 2px; padding: 20px; }
div.error span { display: block; margin-top: -15px; margin-left: -15px; margin-bottom: 15px; font-weight: bold; }
div.addinfo { height: 200px; overflow: auto; border: solid #666 1px; padding: 10px; }
div.addinfo>div { margin-top: 5px; border-top: dashed #666 1px; padding-top: 30px; margin-bottom: 5px; border-bottom: dashed #666 1px; padding-bottom: 30px; }
code.error { color: #f66; font-weight: bold; }
</style>
</head>
<body>
<p class="info">Процесс установки системы состоит из нескольких шагов. 
Вам необходимо будет ввести учетные данные для соединения 
с существующей базой данных. Эти данные будут сохранены 
в файле конфигурации, затем будет создана структура базы данных.</p>
