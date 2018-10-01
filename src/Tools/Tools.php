<?php
namespace App\Tools;

use App\Models\Permission;

class Tools {

    const codigoUsuarioPlataforma = 1;
    const codigoUsuarioCampus = 2;
    const codigoInstancias = 3;
    const codigoInstituciones = 4;
    const codigoProgramas = 5;
    const codigoCursos = 6;
    const codigoMatriculas = 7;
    const codigoBusqueda = 8;
    const Lectura = 1;
    const LecturaEscritura = 3;

    static protected $UserMessage = [
        0 => "El usuario correcto.",
        1 => "El usuario existe, pero con este documento de identidad :documento.",
        2 => "El usuario no pertenece a tu institución.",
        3 => "El usuario existe con ese documento y correo.",
        4 => "El usuario existe con ese documento, pero con este correo :usuario",
        5 => "El registro no tiene la estructura de un correo valido en el campo usuario",
        6 => "El usuario no tiene un documento de identidad"
    ];

    static protected $UserCodigo = [
        "C01", "A01", "E01", "E02", "E03", "E04", "E05"
    ];

    static protected $RegisterMessage = [
        "El registro no tiene la estructura de un correo valido en el campo usuario",
        "El codigo :codigo no tiene ningun curso asociado",
        "El rol tiene que ser student, teacher y editingteacher, no :rol",
        "La instancia :instancia no es valida",
        "La matricula es correcta",
        "El usuario con el correo :usuario no existe",
        "El usuario :usuario ya esta matriculado en el curso :curso"
    ];
    static protected $RegisterCodigo = [
        "E01", "E02", "E03", "E04", "C01", "E05", "A01"
    ];

    static protected $CourseMessage = [
        "El programa con el codigo :codigo no existe",
        "El curso con el codigo :codigo ya existe",
        "El curso con el codigo :codigo creado correctamente"
    ];

    static protected $CourseCodigo = [
        "E01", "E02", "A01"
    ];

    static protected $Institution = [
        "nombres" => [
            0 => "Institución Universitaria Pascual Bravo",
            1 => "Institución Universitaria Colegio Mayor de Antioquia",
            2 => "Institución Universitaria ITM",
            3 => "Ruta N",
            4 => "@Medellín",
            5 => "Secretaría de Salud",
            6 => "secretaría de la Mujer"
        ],
        "codigo" => [
            0 => "01",
            1 => "02",
            2 => "03",
            3 => "04",
            4 => "05",
            5 => "06",
            6 => "07"
        ]
    ];

    static protected $InstitutionForCodigo = [
        "01" => "Institución Universitaria Pascual Bravo",
        "02" => "Institución Universitaria Colegio Mayor de Antioquia",
        "03" => "Institución Universitaria ITM",
        "04" => "Ruta N",
        "05" => "@Medellín",
        "06" => "Secretaría de Salud",
        "07" => "Secretaría de la Mujer"
    ];

    static protected $Instance = [
        "nombre" => [
            1 => "Pregrado",
            2 => "Posgrado",
            3 => "FTDH",
            4 => "Ruta N",
            5 => "SandBox",
            6 => "IUPB",
            8 => "Colmayor",
            9 => "ITM"
        ]
    ];

    static public $Modules = [
        "Usuarios @Monitor" , "Usuarios Campus", "matriculas", "instancias", "instituciones", "programas", "búsqueda", "cursos"
    ];

    static public $MenuActive = [
        "Módulo", "Búsqueda", "Herramientas"
    ];

    static public $Roles = [
      "student", "teacher", "editingteacher", "manager"
    ];
    static function moveUploadedFile($uploadedFile, $dir)
    {
        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
        $basename = bin2hex(random_bytes(8)); // see http://php.net/manual/en/function.random-bytes.php
        $filename = sprintf('%s.%0.8s', $basename, $extension);
        $filename = date("d-m-y") ." _ ". $filename;
        try{
            $uploadedFile->moveTo($dir  . $filename);
            return $filename;
        } catch (\Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    static function getInstitutionForCodigo(String $codigo)
    {
        return self::$InstitutionForCodigo[$codigo];
    }

    static function getInstanceForCodigo(Int $codigo)
    {
        return self::$Instance['nombre'][$codigo];
    }
    
    static function getMessageUser($i)
    {
        return self::$UserMessage[$i];
    }

    static function getCodigoUser($i)
    {
        return self::$UserCodigo[$i];
    }

    static function getCodigoRegister($i)
    {
        return self::$RegisterCodigo[$i];
    }
    static function getMessageRegister($id)
    {
        return self::$RegisterMessage[$id];
    }

    static function getMessageCourse($i)
    {
        return self::$CourseMessage[$i];
    }

    static function getCodigoCourse($id)
    {
        return self::$CourseCodigo[$id];
    }

    static function getMessageModule($i)
    {
        return "módulo ".self::$Modules[$i];
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

    static function codigoMujeres()
    {
        return self::$Institution['codigo'][6];
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

    static function refreshPermission($id)
    {
        foreach(Permission::where('user_id', '=', $id)->get()->toArray() as $k => $v){
            foreach ($v as $k2 => $v2) {
                $_SESSION['permission']['modules'][$v2] = $v;
                break;
            }

        }
    }
}