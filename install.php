<?php
include('Donnees.inc.php');
$tab = parse_ini_file('conf/db.config.ini');
$pdo = null;
try {
    $pdo = new PDO("mysql:host=" . $tab['host'] . ";port=" . $tab['port'] . ";charset=utf8mb4", $tab['username'], $tab['password']);
} catch (Exception $e) {
    echo $e->getMessage();
    print_r($e);
    error_log($e->getMessage());
    exit('Error connecting to database'); //Should be a message a typical user could understand
}
echo "<ul>";
echo '<li>PDO connection OK</li>';
echo "<br>";
echo '<li>Creating database...</li>';
$sql = "DROP DATABASE IF EXISTS " . $tab['database'];
$pdo->exec($sql);
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

echo $pdo->query($sql) ? '<li>Table user crée avec succès</li>' : 'USER KO';

// on créé des utilisateurs
$sql = "INSERT INTO `user` (`username`, `password`, `firstname`, `lastname`, `email`) VALUES ('toxicbloud', '\$2y\$10\$VO9QS0hZEIE9gGn384DUROtXXMKmdtInYYAUS3hmaxMhvfQSpRGHy', 'Antonin', 'Rousseau', 'trucen@truc.fr') ";
echo $pdo->query($sql) ? '<li>Utilisateur créé avec succès</li>' : 'USER KO';
$sql = "DROP TABLE IF EXISTS cocktail";
echo $pdo->query($sql) ? '<li>RESET table cocktail</li>' : 'COCKTAIL KO';
$sql = "CREATE TABLE IF NOT EXISTS cocktail (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(300) NOT NULL,
    ingredients VARCHAR(2000) NOT NULL,
    preparation VARCHAR(2000) NOT NULL
    );";
echo $pdo->query($sql) ? '<li>Table cocktail crée avec succès</li>' : 'COCKTAIL KO';
$sql = "DROP TABLE IF EXISTS Aliment";
echo $pdo->query($sql) ? '<li>RESET table aliment</li>' : 'ALIMENT KO';
$sql = "CREATE TABLE IF NOT EXISTS aliment (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(60) NOT NULL UNIQUE
    );";
echo $pdo->query($sql) ? '<li>Table aliment crée avec succès</li>' : 'ALIMENT KO';
$sql = "DROP TABLE IF EXISTS categorie";
echo $pdo->query($sql) ? '<li>RESET table categorie</li>' : 'SUPER CATEGORIE KO';
$sql = "CREATE TABLE IF NOT EXISTS categorie (
    id INT(6) UNSIGNED ,
    idPere INT(6) UNSIGNED ,
    primary key (id, idPere),
    FOREIGN KEY (id) REFERENCES aliment(id),
    FOREIGN KEY (idPere) REFERENCES aliment(id)
    );";
echo $pdo->query($sql) ? '<li>Table categorie crée avec succès</li>' : 'SUPER CATEGORIE KO';
$sql = "DROP TABLE IF EXISTS compositionPanier";
echo $pdo->query($sql) ? '<li>RESET table compositionPanier </li>' : 'PANIER KO';
$sql = "CREATE TABLE IF NOT EXISTS compositionPanier (
    id_user INT(6) UNSIGNED NOT NULL,
    id_cocktail INT(6) UNSIGNED NOT NULL,
    dateAjout DATE,
    PRIMARY KEY (id_user, id_aliment),
    FOREIGN KEY (id_user) REFERENCES user(id),
    FOREIGN KEY (id_aliment) REFERENCES aliment(id)
    );";
echo $pdo->query($sql) ? '<li>Table compositionPanier crée avec succès</li>' : 'PANIER KO';
$sql = "DROP TABLE IF EXISTS composition";
echo $pdo->query($sql) ? '<li>RESET table composition </li>' : 'COCKTAIL KO';
$sql = "CREATE TABLE IF NOT EXISTS composition (
    id_cocktail INT(6) UNSIGNED NOT NULL,
    id_aliment INT(6) UNSIGNED NOT NULL,
    PRIMARY KEY (id_cocktail, id_aliment),
    FOREIGN KEY (id_cocktail) REFERENCES cocktail(id),
    FOREIGN KEY (id_aliment) REFERENCES aliment(id)
    );";
echo $pdo->query($sql) ? '<li>Table composition crée avec succès</li>' : 'COCKTAIL KO';
echo "</ul>";


function existInDB($pdo, $name)
{
    $prepare = $pdo->prepare("SELECT * FROM aliment WHERE name = :name");
    $prepare->bindValue(':name', $name);
    $prepare->execute();
    return $prepare->rowCount() > 0;
}
function getIdInDB($pdo, $name)
{
    $prepare = $pdo->prepare("SELECT id FROM aliment WHERE name = :name");
    $prepare->bindValue(':name', $name);
    $prepare->execute();
    return $prepare->fetch()['id'];
}

foreach($Hierarchie as $key => $value){
    $prepare = $pdo->prepare("INSERT INTO aliment (name) VALUES (:name)");
    $prepare->bindValue(':name', $key);
    $prepare->execute();
}
foreach($Hierarchie as $key => $value){
    if(isset($value['super-categorie'])){
        foreach($value['super-categorie'] as $superCategorie){
            $prepare = $pdo->prepare("INSERT INTO categorie (id, idPere) VALUES (:id, :idPere)");
            $prepare->bindValue(':id', getIdInDB($pdo, $key));
            $prepare->bindValue(':idPere', getIdInDB($pdo, $superCategorie));
            $prepare->execute();
        }
    }
}

foreach ($Recettes as $value) {
    $prepare = $pdo->prepare("INSERT INTO cocktail (name, ingredients, preparation) VALUES (:name, :ingredients, :preparation)");
    $prepare->bindValue(':name', $value['titre']);
    $prepare->bindValue(':ingredients', $value['ingredients']);
    $prepare->bindValue(':preparation', $value['preparation']);
    echo $prepare->execute() ? '<li>Recette ' . $value['titre'] . ' ajoutée avec succès</li>' : 'Recette KO';
    $idCocktail = $pdo->lastInsertId();
    foreach($value['index'] as $index){
        try {
        $prepare = $pdo->prepare("INSERT INTO composition (id_cocktail, id_aliment) VALUES (:id_cocktail, :id_aliment)");
        $prepare->bindValue(':id_cocktail', $idCocktail);
        $prepare->bindValue(':id_aliment', getIdInDB($pdo, $index));
        $prepare->execute();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}