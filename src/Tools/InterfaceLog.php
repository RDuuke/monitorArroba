<?php
namespace App\Tools;

interface InterfaceLog
{
    static function e (string $message, int $type ) : bool;

    static function i (string $message, int $type ) : bool;

    static function write (string $message) : bool;
}