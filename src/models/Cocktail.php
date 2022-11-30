<?php

namespace boissons\models;

class Cocktail extends \Illuminate\Database\Eloquent\Model
{
    protected $table = "cocktail";
    protected $primaryKey = "id";
    public $timestamps = false;
    public function composition() {
        return $this->hasManyThrough('boissons\models\Aliment', 'boissons\models\Composition', 'id_cocktail', 'id', 'id', 'id_aliment');
    }
}