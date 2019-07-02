<?php

namespace App\Models;


use Carbon\Carbon;

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

    public function getStartdateAttribute($value)
    {
        return Carbon::createFromTimestamp($value)->toDateString();
    }

    public function getidnumberAttribute($value)
    {
        return $value == "" ? "Sin código" : $value;
    }
}