<?php

namespace boissons\controls;

use \boissons\views\CocktailsView;
use \boissons\views\CocktailView;
use \boissons\models\Cocktail;
use \boissons\models\Aliment;

class CocktailController
{

    function getCocktail($rq, $rs, $args)
    {
        $id = $args['id'];
        $cocktail = Cocktail::where('id', $id)->with('composition')->first();
        $view = new CocktailView($rq, $cocktail);
        return $view->render();
    }
    function getCocktails($rq, $rs, $args)
    {
        $cocktails = Cocktail::all();
        $view = new CocktailsView($rq, $cocktails);
        return $view->render();
    }
    function getAliments($rq, $rs, $args)
    {
        // return all aliments in json
        $aliments = Aliment::all();
        return $rs->withJson($aliments);
    }
    function getSearch($rq, $rs, $args)
    {
        // get array of ingredients
        $ingredients = $rq->getParsedBodyParam('ingredients');
        // get array of cocktails
        $ingredients = Aliment::whereIn('id', $ingredients)->with('sousCategories', 'cocktails')->get();
        $cocktails = $ingredients->flatMap(function ($aliment) {
            return Aliment::getCocktails($aliment);
        })->unique();
        // select only name and id into unique collection
        $cocktails = $cocktails->map(function ($cocktail) {
            return ['id' => $cocktail->id, 'name' => $cocktail->name];
        });
        // make collection unique
        $cocktails = $cocktails->unique();
        return $rs->withJson(array_values($cocktails->toArray()));
    }
}
