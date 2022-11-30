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
