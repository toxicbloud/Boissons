<?php
namespace boissons\views;
class CocktailView{
    public function __construct($rq,$cocktail)
    {
        $this->rq = $rq;
        $this->cocktail = $cocktail;
    }
    public function render()
    {
        $content = $this->html();
        $vue = new View($content, 'Cocktail', $this->rq);
        return $vue->getHtml();
    }
    public function html()
    {
        $nom = $this->cocktail->name;
        $path = $this->rq->getUri()->getBasePath();
        $temp = <<<END
        $nom
        END;
        foreach($this->cocktail->composition as $ingredient){
            $temp .= <<<END
            <div class="card" style="width: 18rem;">
            <div class="card-body">
                <h5 class="card-title">$ingredient->name</h5>
                <a href="$path/aliment/$ingredient->id" class="btn btn-secondary">Voir l'ingrédient</a>
            </div>
            </div>
            END;
        }
        return $temp;
    }
}