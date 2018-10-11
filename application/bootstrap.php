<?php

if(!isset($_GET['debug']))error_reporting(E_ERROR | E_PARSE);
//require_once 'config.php';
require_once 'core/class_mysql.php';

require 'application/config.php';
$db = new SafeMySQL(array('user' => $DBUSER,'pass' => $DBPASS, 'db' => $DB,'charset' => 'utf8'));


require_once 'core/functions.php';



require_once 'core/model.php';
require_once 'core/view.php';
require_once 'core/controller.php';
require_once 'core/route.php';


//require_once 'core/class_mysql.php';



Route::start(); // запускаем маршрутизатор
?>
