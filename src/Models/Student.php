<?php

namespace App\Models;

class Student extends Model
{
    protected $table = "usuario";

    protected $fillable = ['usuario', 'nombres', 'clave', 'correo', 'apellidos', 'documento', 'institucion', 'genero', 'ciudad', 'departamento', 'pais', 'telefono', 'celular', 'direccion', 'fecha'];

    protected $hidden = ['created_at', 'updated_at'];

    public $timestamps = false;

    static function getForInstitution($where)
    {

        return Student::where('institucion', 'Like', $where)->get()->toArray();
    }

    public function getGeneroAttribute($value)
    {
        if (!empty($value)) {
            if ($value == '1' || $value == '2') {
                return $value == '1' ? 'M' : 'F';
            }
        }
        return strtoupper($value);
    }

    public function getFechaAttribute($value)
    {
        $date = new \DateTime($value);
        return $date->format('d-m-Y');
    }

    public function registers()
    {
        return $this->hasMany(Register::class, 'usuario', 'usuario');
    }

    public function institutions()
    {
        return $this->belongsToMany(Institution::class, 'institucion_usuario', 'usuario', 'codigo', 'usuario', 'codigo');
    }

    public function registershistoricos()
    {
        return $this->hasMany(RegisterArchive::class, 'usuario', 'usuario');
    }

    public function archive()
    {
        StudentArchive::create($this->toArray());
    }

}