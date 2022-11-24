<?php
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING | E_DEPRECATED));
require_once('vendor/autoload.php');
require_once('conf/db.php');
include('Donnees.inc.php');
session_start();

use boissons\controls\AuthController;
use boissons\controls\Authentication;
use boissons\exceptions\WrongPasswordException;
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
$app->get('/login', function ($rq, $rs, $args) {
    return AuthController::showLoginForm($rq, $rs, $args);
});
$app->get('/logout', AuthController::class . ':logout');
$app->get('/register', function ($rq, $rs, $args) {
    $content = <<<END
    <form action="register" method="post">
        <div class="form-group
        <label for="pseudo">Pseudo</label>
        <input type="text" class="form-control" id="pseudo" name="pseudo" placeholder="Pseudo">
        </div>
        <div class="form-group">
        <label for="password">Mot de passe</label>
        <input type="password" class="form-control" id="password" name="password" placeholder="Mot de passe">
        </div>
        <button type="submit" class="btn btn-primary">Inscription</button>
    </form>
END;
    return $content;
});
$app->post('/register', AuthController::class . ':register');
$app->post('/login', AuthController::class . ':login');
$app->run();
