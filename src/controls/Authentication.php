<?php

namespace boissons\controls;

use boissons\models\User;
use boissons\exceptions\WrongPasswordException;
use Slim\Http\Request;
use Illuminate\Database\QueryException;

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
        $u->firstname = $body['firstname'];
        $u->lastname = $body['lastname'];
        $u->sex = $body['gender'];
        $u->email = $body['email'];
        $u->dateofbirth = $body['dateofbirth'];
        $u->address = $body['address'];
        $u->ZIPcode = $body['zipcode'];
        $u->city = $body['city'];
        $u->phone = $body['phone'];
        $u->password = $hash;
        try {
            $u->save();
        } catch (QueryException $th) {
            throw new \Exception('Le nom d\'utilisateur est déjà utilisé.');
        }
        Authentication::loadProfile($u); // on charge le profil de l'utilisateur
    }
    static function editUser(Request $rq){
        $user = Authentication::getProfile();
        $body = $rq->getParsedBody();
        if(isset($body['password']) && $body['password'] != ''){
            $hash = password_hash($body['password'], PASSWORD_DEFAULT);
            $user->password = $hash;
        }
        $user->username = $body['pseudo'];
        $user->firstname = $body['firstname'];
        $user->lastname = $body['lastname'];
        $user->sex = $body['gender'];
        $user->email = $body['email'];
        $user->dateofbirth = $body['dateofbirth'];
        $user->address = $body['address'];
        $user->ZIPcode = $body['zipcode'];
        $user->city = $body['city'];
        $user->phone = $body['phone'];
        try {
            $user->save();
        } catch (QueryException $th) {
            throw new \Exception('Le nom d\'utilisateur est déjà utilisé.');
        }
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
