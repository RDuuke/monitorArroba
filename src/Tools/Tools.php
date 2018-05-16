<?php
namespace App\Tools;

class Tools {

    static public $UserMessage = [
        0 => "El usuario fue creado correctamente.",
        1 => "El usuario existe, pero con otro documento.",
        2 => "El usuario no pertenece a tu institución.",
        3 => "El usuario existe con ese documento y correo."
    ];

    static public $Institution = [
        "nombres" => [
            0 => "Institución Universitaria Pascual Bravo",
            1 => "Institución Universitaria Colegio Mayor de Antioquia",
            2 => "Institución Universitaria ITM",
            3 => "Ruta N",
            4 => "@Medellín"
        ],
        "codigo" => [
            0 => "01",
            1 => "02",
            2 => "03",
            3 => "04",
            4 => "05"
        ]
    ];

    static public $Instance = [
        "nombre" => [
            1 => "Pregrado",
            2 => "Posgrado",
            3 => "FTDH",
            4 => "Ruta N",
            5 => "SandBox",
            6 => "Mainsite"
        ]
    ];

    static function moveUploadedFile($uploadedFile, $dir)
    {
        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
        $basename = bin2hex(random_bytes(8)); // see http://php.net/manual/en/function.random-bytes.php
        $filename = sprintf('%s.%0.8s', $basename, $extension);
        $filename = date("d-m-y") ." _ ". $filename;
        try{
            $uploadedFile->moveTo($dir . DIRECTORY_SEPARATOR . $filename);
            return $filename;
        } catch (\Exception $e) {
            return false;
        }
    }

    static function getMessageUser($i)
    {
        return self::$UserMessage[$i];
    }

    static function nombreColegioMayor()
    {
        return self::$Institution['nombre'][1];
    }

    static function nombrePascualBravo()
    {
        return self::$Institution['nombre'][0];
    }

    static function nombreITM()
    {
        return self::$Institution['nombre'][2];
    }

    static function nombreRutaN()
    {
        return self::$Institution['nombre'][3];
    }

    static function nombreMedellin()
    {
        return self::$Institution['nombre'][4];
    }

    static function codigoColegioMayor()
    {
        return self::$Institution['codigo'][1];
    }

    static function codigoPascualBravo()
    {
        return self::$Institution['codigo'][0];
    }

    static function codigoITM()
    {
        return self::$Institution['codigo'][2];
    }

    static function codigoRutaN()
    {
        return self::$Institution['codigo'][3];
    }

    static function codigoMedellin()
    {
        return self::$Institution['codigo'][4];
    }

    static function Pregado()
    {
        return self::$Institution['nombre'][1];
    }

    static function Posgrado()
    {
        return self::$Institution['nombre'][2];
    }

    static function FTDH()
    {
        return self::$Institution['nombre'][3];
    }

    static function RutaN()
    {
        return self::$Institution['nombre'][4];
    }

    static function Sandbox()
    {
        return self::$Institution['nombre'][5];
    }

    static function Mainsite()
    {
        return self::$Institution['nombre'][6];
    }

}