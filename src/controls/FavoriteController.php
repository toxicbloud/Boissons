<?php

namespace boissons\controls;

use \boissons\models\Panier;
use \boissons\models\Cocktail;
use boissons\views\CocktailView;
use boissons\views\FavoriteView;
use \boissons\views\View;

class FavoriteController
{
    function addFavorite($rq, $rs, $args)
    {
        $id = $args['id'];
        // test if user is logged in
        if (Authentication::isConnected()) {
            // add fav in database
            try {
                $panier = new Panier();
                $panier->id_user = Authentication::getProfile()->id;
                $panier->id_cocktail = $id;
                $panier->save();
            } catch (\Exception $e) {
                // return 404 not found
                return $rs->withStatus(404);
            }
        } else {
            // add fav in session only if not already in
            if (!isset($_SESSION['favorites'])) {
                $_SESSION['favorites'] = [];
            }
            if (!in_array($id, $_SESSION['favorites'])) {
                // attention on doit caster en int pour éviter de faire un code pour les entier et un autre pour les string en JS
                $_SESSION['favorites'][] = (int) $id;
            }
        }

        // send number of favs
        $nbFavs = 0;
        if (Authentication::isConnected()) {
            $nbFavs = Panier::where('id_user', '=', Authentication::getProfile()->id)->count();
        } else {
            if (isset($_SESSION['favorites'])) {
                $nbFavs = count($_SESSION['favorites']);
            }
        }
        return $rs->withJson($nbFavs);
    }
    function getFavorites($rq, $rs, $args)
    {
        $cocktails = null;
        if (Authentication::isConnected()) {
            $panier = Panier::where('id_user', '=', Authentication::getProfile()->id)->with('cocktail')->get();
            $cocktails = $panier->map(function ($item, $key) {
                return $item->cocktail;
            });
        } else {
            if (isset($_SESSION['favorites'])) {
                $cocktails = Cocktail::whereIn('id', $_SESSION['favorites'])->get();
            }
        }
        $view = new FavoriteView($rq, $cocktails);
        return $view->render();
    }
    function getFavoritesCount($rq, $rs, $args)
    {
        // return number of favs
        $nbFavs = 0;
        if (Authentication::isConnected()) {
            $nbFavs = Panier::where('id_user', '=', Authentication::getProfile()->id)->count();
        } else {
            if (isset($_SESSION['favorites'])) {
                $nbFavs = count($_SESSION['favorites']);
            }
        }
        return $rs->withJson($nbFavs);
    }
    function getFavoritesList($rq, $rs, $args)
    {
        $list = [];
        if (Authentication::isConnected()) {
            $panier = Panier::where('id_user', '=', Authentication::getProfile()->id)->with('cocktail')->get();
            foreach ($panier as $cocktail) {
                $list[] = $cocktail->id_cocktail;
            }
        } else {
            if (isset($_SESSION['favorites'])) {
                $list = $_SESSION['favorites'];
            }
        }
        return $rs->withJson($list);
    }
    function deleteFavorite($rq, $rs, $args)
    {
        $id = $args['id'];
        if (Authentication::isConnected()) {
            Panier::where('id_user', '=', Authentication::getProfile()->id)->where('id_cocktail', '=', $id)->delete();
        } else {
            if (isset($_SESSION['favorites'])) {
                $key = array_search($id, $_SESSION['favorites']);
                if ($key !== false) {
                    unset($_SESSION['favorites'][$key]);
                }
            }
        }
        // return number of favs
        $nbFavs = 0;
        if (Authentication::isConnected()) {
            $nbFavs = Panier::where('id_user', '=', Authentication::getProfile()->id)->count();
        } else {
            if (isset($_SESSION['favorites'])) {
                $nbFavs = count($_SESSION['favorites']);
            }
        }
        return $rs->withJson($nbFavs);
    }
    function deleteAllFavorites($rq, $rs, $args)
    {
        if (Authentication::isConnected()) {
            Panier::where('id_user', '=', Authentication::getProfile()->id)->delete();
        } else {
            if (isset($_SESSION['favorites'])) {
                unset($_SESSION['favorites']);
            }
        }
        // return number of favs
        $nbFavs = 0;
        if (Authentication::isConnected()) {
            $nbFavs = Panier::where('id_user', '=', Authentication::getProfile()->id)->count();
        } else {
            if (isset($_SESSION['favorites'])) {
                $nbFavs = count($_SESSION['favorites']);
            }
        }
        return $rs->withJson($nbFavs);
    }
}
