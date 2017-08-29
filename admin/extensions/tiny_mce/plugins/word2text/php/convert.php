<?php

function Upload()
{
    $input_file_name_default = 'uploadfile';
    $input_file_name = $input_file_name_default;
    
    //если поле input:file имеет имя отличное от умолчания, его можно передать
    if (isset($_GET[$input_file_name_default])) {
        $input_file_name = $_GET[$input_file_name_default];
    }
    
    /* DEBUG echo '<pre>'; var_dump($_FILES); echo '</pre>'; */
    if (!isset($_FILES[$input_file_name])) {
        return false;
    }
    
    /*
    if (C_UPLOAD_MAXFILESIZE < $_FILES[$input_file_name]['size']) {
        return false;
    }
    */
    
    $pos = strrpos($_FILES[$input_file_name]['name'], '.');
    if (false === $pos) {
        return false;
    }
    $ext = strtolower(substr($_FILES[$input_file_name]['name'], $pos + 1));
    /* DEBUG echo '<pre>'; var_dump($ext); echo '</pre>'; */
    switch ($ext) {
        case 'doc':
        case 'docx':
        case 'odt':
            break;
        default:
            return false;
    }
    
    return array($_FILES[$input_file_name]['tmp_name'], $ext);
}

function Convert()
{
    $arr = Upload();
    /* DEBUG echo '<pre>'; var_dump($arr); echo '</pre>'; */
    if (!is_array($arr)) {
        return false;
    }
    list($file, $ext) = $arr;
    switch ($ext) {
        case 'doc':
            //require_once('doc.php');
            $func_name = 'doc2text';
            break;
        case 'docx':
            //require_once('xml.php');
            $func_name = 'docx2text';
            break;
        case 'odt':
            //require_once('xml.php');
            $func_name = 'odt2text';
            break;
        default:
            return false;
    }
    
    return $func_name($file);
}

ini_set('display_errors', '1');
ini_set('error_reporting', E_ALL);
header('Content-type: text/html; charset=utf-8');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s', time() - 1000000) . ' GMT');
require_once('doc.php');
require_once('xml.php');
$text = Convert();
?><!DOCTYPE html>
<html>
<head>
<title>Word2text</title>
<script type="text/javascript" src="for_tinymce.js"></script>
</head>
<body>
<form action="#">
<div style=" height: 430px;">
<textarea id="text" id="name" rows="10" cols="40" style="width: 620px; height: 420px;"><?php echo str_replace('<', '&lt;', (string) $text); ?></textarea>
</div>
<div style="margin-top: 10px;">
<input id="insert" type="button" onclick="insertHtml(this.form.text.value);" value="Вставить" name="insert" />
<input id="cancel" type="button" onclick="tinyMCEPopup.close();" value="Отменить" name="cancel" />
</div>
</form>
</body>
</html>