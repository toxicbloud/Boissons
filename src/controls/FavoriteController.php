<?php

namespace boissons\controls;
use \boissons\models\Panier;

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
}
