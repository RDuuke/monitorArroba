<?php

namespace App\Models;

class Institution extends Model
{
    protected $table = "institucion";

    protected $fillable = ['codigo', 'nombre', 'img'];
}