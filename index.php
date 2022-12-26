<?php
// error_reporting(E_ALL ^ (E_NOTICE | E_WARNING | E_DEPRECATED));
require_once('vendor/autoload.php');
require_once('conf/db.php');
include('Donnees.inc.php');
session_start();

use boissons\controls\AlimentController;
use boissons\controls\CocktailController;
use boissons\controls\AuthController;
use boissons\controls\Authentication;
use boissons\controls\FavoriteController;
use boissons\exceptions\WrongPasswordException;
use boissons\models\Aliment;
use boissons\models\Cocktail;
use boissons\views\View;
use \Slim\App;
use \Slim\Container;
use Slim\Http\Request;
use boissons\models\User;
use boissons\views\LoginView;
use boissons\views\SearchView;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Support\Collection;
use boissons\models\Panier;

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
        // $html = new View($content, 'Boissons', $rq);
        $vue = new SearchView($rq);
        return $vue->render();
    }
);

$app->get('/cocktail/{id}', CocktailController::class . ':getCocktail');
$app->get('/cocktails', CocktailController::class . ':getCocktails');
$app->get('/aliments', CocktailController::class . ':getAliments');
$app->post('/cocktails', CocktailController::class . ':getSearch');
$app->get('/aliment/{id}',AlimentController::class . ':getAliment');
$app->get('/login', function ($rq, $rs, $args) {
    return AuthController::showLoginForm($rq, $rs, $args);
});
$app->get('/logout', AuthController::class . ':logout');
$app->get('/edit', function ($rq, $rs, $args) {
    return AuthController::showEditForm($rq, $rs, $args);
});
$app->post('/edit', AuthController::class . ':edit');
$app->get('/register', function ($rq, $rs, $args) {
    return AuthController::showRegisterForm($rq, $rs, $args);
});
$app->post('/register', AuthController::class . ':register');
$app->post('/login', AuthController::class . ':login');
$app->post('/favorite/{id}', FavoriteController::class . ':addFavorite');
$app->delete('/favorite/{id}', FavoriteController::class . ':deleteFavorite');
$app->delete('/favorite', FavoriteController::class . ':deleteAllFavorites');
$app->get('/favorite',FavoriteController::class . ':getFavorites');
$app->get('/favorite/count',FavoriteController::class . ':getFavoritesCount');
$app->get('/favorite/list',FavoriteController::class . ':getFavoritesList');
$app->run();
