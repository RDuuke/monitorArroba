<?php
namespace App\Tools;

class Log implements InterfaceLog
{
    static protected $tipo = [
        "Creación", "Actualización", "Eliminación", "Ingresos", "Salida", "Carga"
    ];

    static function i (string $message, int $type = 0) : bool
    {
        $log = "INFO[".date('d-m-Y h:i:s')."]: [" . self::$tipo[$type]  . "] " . $message . " " . "\n";
        return self::write($log);
    }

    static function e (string $message, int $type = 0) : bool
    {
        $log = "ERROR[".date('d-m-Y h:i:s')."]: [" . self::$tipo[$type]  . "] " . $message . " " . "\n";
        return self::write($log);
    }

    static function write(string $message) : bool
    {
        $directory = BASE_DIR . 'logs' . DS . 'logs.txt';

        if (is_writable($directory)) {

            if ($archive = fopen($directory, 'a+')) {

                if (fwrite($archive, $message)) {
                    return true;
                }
            }

        }

        return false;

    }
}