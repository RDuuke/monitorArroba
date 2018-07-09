<?php

namespace App\Models;


class Program extends Model
{
    protected $table = "programa";

    protected $fillable = ['codigo', 'nombre', 'codigo_institucion'];

    public $timestamps = false;


    public function course()
    {
        return $this->hasMany(Course::class, 'programa', 'codigo');
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

    static function checkCodigo($codigo)
    {
        $result = Program::where('codigo', '=', $codigo)->get();
        if ($result->count() < 1) {
            return true;
        }
        return false;
    }

}