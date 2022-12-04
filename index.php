<?php
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING | E_DEPRECATED));
require_once('vendor/autoload.php');
require_once('conf/db.php');
include('Donnees.inc.php');
session_start();

use boissons\controls\CocktailController;
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
use boissons\views\SearchView;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Support\Collection;

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
$app->get('/aliment/{id}',function ($rq, $rs, $args) {
    $id = $args['id'];
    $aliments = Aliment::where('id', '=',$id)->with('superCategories','sousCategories','cocktails')->first();
    $content = "<h1>$aliments->name</h1>";
    $elements = Aliment::where('id', '=',$id)->first()->getTopPath();
    $test = Aliment::where('id', '=',$id)->first()->pathToRoot();
    $content .= "<h3>";
    $content .= <<<HTML
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb"><ol class="breadcrumb">
HTML;
    for ($i=0; $i < $test->count()-1 ; $i++) {
        $id = $test[$i]->id;
        $name = $test[$i]->name;
        $content .= "<li class='breadcrumb-item'><a href='/aliment/$id'>$name</a> </li>";
    }
    $id = $test[$test->count()-1]->id;
    $name = $test[$test->count()-1]->name;
    $content .= "<li class='breadcrumb-item aria-current='page' active'><a>$name</a> </li> ";
    $content .= "</ol></nav> </h3>";
    $content .= "Super categories:<br>";
    $content .= "<ul>";
    foreach ($aliments->superCategories as $categorie) {
        $content .= "<li>" . "<a href='/aliment/$categorie->id'>$categorie->name</a> ". "</li>";
    }
    $content .= "</ul>";
    $content .= "<br>";
    if($aliments->sousCategories->count() > 0){
        $content .= "Sous categories:<br>";
        $content .= "<ul>";
        foreach ($aliments->sousCategories as $categorie) {
            $content .= "<li>" . "<a href='/aliment/$categorie->id'>$categorie->name</a> ". "</li>";
        }
        $content .= "</ul>";
        $content .= "<br>";
    }
    $content .= "Cocktails utilisant cet aliment :<br>";
    $content .= "<ul>";
    foreach ($aliments->cocktails as $cocktail) {
        $content .= "<li> <a href='/cocktail/$cocktail->id'>$cocktail->name</a> </li>";
    }
    $content .= "</ul>";
    // DB::connection()->enableQueryLog();
    // $cocktails = Aliment::getCocktails($aliments);
    // foreach ($cocktails as $cocktail) {
    //     $content .= "<li>" . $cocktail->name . "</li>";
    // }
    // $i=0;
    // foreach( DB::getQueryLog() as $q){
    //     $i++;
    // };
    // echo $i;
    
    $view = new View($content, $aliments->name, $rq);
    return $view->getHtml();
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
