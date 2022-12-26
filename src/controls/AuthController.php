<?php

namespace boissons\controls;

use boissons\models\User;
use boissons\exceptions\WrongPasswordException;
use boissons\views\LoginView;
use boissons\views\RegisterView;
use boissons\views\ProfileView;

class AuthController
{
    static function login($rq, $rs, $args)
    {
        $pseudo = $rq->getParsedBodyParam('pseudo');
        $u = User::where('username', $pseudo)->first();
        if ($u == null) {
            return $rs->withRedirect('/login?error=wrongusername');
        }
        $password = $rq->getParsedBodyParam('password');
        try {
            Authentication::authenticate($u, $password);
            return $rs->withRedirect('/');
        } catch (WrongPasswordException $th) {
            return $rs->withRedirect('/login?error=wrongpassword');
        }
    }
    static function register($rq, $rs, $args)
    {
        try{
            Authentication::createUser($rq);
        }catch(\Exception $e){
            return $rs->withjson(['success' => false, 'message' => $e->getMessage()]);
        }
        return $rs->withjson(['success' => true]);
    }
    static function edit($rq, $rs, $args)
    {
        try{
            Authentication::editUser($rq);
        }catch(\Exception $e){
            return $rs->withjson(['success' => false]);
        }
        return $rs->withjson(['success' => true]);
    }
    static function logout($rq, $rs, $args)
    {
        session_destroy();
        return $rs->withRedirect('/');
    }
    static function showLoginForm($rq,$rs,$args){
        $view = new LoginView($rq);
        return $view->render();
    }
    static function showRegisterForm($rq, $rs, $args){
        $view = new RegisterView($rq);
        return $view->render();
    }
    static function showEditForm($rq, $rs, $args){
        $user = Authentication::getProfile();
        $view = new ProfileView($rq, $user);
        return $view->render();
    }
}
