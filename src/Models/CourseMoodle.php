<?php

namespace App\Models;


class CourseMoodle extends Model
{
    protected $table = 'courses_moodle';

    protected $fillable = [
        "visible"
    ];

    public function getVisibleAttribute($value)
    {
        if ($value == 1) {
            return "Publicado";
        }
        return "No Publicado";
    }
}