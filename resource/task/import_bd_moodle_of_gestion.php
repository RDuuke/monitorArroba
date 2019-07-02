<?php
ini_set('display_errors', '1');
ini_set('memory_limit', '-1');

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR .  ".." . DIRECTORY_SEPARATOR  . "vendor" . DIRECTORY_SEPARATOR . "autoload.php";

$container = include_once dirname(__DIR__) . DIRECTORY_SEPARATOR .  ".." . DIRECTORY_SEPARATOR . "bootstrap" . DIRECTORY_SEPARATOR . "database.php";

$capsule = new \Illuminate\Database\Capsule\Manager();
$capsule->addConnection($container['settings']['db'], "db");
$capsule->addConnection($container['settings']['db_pregrado'], "db_pregrado");
$capsule->addConnection($container['settings']['db_postgrado'], "db_postgrado");
$capsule->addConnection($container['settings']['db_itm'], "db_itm");
$capsule->addConnection($container['settings']['db_colmayor'], "db_colmayor");
$capsule->addConnection($container['settings']['db_pascual'], "db_pascual");
$capsule->addConnection($container['settings']['db_ftdh'], "db_ftdh");
$capsule->setAsGlobal();
$capsule->bootEloquent();
$pdo = $capsule->getConnection("db")->getPdo();
$pdo->query("TRUNCATE TABLE gestion_arroba.courses_moodle");
$pdo = $capsule->getConnection("db_pregrado")->getPdo();
$pdo->query("INSERT INTO gestion_arroba.courses_moodle SELECT *, 'pregrado' AS instance FROM zadmin_mdlpregradoproduccion.mdl_course");
$pdo->query("INSERT INTO gestion_arroba.courses_moodle SELECT *, 'postgrado' AS instance FROM zadmin_mdlpostgradoproduccion.mdl_course");
$pdo->query("INSERT INTO gestion_arroba.courses_moodle SELECT *, 'colmayor' AS instance FROM zadmin_mdlcolmayorproduccion.mdl_course");
$pdo->query("INSERT INTO gestion_arroba.courses_moodle SELECT *, 'iupb' AS instance FROM zadmin_mdliupbproduccion.mdl_course");
$pdo->query("INSERT INTO gestion_arroba.courses_moodle SELECT *, 'itm' AS instance FROM zadmin_mdlitmproduccion.mdl_course");
$pdo->query("INSERT INTO gestion_arroba.courses_moodle SELECT *, 'ftdh' AS instance FROM zadmin_mdlftdhproduccion.mdl_course");
