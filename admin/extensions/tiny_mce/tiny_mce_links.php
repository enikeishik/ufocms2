<?php
namespace Ufocms\Backend;

ini_set('display_errors', '1');
ini_set('error_reporting', E_ALL);
ini_set('scream.enabled', true);
date_default_timezone_set('Europe/Moscow');
setlocale(LC_ALL, 'ru_RU.utf-8', 'rus_RUS.utf-8', 'ru_RU.utf8');
mb_internal_encoding('UTF-8');

require_once '../../../config.php';
require_once '../../autoload.php';

function ShowLinks()
{
    $debug = null;
    if (defined('C_DEBUG') && C_DEBUG) {
        $debug = new \Ufocms\Frontend\Debug();
        //set_error_handler(array('\Ufocms\Frontend\Debug', 'errorHandler'));
    }
    $config = new Config();
    $audit = new Audit($config);
    $db = new Db($audit, $debug);
    $sql =  'SELECT levelid,path,indic' . 
            ' FROM ' . C_DB_TABLE_PREFIX . 'sections' . 
            ' WHERE isenabled!=0' . 
            ' ORDER BY mask';
    $items = $db->getItems($sql);
    $db->close();
    unset($db);
    foreach ($items as $item) {
        $indent = '';
        for ($i = 0; $i < $item['levelid']; $i++) {
            $indent .= '&nbsp;&nbsp;';
        }
        $indic = $indent . htmlspecialchars(addcslashes($item['indic'], "\0..\37\"\'\\"));
        if (60 < strlen($indic)) {
            $indic = mb_substr($indic, 0, 57) . '...';
        }
        echo "['" . $indic . "', '" . $item['path'] . "'],";
    }
}

@header('Last-Modified: ' . gmdate("D, d M Y H:i:s", time() - 600) . ' GMT');
@header('Cache-Control: max-age=3600');
@header('Expires: ' . gmdate("D, d M Y H:i:s", time() + 3600) . ' GMT');
@header('Content-type: text/javascript');
echo 'var tinyMCELinkList = new Array(';
ShowLinks();
echo '["", ""]);';
//Audit("info", "_editor", "showlinks");
