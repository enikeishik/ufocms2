<?php
require_once 'presets.php';

if (!isset($_SERVER['REQUEST_METHOD']) || 0 != strcasecmp('POST', $_SERVER['REQUEST_METHOD'])) {
    exit();
}
$sectionId = isset($_GET['sectionid']) ? (int) $_GET['sectionid'] : 0;
if (0 == $sectionId) {
    exit();
}

header('Last-Modified: ' . gmdate('D, d M Y H:i:s', time() - 1000000) . ' GMT');
header('Expires: ' . gmdate('D, d M Y H:i:s', time() - 1000000) . ' GMT');
header('Content-type: text/javascript; charset=utf-8');

$config = new \Ufocms\Frontend\Config();
$params = new \Ufocms\Frontend\Params();
$db = new \Ufocms\Frontend\Db($debug);
$tools = new \Ufocms\Frontend\Tools($config, $params, $db, $debug);
$interaction = new \Ufocms\Frontend\InteractionManage($config, $params, $db, $debug);
$core = new \Ufocms\Frontend\Core($config, $params, $db, $debug);
$user = $core->getUsers()->getCurrent();
unset($core);

$interaction->setParams(
    $sectionId, 
    (isset($_GET['itemid']) ? (int) $_GET['itemid'] : 0), 
    (null === $user ? 0 : $user['Id']), 
    true
);

if (isset($_POST['rate'])) {
    if ($interaction->AddRate()) {
        $rating = $interaction->getRating();
        $data = 'objData={Message:"Голос добавлен",Rating:"' . $rating['Rating'] . '",RatesCnt:"' . $rating['RatesCnt'] . '"};';
        echo    'errCode=0;strData="' . $tools->getSafeJsString($data) . '";' . "\r\n";
    } else {
        echo 'errCode=' . $interaction->getErrorCode() . ';strData="";' . "\r\n";
    }
    
} else if (isset($_POST['commentrate']) && isset($_POST['commentid'])) {
    if ($interaction->addCommentRate()) {
        $rating = $interaction->getCommentRating($_POST['commentid']);
        $data = 'objData={Message:"Оценка добавлена",Rating:"' . $rating['Rating'] . '",RatesCnt:"' . $rating['RatesCnt'] . '"};';
        echo    'errCode=0;strData="' . $tools->getSafeJsString($data) . '";' . "\r\n";
    } else {
        echo 'errCode=' . $interaction->getErrorCode() . ';strData="";' . "\r\n";
    }
    
} else if (isset($_POST['text'])) {
    if ($interaction->addComment()) {
        if ($comment = $interaction->getLastComment()) {
            $commentData = '';
            foreach ($comment as $field => $value) {
                $commentData .= ',' . $field . ':"' . htmlspecialchars(addcslashes($value, "\0..\37\"\'\\"), ENT_NOQUOTES) . '"';
            }
            $data = 'objData={Message:"Комментарий добавлен"' . $commentData . '}';
            echo    "errCode=0;strData='" . $tools->getSafeJsString($data) . "';\r\n";
        } else {
            //database error
            echo 'errCode=8;strData="";' . "\r\n";
        }
    } else {
        echo 'errCode=' . $interaction->getErrorCode() . ';strData="";' . "\r\n";
    }
    
}
