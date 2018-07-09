<?php

namespace App\Models;

class Course extends Model
{
    protected $table = "curso";

    protected $fillable = ['codigo', 'nombre', 'nombre_corto', 'programa'];

    public $timestamps = false;

    public function registers()
    {
        return $this->hasMany(Register::class, 'curso', 'codigo');
    }

    public function programs()
    {
        return $this->belongsTo(Program::class, 'programa', 'codigo');
    }

    public function getProgramaAttribute($value)
    {
        $programa = Program::where('codigo', $value)->first();
        return $programa->nombre;
    }

    public function scopePascual($query)
    {
        return $query->where('codigo','LIKE', "101%")
            ->orWhere('codigo','LIKE', "201%")
            ->orWhere('codigo','LIKE', "301%");
    }

    public function scopeColegio($query)
    {
        return $query->where('codigo','LIKE', "102%")
            ->orWhere('codigo','LIKE', "202%")
            ->orWhere('codigo','LIKE', "302%");
    }

    public function scopeITM($query)
    {
        return $query->where('codigo','LIKE', "103%")
            ->orWhere('codigo','LIKE', "203%")
            ->orWhere('codigo','LIKE', "303%");
    }

    public function scopeRuta($query)
    {
        return $query->where('codigo','LIKE', "104%")
            ->orWhere('codigo','LIKE', "204%")
            ->orWhere('codigo','LIKE', "304%");
    }


    static function checkCodigo($codigo)
    {
        $result = Course::where('codigo', '=', $codigo)->get();
        if ($result->count() < 1) {
            return true;
        }
        return false;
    }
}