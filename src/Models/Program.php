<?php

namespace App\Models;


use App\Tools\Tools;

class Program extends Model
{
    protected $table = "programa";

    protected $fillable = ['codigo', 'nombre', 'codigo_institucion', 'fecha'];

    public $timestamps = false;

    public function getFechaAttribute($value)
    {
        $date = new \DateTime($value);
        return $date->format('d-m-Y');
    }

    public function getCodigoInstitucionAttribute($value)
    {
        return Tools::getInstitutionForCodigo($value);
    }

    public function course()
    {
        return $this->hasMany(Course::class, 'id_programa', 'codigo');
    }

    public function institution()
    {
        return $this->belongsTo(Institution::class, 'codigo_institucion', 'codigo');
    }

    public function scopeITM($query)
    {
        return $query->where("codigo_institucion", "=", 03)->get();
    }
    public function scopeColegio($query)
    {
        return $query->where("codigo_institucion", "=", 02)->get();
    }
    public function scopePascual($query)
    {
        return $query->where("codigo_institucion", "=", 01)->get();
    }
    public function scopeRuta($query)
    {
        return $query->where("codigo_institucion", "=", 04)->get();
    }
    public function scopeMujeres($query)
    {
        return $query->where('curso','LIKE', 07)->get();
    }
    static function checkCodigo($codigo)
    {
        $result = Program::where('codigo', '=', $codigo)->get();
        if ($result->count() < 1) {
            return true;
        }
        return false;
    }

}