<?php
//отладка
define('C_DEBUG', false);
define('C_DEBUG_LEVEL', 0);
//режим read-only для БД
define('C_DB_READONLY', false);
//кэширование
define('C_CACHE', false);

//подключение к базе данных
define('C_DB_SERVER', 'localhost');
define('C_DB_USER', 'root');
define('C_DB_PASSWD', '');
define('C_DB_NAME', 'cctestsdb');
define('C_DB_TABLE_PREFIX', '');
define('C_DB_CHARSET', 'utf8');

//HTML код разделителя страниц, анонсов и т.п.
define('C_SITE_PAGEBREAK_SEPERATOR', '<!-- pagebreak -->');

//используемый шаблон
//define('C_THEME', 'ufocms');

//использовать системную аутентификацию для входа в админку
define('C_ADMIN_SYS_AUTH', false);
