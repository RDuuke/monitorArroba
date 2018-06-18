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

    static function checkCodigo($codigo)
    {
        $result = Program::where('codigo', '=', $codigo)->get();
        if ($result->count() < 1) {
            return true;
        }
        return false;
    }
}