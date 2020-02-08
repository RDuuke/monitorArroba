<?php

namespace App\Models;


class StudentArchive extends Model
{
    protected $table = "usuario_historico";
    protected $primaryKey = "usuario";
    protected $fillable = ['usuario', 'clave', 'nombres', 'correo', 'apellidos', 'documento', 'institucion', 'genero', 'ciudad', 'departamento', 'pais', 'telefono', 'celular', 'direccion', 'fecha', 'institucion_carga'];
    public $timestamps = false;

}