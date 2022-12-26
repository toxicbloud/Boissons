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
        $path = $this->rq->getUri()->getBasePath();
        $img = CocktailsView::getImageFileName($this->cocktail->name);
        // genere une page html avec le nom du cocktail les ingredients la recette et l'image en utilisant bootstrap 5
        $temp = <<<END
        <div class="container mt-5">
    <div class="row">
      <div class="col-md-6">
        <h3>{$this->cocktail->name}</h3>
        <h6>Préparation : </h6>
            {$this->getPreparationHTML()}
        </div>
      <div class="col-md-6">
        <img src="/Photos/{$img}" alt="description de l'image" class="img-fluid img-thumbnail">
      </div>
    </div>
    <h2 class="mt-5">Ingrédients</h2>
    <ul class="list-group mb-2">
        {$this->getIngredientsHTML()}
    </ul>
  </div>
END;

        return $temp;
    }
    private function getIngredientsHTML(){
        $temp = "";
        // split ingredients by | 
        $ingredients = explode("|",$this->cocktail->ingredients);
        foreach($ingredients as $ingredient){
            $temp .= <<<END
            <a class='list-group-item' >$ingredient</a>
            END;
        }
        return $temp;
    }
    private function getPreparationHTML(){
        $temp = "";
        // split by .
        $preparation = explode(".",$this->cocktail->preparation);
        foreach($preparation as $step){
            $temp .= <<<END
            $step <br>
            END;
        }
        return $temp;
    }
}