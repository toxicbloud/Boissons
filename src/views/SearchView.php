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
        <div class="text-center">
        <form autocomplete="off" action="" style="margin-bottom: 10px;">
            <div class="autocomplete" style="width:300px;">
                <input id="Myingredients" type="text" name="Myingredients" placeholder="Ingrédients">
            </div>
            <input type="submit" id="searchButton">
        </form>
        <div id="listIngredients">
        </div>
        </div>
END;
        return $temp;
    }
}