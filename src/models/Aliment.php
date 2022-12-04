<?php

namespace boissons\models;
use Illuminate\Database\Capsule\Manager as DB;
class Aliment extends \Illuminate\Database\Eloquent\Model
{
    protected $table = "aliment";
    protected $primaryKey = "id";
    public $timestamps = false;

    public function sousCategories() {
        return $this->hasManyThrough('boissons\models\Aliment', 'boissons\models\Categorie', 'idPere', 'id', 'id', 'id');
    }
    public function superCategories() {
        return $this->hasManyThrough('boissons\models\Aliment', 'boissons\models\Categorie', 'id', 'id', 'id', 'idPere');
    }
    public function superCategorie(){
        return $this->hasOneThrough('boissons\models\Aliment', 'boissons\models\Categorie', 'id', 'id', 'id', 'idPere');
    }
    public function cocktails()
    {
        return $this->hasManyThrough('boissons\models\Cocktail', 'boissons\models\Composition', 'id_aliment', 'id', 'id', 'id_cocktail');
    }
    /**
     * retourne les cocktails de cet aliment ainsi que ceux de ses sous-catégories de maniere récursive
     * sans eager loading donc beaucoup de requetes complexite exponentielle
     */
    public static function getCocktails($aliment){
        return $aliment->cocktails->merge($aliment->sousCategories->flatMap(function ($aliment) {
            return Aliment::getCocktails($aliment);
        }));
    }
    public function getTopPath(){
        $path = $this->superCategories->map(function ($categorie) {
            return $categorie->getTopPath();
        });
        $path->push($this);
        return $path;
    }
    public function pathToRoot(){
        $path = $this->superCategorie;
        if($path != null){
            $path = $path->pathToRoot();
            $path->push($this);
        }else{
            $path = collect([$this]);
        }
        return $path->flatten();
    }
    
}
