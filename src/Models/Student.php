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
        return $this->hasMany(Register::class, 'usuario', 'usuario');
    }

    public function scopeRutaN($query)
    {
        return $query->where('institucion', 'RutaN')->get()->toArray();
    }

    public function scopePascualBravo($query)
    {
        return $query->where('institucion', 'Institución Universitaria Pascual Bravo')->get()->toArray();
    }

    public function scopeColegioMayor($query)
    {
        return $query->where('institucion', 'Institución Universitaria Colegio Mayor de Antioquia')->get()->toArray();
    }

    public function scopeITM($query)
    {
        return $query->where('institucion', 'Institución Universitaria ITM')->get()->toArray();
    }

}