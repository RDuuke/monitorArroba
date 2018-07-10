<?php

namespace App\Models;

class Module extends Model
{
    protected $table = "modulos";

    protected $fillable = ['nombre'];

    public $timestamps = false;

}