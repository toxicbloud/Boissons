<?php 

namespace boissons\views;

class SearchView
{
    public function __construct($rq, $error = NULL)
    {
        $this->rq = $rq;
        $this->error = $error;
    }
    public function render()
    {
        $content = $this->html();
        $vue = new View($content, 'Recherche', $this->rq);
        $vue->addCSSSheet('search.css');
        $vue->addJSScript('search.js');
        return $vue->getHtml();
    }
    public function html()
    {
        $path = $this->rq->getUri()->getBasePath();
        $temp = <<<END
        <div class="text-center" style="padding-top:10px;">
        <form id="form" autocomplete="off" action="" style="margin-bottom: 10px;">
            <div class="autocomplete" style="width:300px;">
                <input id="Myingredients" type="text" name="Myingredients" placeholder="Ingrédients">
            </div>
            <input type="submit" id="searchButton">
        </form>
        <div id="listIngredients">
        </div>
        <div id="loading" class="spinner-border" role="status" style="display:none;">
            <span class="sr-only">Loading...</span>
        </div>
        <h2 id="result" style="display:none;">Résultats</h2>
        <div id="listCocktails" style="margin-top:10px;">
        </div>
        </div>
END;
        return $temp;
    }
}