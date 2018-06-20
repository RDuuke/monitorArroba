<?php

namespace App\Models;

class RegisterArchive extends Model
{
    protected $table = "matricula_historico";

    protected $fillable = ['curso', 'instancia', 'usuario', 'rol', 'fecha'];

    public $timestamps = false;

}