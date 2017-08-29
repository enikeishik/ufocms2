<?php
ini_set('display_errors', '1');
ini_set('error_reporting', E_ALL);
date_default_timezone_set('Europe/Moscow');
setlocale(LC_ALL, 'ru_RU.utf-8', 'rus_RUS.utf-8', 'ru_RU.utf8');
mb_internal_encoding('UTF-8');

define('C_ROOT', $_SERVER['DOCUMENT_ROOT']);
define('C_CONFIG_PATH', C_ROOT . '/config.php');
define('C_INSTALL_PATH', C_ROOT . '/install');
define('C_BEGIN_TEMPLATE', C_INSTALL_PATH . '/begin.php');
define('C_END_TEMPLATE', C_INSTALL_PATH . '/end.php');
define('C_FORM1A_TEMPLATE', C_INSTALL_PATH . '/form1a.php');
define('C_FORM1B_TEMPLATE', C_INSTALL_PATH . '/form1b.php');
define('C_FORM2A_TEMPLATE', C_INSTALL_PATH . '/form2a.php');
define('C_FORM2B_TEMPLATE', C_INSTALL_PATH . '/form2b.php');
define('C_FORM3_TEMPLATE', C_INSTALL_PATH . '/form3.php');
define('C_FORM4_TEMPLATE', C_INSTALL_PATH . '/form4.php');
define('C_SQLFILES_PATH', C_INSTALL_PATH . '/sql');
define('C_SQLFILES_EXT', 'sql');
define('C_SQL_TABLE_PREFIX', '/* TABLE_PREFIX */');

/**
 * @throws Exception
 */
function checkConfig()
{
    if (!file_exists(C_CONFIG_PATH)) {
        throw new Exception('Файл конфигурации не найден / Configuration file not exists');
    }
    require_once C_CONFIG_PATH;
    if (!defined('C_DB_SERVER') 
    || !defined('C_DB_USER') 
    || !defined('C_DB_PASSWD') 
    || !defined('C_DB_NAME') 
    || !defined('C_DB_TABLE_PREFIX')) {
        throw new Exception('Файл конфигурации имеет недопустимый формат / Configuration file have wrong format');
    }
    if (!is_writable(C_CONFIG_PATH)) {
        throw new Exception('Файл конфигурации не доступен для записи / Configuration file not writable');
    }
}

/**
 * @throws Exception
 */
function checkPost()
{
    if (!isset($_SERVER['HTTP_REFERER']) || !isset($_SERVER['HTTP_HOST'])) {
        throw new Exception('Referer not set');
    }
    if (false === strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST'])) {
        throw new Exception('Referer not correct');
    }
    if (!isset($_POST['host']) 
    || !isset($_POST['user']) 
    || !isset($_POST['password']) 
    || !isset($_POST['password2']) 
    || !isset($_POST['base']) 
    || !isset($_POST['prefix'])) {
        throw new Exception('Some form field(s) not set');
    }
    if ('' == $_POST['host'] || '' == $_POST['user'] || '' == $_POST['base']) {
        throw new Exception('Required field not set');
    }
    if ('' != $_POST['password'] && $_POST['password'] != $_POST['password2']) {
        throw new Exception('Password confirmation failed');
    }
}

/**
 * @throws Exception
 * @todo make implementation for non empty constants
 */
function writeConfig()
{
    $defs = [
        "define('C_DB_SERVER', '');", 
        "define('C_DB_USER', '');", 
        "define('C_DB_PASSWD', '');", 
        "define('C_DB_NAME', '');", 
        "define('C_DB_TABLE_PREFIX', '');", 
    ];
    $vals = [
        "define('C_DB_SERVER', '" .         str_replace(['\\', "'"], ['\\\\', "\'"], $_POST['host'])        . "');", 
        "define('C_DB_USER', '" .           str_replace(['\\', "'"], ['\\\\', "\'"], $_POST['user'])        . "');", 
        "define('C_DB_PASSWD', '" .         str_replace(['\\', "'"], ['\\\\', "\'"], $_POST['password'])    . "');", 
        "define('C_DB_NAME', '" .           str_replace(['\\', "'"], ['\\\\', "\'"], $_POST['base'])        . "');", 
        "define('C_DB_TABLE_PREFIX', '" .   str_replace(['\\', "'"], ['\\\\', "\'"], $_POST['prefix'])      . "');", 
    ];
    if (false === $config = @file_get_contents(C_CONFIG_PATH)) {
        throw new Exception('Не удается прочитать файл конфигурации / Reading configuration file failed');
    }
    $config = str_replace($defs, $vals, $config);
    //echo '<pre>' . htmlspecialchars($config) . '</pre>';
    if (false === @file_put_contents(C_CONFIG_PATH, $config)) {
        throw new Exception('Не удается осуществить запись в файл конфигурации / Writing configuration file failed');
    }
}

/**
 * @throws Exception
 */
function checkNewDbConnect()
{
    checkDbConnect($_POST['host'], $_POST['user'], $_POST['password'], $_POST['base']);
}

