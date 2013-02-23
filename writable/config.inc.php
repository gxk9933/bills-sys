<?php 
ini_set('display_errors', 'on');
error_reporting(E_ALL & ~ E_NOTICE);

define('MYSQLHOST', '127.0.0.1');
define('MYSQLPORT', '3306');
define('MYSQLUSER', 'root');
define('MYSQLPASS', '123');
define('DB_NAME', 'bills');
define('TABLE_PREFIX', 'bills_');

define('WEB_NAME', '账单后台');