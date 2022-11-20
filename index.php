<?php
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
$app->get('/login', function (Request $rq, $rs, $args) {
    $content = <<<END
    <form action="login" method="post">
        <div class="form-group
        <label for="pseudo">Pseudo</label>
        <input type="text" class="form-control" id="pseudo" name="pseudo" placeholder="Pseudo">
        </div>
        <div class="form-group">
        <label for="password">Mot de passe</label>
        <input type="password" class="form-control" id="password" name="password" placeholder="Mot de passe">
        </div>
        <button type="submit" class="btn btn-primary">Connexion</button>
    </form>
END;
    return $content;
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