/**
 * @throws Exception
 */
function checkExistingDbConnect()
{
    checkDbConnect(C_DB_SERVER, C_DB_USER, C_DB_PASSWD, C_DB_NAME);
}

/**
 * @throws Exception
 */
function checkDbConnect($host, $user, $password, $base)
{
    if (false !== $link = @mysqli_connect($host, $user, $password, $base)) {
        mysqli_close($link);
    } else {
        throw new Exception('Connect to database failed, error: ' . 
                            preg_replace('/[^a-z0-1\s\.\-;:,_~]+/i', '', mysqli_connect_error()));
    }
}

/**
 * @throws Exception
 * @todo make check prefix and execute queries if no exists tables with prefix in config
 */
function execSqls()
{
    $sqlFiles = glob(C_SQLFILES_PATH . '/*.' . C_SQLFILES_EXT);
    if (!is_array($sqlFiles)) {
        throw new Exception('There are no SQL files');
    }
    if (0 == count($sqlFiles)) {
        throw new Exception('There are no SQL files');
    }
    
    if (false === $link = @mysqli_connect(C_DB_SERVER, C_DB_USER, C_DB_PASSWD, C_DB_NAME)) {
        throw new Exception('Connect to database failed, error: ' . 
                            preg_replace('/[^a-z0-1\s\.\-;:,_~]+/i', '', mysqli_connect_error()));
    }
    if (false === $result = mysqli_query($link, 'SHOW TABLES')) {
        throw new Exception('Query execution error');
    }
    if (0 < mysqli_num_rows($result)) {
        throw new Exception('DB must be empty, but this are not');
    }
    mysqli_free_result($result);
    
    //var_dump($sqlFiles); return;
    mysqli_query($link, 'SET NAMES ' . C_DB_CHARSET);
    echo '<div class="addinfo">' . "\r\n";
    foreach ($sqlFiles as $sqlFile) {
        echo '<div><b>' . basename($sqlFile) . "</b><br>\r\n";
        if (false !== $content = file_get_contents($sqlFile)) {
            $sqls = explode(';', str_replace(C_SQL_TABLE_PREFIX, C_DB_TABLE_PREFIX, $content));
            foreach ($sqls as $sql) {
                if ('' != $sql = trim($sql)) {
                    echo "SQL: <code>" . $sql . "</code><br>\r\n";
                    if (mysqli_query($link, $sql)) {
                        echo "<b>complete</b><br><br>\r\n";
                    } else {
                        echo '<code class="error">' . mysqli_error($link) . "</code><br><br>\r\n";
                    }
                }
            }
        } else {
            echo '<code class="error">not readable</code><br>' . "\r\n";
        }
        echo "</div>\r\n";
    }
    echo "</div>\r\n";
    mysqli_close($link);
}

function disableInstall()
{
    $f = C_INSTALL_PATH . '/.htaccess';
    if (@rename($f, $f . '~')) {
        @file_put_contents($f, 'Deny from all');
    }
}


ob_implicit_flush(false);
ob_start();
header('Content-type: text/html; charset=utf-8');
require_once C_BEGIN_TEMPLATE;
try {
    if (!isset($_SERVER['REQUEST_METHOD']) || 0 != strcasecmp('POST', $_SERVER['REQUEST_METHOD'])) {
        checkConfig();
        if ('' == C_DB_NAME) {
            require_once C_FORM1A_TEMPLATE;
        } else {
            require_once C_FORM1B_TEMPLATE;
        }
    } else {
        if (!isset($_POST['step'])) {
            throw new Exception('Wrong form');
        }
        $step = (int) $_POST['step'];
        switch ($step) {
            case 2:
                require_once C_CONFIG_PATH;
                if ('' == C_DB_NAME) {
                    checkPost();
                    checkNewDbConnect();
                    writeConfig();
                    require_once C_FORM2A_TEMPLATE;
                } else {
                    checkExistingDbConnect();
                    require_once C_FORM2B_TEMPLATE;
                }
                break;
            case 3:
                require_once C_CONFIG_PATH;
                execSqls();
                require_once C_FORM3_TEMPLATE;
                break;
            case 4:
                disableInstall();
                require_once C_FORM4_TEMPLATE;
                break;
            default:
                throw new Exception('Wrong form');
        }
    }
} catch (Exception $e) {
    echo '<div class="error"><span>Ошибка / Error</span>' . $e->getMessage() . '</div>';
}
require_once C_END_TEMPLATE;
exit();


if (!mysql_connect(C_DB_SERVER, C_DB_USER, C_DB_PASSWD)) {
	exit(mysql_error());
}
if (!mysql_select_db(C_DB_NAME)) {
	exit(mysql_error());
}
if (false === $result = mysql_list_tables(C_DB_NAME)) {
	exit(mysql_error());
}
if (0 < mysql_num_rows($result)) {
	exit('DB must be empty, but this are not');
}
mysql_query('SET NAMES CP1251');


mysql_close();
