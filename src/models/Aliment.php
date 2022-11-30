<?php

namespace boissons\models;

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
}
