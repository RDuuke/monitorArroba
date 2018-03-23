<?php

namespace App\Models;

class Student extends Model
{
    protected $table = "usuario";

    protected $fillable = ['usuario', 'clave', 'nombres', 'apellidos', 'documento', 'institucion', 'genero', 'ciudad', 'departamento', 'pais', 'telefono', 'celular', 'direccion'];

    static function getForInstitution($where)
    {

        return Student::where('institucion', 'Like', $where)->get();
    }
}