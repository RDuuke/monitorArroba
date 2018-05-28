<?php

namespace App\Models;

class Institution extends Model
{
    protected $table = "institucion";

    protected $fillable = ['codigo', 'nombre', 'img'];

    static function checkCodigo($codigo)
    {
        $result = Institution::where('codigo', '=', $codigo)->get();
        if ($result->count() < 1) {
            return true;
        }
        return false;
    }
}