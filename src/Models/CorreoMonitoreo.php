<?php

namespace App\Models;


class CorreoMonitoreo extends Model
{
    protected $table = "correo_monitoreo";
    protected $fillable = ["correo"];
    public $timestamps = false;

}