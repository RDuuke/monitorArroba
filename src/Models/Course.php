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

    public function scopePascual($query)
    {
        return $query->where('codigo','LIKE', "101%")
            ->orWhere('codigo','LIKE', "201%")
            ->orWhere('codigo','LIKE', "301%");
    }
}