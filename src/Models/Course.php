<?php

namespace App\Models;

class Course extends Model
{
    protected $table = "curso";

    protected $fillable = ['codigo', 'nombre', 'nombre_corto', 'id_programa'];

    public $timestamps = false;

    public function registers()
    {
        return $this->hasMany('App\Models\Register', 'curso');
    }
}