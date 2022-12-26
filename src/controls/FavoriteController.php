<?php

namespace boissons\controls;

use \boissons\models\Panier;
use \boissons\models\Cocktail;
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
                $_SESSION['favorites'][] = $id;
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
        $content = "";
        if (Authentication::isConnected()) {
            $panier = Panier::where('id_user', '=', Authentication::getProfile()->id)->with('cocktail')->get();
            $content .= "<h1>Vos favoris</h1>";
            $content .= "<ul>";
            foreach ($panier as $cocktail) {
                $content .= "<li>" . "<a href='/cocktail/{$cocktail->cocktail_id}'>{$cocktail->cocktail->name}</a> " . "</li>";
            }
            $content .= "</ul>";
        } else {
            $content .= "<h1>Vos favoris</h1>";
            $content .= "<ul>";
            if (isset($_SESSION['favorites'])) {
                foreach ($_SESSION['favorites'] as $id) {
                    $cocktail = Cocktail::where('id', '=', $id)->first();
                    $content .= "<li>" . "<a href='/cocktail/$cocktail->id'>$cocktail->name</a> " . "</li>";
                }
            }
            $content .= "</ul>";
        }
        $view = new View($content, "Vos favoris", $rq);
        return $view->getHtml();
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
