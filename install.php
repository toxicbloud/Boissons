<?php
include('Donnees.inc.php');
$tab = parse_ini_file('conf/db.config.ini');
$pdo = null;
try {
    $pdo = new PDO("mysql:host=".$tab['host'].";port=".$tab['port'].";charset=utf8mb4", $tab['username'], $tab['password']);
} catch (Exception $e) {
    echo $e->getMessage();
    print_r($e);
    error_log($e->getMessage());
    exit('Error connecting to database'); //Should be a message a typical user could understand
}
echo "<ul>";
echo '<li>PDO connection OK</li>';
echo "<br>";
$sql = "Create database if not exists " . $tab['database'];
echo $pdo->query($sql) ? '<li>Database created</li>' : '<li>Database not created</li>';
$sql = "use " . $tab['database'];
echo $pdo->query($sql) ? '<li>Database selected</li>' : '<li>Database not selected</li>';
$sql = "DROP TABLE IF EXISTS user";
echo $pdo->query($sql) ? '<li>RESET table user</li>' : 'USER KO';
$sql = "CREATE TABLE IF NOT EXISTS user (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(30) NOT NULL,
    password VARCHAR(255) NOT NULL,
    firstname VARCHAR(30) NULL,
    lastname VARCHAR(30) NULL,
    sex VARCHAR(1) NULL,
    email VARCHAR(300) NULL,
    dateofbirth DATE NULL,
    address VARCHAR(300) NULL,
    ZIPcode VARCHAR(5) NULL,
    city VARCHAR(100) NULL,
    phone VARCHAR(20) NULL
    );";

echo $pdo->query($sql) ? '<li>Table user créée avec succès</li>' : 'USER KO';
echo "</ul>";
foreach($Recettes as $value){
    $ing = $value['ingredients'];
    echo "<li>";
    echo $value['titre'];
    $res = explode("|", $ing);
    foreach($res as $str){
        echo "<ul>";
        echo $str;
        echo "</ul>";
    }
    echo "</li>";
}