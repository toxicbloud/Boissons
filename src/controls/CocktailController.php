<?php
namespace boissons\controls;
use \boissons\views\CocktailsView;
use \boissons\views\CocktailView;
use \boissons\models\Cocktail;

class CocktailController {

    function getCocktail($rq, $rs, $args){
        $id = $args['id'];
        $cocktail = Cocktail::where('id', $id)->with('composition')->first();
        $view = new CocktailView($rq, $cocktail);
        return $view->render();
    }
    function getCocktails($rq, $rs, $args)
    {
        $cocktails = Cocktail::all();
        $view = new CocktailsView($rq,$cocktails);
        return $view->render();
    }
}