<?php
require_once('vendor/autoload.php');
use Illuminate\Database\Capsule\Manager as DB;

$db = new DB();
$tab = parse_ini_file('db.config.ini');
$db->addConnection([
    'driver'    => $tab['driver'],
    'host'      => $tab['host'],
    'database'  => $tab['database'],
    'username'  => $tab['username'],
    'password'  => $tab['password'],
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);
$db->setAsglobal();
$db->bootEloquent();