<?php

namespace boissons\views;

class CocktailsView
{
    public function __construct($rq,$cocktails)
    {
        $this->rq = $rq;
        $this->cocktails = $cocktails;
    }
    public function render()
    {
        $content = $this->html();
        $vue = new View($content, 'Cocktails', $this->rq);
        $vue->addJSScript('cocktails.js');
        return $vue->getHtml();
    }
    private function getImageFileName($name){
        $normalizeChars = array(
            'Š'=>'S', 'š'=>'s', 'Ð'=>'Dj','Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A',
            'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I',
            'Ï'=>'I', 'Ñ'=>'N', 'Ń'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U',
            'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss','à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a',
            'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i',
            'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ń'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u',
            'ú'=>'u', 'û'=>'u', 'ü'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y', 'ƒ'=>'f',
            'ă'=>'a', 'î'=>'i', 'â'=>'a', 'ș'=>'s', 'ț'=>'t', 'Ă'=>'A', 'Î'=>'I', 'Â'=>'A', 'Ș'=>'S', 'Ț'=>'T',
        );
        $imageName = ucfirst(strtr($name,$normalizeChars)) . ".jpg";
        // replace space with underscore
        $imageName = str_replace(' ', '_', $imageName);
        if(file_exists("Photos/" . $imageName)){
            return $imageName;
        }else{
            return "default.png";
        }
    }
    private function getItemHtml(\boissons\models\Cocktail $cocktail){
        $imageFileName = $this->rq->getUri()->getBasePath()."/Photos/".$this->getImageFileName($cocktail->name);
        $html = <<<END
        <li class="list-group-item">
            <!-- Custom content-->
            <div class="media align-items-lg-center flex-column flex-lg-row p-3">
            <div class="media-body order-2 order-lg-1">
            <h5 class="mt-0 font-weight-bold mb-2">$cocktail->name</h5>
            <p class="font-italic text-muted mb-0 small">$cocktail->preparation</p>
            </div>
            <div class="row">
            <img src="$imageFileName" alt="Generic placeholder image" class="ml-lg-5 mt-2 order-1 order-lg-2 col-md-4 img-fluid img-thumbnail">
            <div class="mt-5 col-md-8">
            <ul class="list-group list-group">
END;
        foreach(explode('|',$cocktail->ingredients) as $ingredient){
            $html .= "<li class=\"list-group-item\">$ingredient</li>";
        }
        $html .= <<<END
                </ul>
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-between mt-1">
                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="location.href='/cocktail/$cocktail->id';">Voir</button>
                <i class="bi bi-heart justify-content-end" data-idCocktail="$cocktail->id"></i>
            </div> <!-- End -->
        </li>
END;
        return $html;
    }
    public function html()
    {
        $path = $this->rq->getUri()->getBasePath();
        $temp = <<<END
        <div class="container py-5">
    <div class="row text-center text-white mb-5">
        <div class="col-lg-7 mx-auto">
            <h1 class="display-4 text-secondary">Cocktails</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <!-- List group-->
            <ul class="list-group shadow">

END;
        foreach ($this->cocktails as $cocktail) {
            $temp .= $this->getItemHtml($cocktail);
        }
        $temp .= <<<END
            </ul> <!-- End -->
        </div>
    </div>
</div>
END;
        return $temp;
    }
}
