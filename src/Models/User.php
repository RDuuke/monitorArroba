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

    public function scopeITM($query)
    {
        return $query->where("id_institucion", "=", 03)->get();
    }
    public function scopeColegio($query)
    {
        return $query->where("id_institucion", "=", 02)->get();
    }
    public function scopePascual($query)
    {
        return $query->where("id_institucion", "=", 01)->get();
    }
    public function scopeRuta($query)
    {
        return $query->where("id_institucion", "=", 04)->get();
    }
}