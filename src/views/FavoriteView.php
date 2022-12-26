<?php

namespace boissons\views;

class FavoriteView
{
    public function __construct($rq, $cocktails)
    {
        $this->rq = $rq;
        $this->cocktails = $cocktails;
    }
    public function render()
    {
        $content = $this->html();
        $vue = new View($content, 'Favoris', $this->rq);
        return $vue->getHtml();
    }
    public function html()
    {
        $path = $this->rq->getUri()->getBasePath();
        $temp = <<<END
        <div class="text-center" style="padding-top:10px;">
        <h1>Mes favoris</h1>
        <div id="listCocktails" style="margin-top:10px;" class="d-flex flex-column align-items-center">
        END;
        foreach ($this->cocktails as $cocktail) {
            $temp .= <<<END
            <div class="card mt-2" style="width: 18rem;">
            <div class="card-body">
                <h5 class="card-title">$cocktail->name</h5>
                <a href="$path/cocktail/$cocktail->id" class="btn btn-secondary">Voir le cocktail</a>
            </div>
            </div>
            END;
        }
        $temp .= <<<END
        </div>
        </div>
        END;
        return $temp;
    }
}
