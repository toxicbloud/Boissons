<?php
namespace boissons\models;
class Composition extends \Illuminate\Database\Eloquent\Model
{
    protected $table = "composition";
    protected $primaryKey = "id";
    public $timestamps = false;
}