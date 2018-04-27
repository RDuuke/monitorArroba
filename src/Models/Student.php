<?php

namespace App\Models;

class Student extends Model
{
    protected $table = "usuario";

    protected $fillable = ['usuario', 'clave', 'nombres', 'correo', 'apellidos', 'documento', 'institucion', 'genero', 'ciudad', 'departamento', 'pais', 'telefono', 'celular', 'direccion'];

    protected $hidden = ['created_at', 'updated_at'];

    static function getForInstitution($where)
    {

        return Student::where('institucion', 'Like', $where)->get()->toArray();
    }

    public function registers()
    {
        return $this->hasMany('App\Models\Register', 'usuario');
    }

}