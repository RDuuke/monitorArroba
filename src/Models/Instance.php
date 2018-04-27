<?php

namespace App\Models;

class Instance extends Model
{
    protected $table = "instancia";

    protected $fillable = ['codigo', 'nombre'];

    public $timestamps = false;

    public function registers()
    {
        return $this->hasMany('App\Models\Register', 'instancia');
    }
}