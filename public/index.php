<?php
header('Content-Type: text/html; charset=ISO-8859-1');
date_default_timezone_set("America/Bogota");
session_start();
error_reporting(E_ALL ^ E_NOTICE);
define("DS", DIRECTORY_SEPARATOR);
define("BASE_DIR",  dirname(__DIR__) . DS);
require_once dirname(__DIR__) . DS . "bootstrap" . DS . "app.php";
$app->run();