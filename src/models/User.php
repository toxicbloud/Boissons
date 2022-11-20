<?php

namespace boissons\models;

class User extends \Illuminate\Database\Eloquent\Model
{
    protected $table = "user";
    protected $primaryKey = "id";
    public $timestamps = false;

    // public function roleid() {
    //     return $this->belongsTo('wish\models\User', 'roleid');
    // }

    // public function reservations() {
    //     return $this->hasMany('wish\models\Reservation', 'user_id');
    // }
}
