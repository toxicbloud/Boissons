<?php

namespace boissons\models;

class Panier extends \Illuminate\Database\Eloquent\Model
{
    protected $table = "compositionPanier";
    protected $primaryKey = "id";
    public $timestamps = false;

    public function cocktail(){
        return $this->belongsTo(Cocktail::class, 'id_cocktail');
    }
}
