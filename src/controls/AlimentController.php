<?php

namespace boissons\controls;

use \boissons\models\Cocktail;
use \boissons\models\Aliment;
use boissons\views\CocktailsView;
use \boissons\views\View;

class AlimentController
{
    function getAliment($rq, $rs, $args)
    {
        $id = $args['id'];
        $aliments = Aliment::where('id', '=', $id)->with('superCategories', 'sousCategories', 'cocktails')->first();
        $elem = Aliment::where('id', '=', $id)->first()->pathToRoot();
        $content = <<<HTML
        <h1>$aliments->name</h1>
        <h3>
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb"><ol class="breadcrumb">
HTML;
        for ($i = 0; $i < $elem->count() - 1; $i++) {
            $id = $elem[$i]->id;
            $name = $elem[$i]->name;
            $content .= "<li class='breadcrumb-item'><a href='/aliment/$id'>$name</a> </li>";
        }
        $content .= <<<HTML
        <li class='breadcrumb-item aria-current='page' active'><a>{$elem[$elem->count() - 1]->name}</a> </li> </ol></nav> </h3>
        <div class='container'>
        <h5>Super categories:</h5>
        <div class='col-12 col-md-2'> <div class='list-group'>
HTML;
        foreach ($aliments->superCategories as $categorie) {
            $content .= "<a class='list-group-item list-group-item-action' href='/aliment/$categorie->id'>$categorie->name</a>";
        }
        $content .= "</div></div><br>";
        if ($aliments->sousCategories->count() > 0) {
            $content .= <<<HTML
        <h5>Sous categories:</h5>
        <div class='col-12 col-md-2'> <div class='list-group'>
HTML;
            foreach ($aliments->sousCategories as $categorie) {
                $content .= "<a class='list-group-item list-group-item-action' href='/aliment/$categorie->id'>$categorie->name</a> ";
            }
            $content .= "</div></div><br>";
        }
        $items = ' ';
        foreach ($aliments->cocktails as $cocktail) {
            $img = CocktailsView::getImageFileName($cocktail->name);
            $items .= <<<HTML
            <div class="col-md-4 col-sm-6">
                <div class="card" style="width: 20rem;">
                    <img src="/Photos/{$img}" class="img-fluid rounded">
                    <div class="card-body">
                        <h5 class="card-title">{$cocktail->name}</h5>
                        <div class="card-text">
                            <a href="/cocktail/{$cocktail->id}" class="btn btn-secondary">Voir le cocktail</a>
                        </div>
                    </div>
                </div>
            </div>
            HTML;
        }
        $titre = $aliments->cocktails->count() > 1 ? "Cocktails utilisant cet aliment" : "Aucun cocktail n'utilise cet aliment";
        $content .= <<<HTML
        <div class="container">
        <h4 class="mb-4">{$titre}</h4>
        <div class="container-fluid" id="container-scroll">
        <div class="overflow-auto row flex-row flex-nowrap mt-4 pb-4 pt-2">
            $items
        </div>
    </div>
</div>
HTML;
        $view = new View($content, $aliments->name, $rq);
        return $view->getHtml();
    }
}
