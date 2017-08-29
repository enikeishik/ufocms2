<!DOCTYPE html>
<html>
<head>
<title>Word2text</title>
<script type="text/javascript" src="for_tinymce.js"></script>
</head>
<body>
<form action="convert.php" method="post" enctype="multipart/form-data">
<div style="height: 240px; margin-top: 200px; text-align: center;">
<label>Файл <input type="file" name="uploadfile" style="width: 300px;" /></label>
</div>
<div style="margin-top: 10px;">
<input id="insert" type="submit" value="Отправить" />
<input id="cancel" type="button" onclick="tinyMCEPopup.close();" value="Отменить" name="cancel" />
</div>
</form>
</body>
</html>