<?php

namespace boissons\controls;

use boissons\models\User;
use boissons\exceptions\WrongPasswordException;
use Slim\Http\Request;

class Authentication
{

    /**
     * méthode qui permet de créer un nouvel utilisateur et le sauvegarde dans la bdd
     * @param pseudo nom de l'utilisateur
     * @param password mot de passe pas encore hashé
     */
    static function createUser(Request $rq)
    {
        $body = $rq->getParsedBody();

        $hash = password_hash($body['password'], PASSWORD_DEFAULT);
        $u = new User();
        $u->username = $body['pseudo'];
        $u->password = $hash;
        $u->save();
        Authentication::loadProfile($u); // on charge le profil de l'utilisateur
    }

    /**
     * méthode qui vérifie si le mot de passe est correct
     * @param u utilisateur concerné
     * @param p mot de passe à tester
     * @return true si l'authentification a réussi
     */
    static function authenticate($u, $p)
    {
        $hash = $u->password;
        if (password_verify($p, $hash)) {
            Authentication::loadProfile($u);
            return true;
        } else {
            throw new WrongPasswordException('Le mot de passe ne correspond pas.');
            return false;
        }
    }

    /**
     * méthode qui charge le profil de l'utilisateur dans une variable de session
     * @param u objet user correspondant à l'utilisateur
     */
    static function loadProfile($u)
    {
        $_SESSION['user'] = $u;
    }

    /**
     * méthode qui libère la variable de session user
     */
    static function freeProfile()
    {
        unset($_SESSION['user']);
    }


    static function isConnected()
    {
        if (isset($_SESSION['user'])) return true;
        else return false;
    }
    static function getProfile()
    {
        if (Authentication::isConnected()) return $_SESSION['user'];
        else return null;
    }
}
