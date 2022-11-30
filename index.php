<?php
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING | E_DEPRECATED));
require_once('vendor/autoload.php');
require_once('conf/db.php');
include('Donnees.inc.php');
session_start();

use boissons\controls\AuthController;
use boissons\controls\Authentication;
use boissons\exceptions\WrongPasswordException;
use boissons\models\Aliment;
use boissons\models\Cocktail;
use boissons\views\View;
use \Slim\App;
use \Slim\Container;
use Slim\Http\Request;
use boissons\models\User;
use boissons\views\LoginView;

$configuration = [
    'settings' => [
        'displayErrorDetails' => true,
    ],
    'imgPath' => 'Photos/',
];
$c = new Container($configuration);
$app = new App($c);
$app->get(
    '/',
    function ($rq, $rs, $args) {
        $content = "";
        $html = new View($content, 'Boissons', $rq);
        return $html->getHtml();
    }
);


$app->get('/cocktails', function () {
    $content = "";
    $normalizeChars = array(
        'Š'=>'S', 'š'=>'s', 'Ð'=>'Dj','Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A',
        'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I',
        'Ï'=>'I', 'Ñ'=>'N', 'Ń'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U',
        'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss','à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a',
        'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i',
        'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ń'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u',
        'ú'=>'u', 'û'=>'u', 'ü'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y', 'ƒ'=>'f',
        'ă'=>'a', 'î'=>'i', 'â'=>'a', 'ș'=>'s', 'ț'=>'t', 'Ă'=>'A', 'Î'=>'I', 'Â'=>'A', 'Ș'=>'S', 'Ț'=>'T',
    );
    //eager load composition
    $cocktails = Cocktail::all()->load('composition');
    $content .= "<h1>Liste des cocktails</h1>";
    foreach ($cocktails as $cocktail) {
        $content .= "<h2>" . $cocktail->name . "</h2>";
        // first letter is uppercase

        $imageName = ucfirst(strtr($cocktail->name,$normalizeChars)) . ".jpg";
        // replace space with underscore
        $imageName = str_replace(' ', '_', $imageName);
        if (file_exists($GLOBALS['c']['imgPath'] . $imageName)) {
            $content .= "<img src='" . $GLOBALS['c']['imgPath'] .$imageName. "' alt='photo du cocktail " . $cocktail->name . "' width='200px' height='200px'>";
        }else{
            $content  .= "<img src='" . $GLOBALS['c']['imgPath'] . "default.png' alt='photo du cocktail " . $cocktail->name . "' width='200px' height='200px'>";
        }
        $content .= "<p>". $cocktail->preparation . "</p>";
        $ingredients = explode('|', $cocktail->ingredients);
        $content .= "<ul>";
        foreach ($ingredients as $ingredient) {
            $content .= "<li>" . $ingredient . "</li>";
        }
        $content .= "</ul>";
        $content .= "<ul>";
        $aliments = $cocktail->composition;
        foreach ($aliments as $aliment) {
            $content .= "<li>" . $aliment->name . "</li>";
        }
        $content .= "</ul>";
    }
    return $content;
});
$app->get('/aliment/{name}',function ($rq, $rs, $args) {
    $name = $args['name'];
    $content = "<h1>$name</h1>";
    $content .= "Super categories:<br>";
    $aliment = Aliment::where('name', 'like', $name)->first()->superCategories;
    foreach ($aliment as $categorie) {
        $content .= "<li>" . $categorie->name . "</li>";
    }
    $content .= "<br>";
    $content .= "Sous categories:<br>";
    $aliment = Aliment::where('name', 'like', $name)->first()->sousCategories;
    foreach ($aliment as $categorie) {
        $content .= "<li>" . $categorie->name . "</li>";
    }
    return $content;
});
$app->get('/login', function ($rq, $rs, $args) {
    return AuthController::showLoginForm($rq, $rs, $args);
});
$app->get('/logout', AuthController::class . ':logout');
$app->get('/register', function ($rq, $rs, $args) {
    return AuthController::showRegisterForm($rq, $rs, $args);
});
$app->post('/register', AuthController::class . ':register');
$app->post('/login', AuthController::class . ':login');
$app->run();
