<?php

namespace App\Models;

class User extends Model
{
    protected $table = "usuario_gestion";

    protected $fillable = ['usuario', 'clave', 'nombres', 'apellidos', 'documento', 'id_rol', 'id_institucion'];

    function institution()
    {
        return $this->belongsTo(Institution::class, 'id_institucion', 'codigo');
    }
}