<?php

namespace App\Models;

class Register extends Model
{
    protected $table = "matricula";

    protected $fillable = ['curso', 'instancia', 'usuario', 'rol', 'fecha'];

    public $timestamps = false;

    function usuario()
    {
        return $this->belongsTo("App\Models\Student", 'usuario', 'usuario');
    }

    function instancia()
    {
        return $this->belongsTo("App\Model\Instance", 'instancia', 'codigo');
    }

    function course()
    {
        return $this->belongsTo("App\Model\Course", 'curso', 'codigo');
    }

    function getFechaAttribute($value)
    {
        return array_shift(explode(" ", $value));
    }

    function scopePublic($query)
    {
        return $query->whereIn('instancia', [1, 2, 3]);
    }

    public function scopeRuta($query)
    {
        return $query->where('instancia', 4);
    }

}