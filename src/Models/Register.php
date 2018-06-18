<?php

namespace App\Models;

class Register extends Model
{
    protected $table = "matricula";

    protected $fillable = ['curso', 'instancia', 'usuario', 'rol', 'fecha'];

    public $timestamps = false;

    function student()
    {
        return $this->belongsTo(Student::class, 'usuario', 'usuario');
    }

    function instancia()
    {
        return $this->belongsTo(Instance::class, 'instancia', 'codigo');
    }

    function course()
    {
        return $this->belongsTo(Course::class, 'curso', 'codigo');
    }

    function getFechaAttribute($value)
    {
        return array_shift(explode(" ", $value));
    }
    function getRolAttribute($value)
    {
        return $value == 'student' ? 'Estudiante' : 'Profesor';
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