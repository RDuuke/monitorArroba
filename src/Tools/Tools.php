<?php
namespace App\Tools;

use App\Models\Course;
use App\Models\Permission;
use App\Models\Program;
use App\Models\Register;
use App\Models\Student;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Support\Str;

class Tools {

    const codigoUsuarioPlataforma = 1;
    const codigoUsuarioCampus = 2;
    const codigoInstancias = 3;
    const codigoInstituciones = 4;
    const codigoProgramas = 5;
    const codigoCursos = 6;
    const codigoMatriculas = 7;
    const codigoBusqueda = 8;
    const codigoReporte = 9;
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
            6 => "secretaría de la Mujer",
            7 => "Sapiencia"
        ],
        "codigo" => [
            0 => "01",
            1 => "02",
            2 => "03",
            3 => "04",
            4 => "05",
            5 => "06",
            6 => "07",
            7 => "08"
        ]
    ];

    static protected $InstitutionForCodigo = [
        "01" => "Institución Universitaria Pascual Bravo",
        "02" => "Institución Universitaria Colegio Mayor de Antioquia",
        "03" => "Institución Universitaria ITM",
        "04" => "Ruta N",
        "05" => "@Medellín",
        "06" => "Secretaría de Salud",
        "07" => "Secretaría de la Mujer",
        "08" => "Sapiencia"
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
        1 => "Usuarios @Monitor" ,
        2 => "Usuarios Campus",
        7 => "Matriculas",
        3 => "Instancias",
        4 => "Instituciones",
        5 => "Programas",
        8 => "Búsqueda",
        6 => "Cursos",
        9 => "Reportes"
    ];

    static public $CodePDO = [
        23000 => "Ya existe un usuario con el correo :correo"
    ];

    static public $MenuActive = [
        "Módulo", "Búsqueda", "Herramientas"
    ];

    static public $Roles = [
      "student", "teacher", "editingteacher", "manager"
    ];

    static protected $tipo = [
        "Creación", "Actualización", "Eliminación", "Ingreso", "Salida", "Carga", "Alerta"
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

    static function codigoSalud()
    {
        return self::$Institution['codigo'][5];
    }
    static function codigoSapiencia()
    {
        return self::$Institution['codigo'][7];
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

    static function getMessageCreaterRegisterModule(Int $module, String $user, String $valor)
    {
        return "El usuario $user creando el registro $valor en el módulo " . self::$Modules[$module];
    }

    static function getMessageUpdateRegisterModule(Int $module, String $user, String $valor)
    {
        return "El usuario $user actualizando el registro $valor en el módulo " . self::$Modules[$module];
    }

    static function getMessageDeleteRegisterModule(Int $module, String $user, String $valor)
    {
        return "El usuario $user eliminando el registro $valor en el módulo " . self::$Modules[$module];
    }
    static function getMessageImportModule(Int $module, String $user) : String
    {
        return "El usuario $user utilizo la herramienta masiva en el módulo " . self::$Modules[$module];
    }

    static function getEnterModuleMessage(int $module, String $user) : String
    {
        return "El usuario $user ingreso al módulo " . self::$Modules[$module];
    }

    static function getTryEnterModuleMessage(Int $module, String $user) : String
    {
        return "El usuario $user intentó ingresar al módulo " . self::$Modules[$module] . " sin tener permisos.";
    }
    static function getTypeCreatorAction() : String
    {
        return self::$tipo[0];
    }

    static function getTypeUpdateAction() : String
    {
        return self::$tipo[1];
    }

    static function getTypeDeleteAction() : String
    {
        return self::$tipo[2];
    }

    static function getTypeAction(int $valor) : String {
        return self::$tipo[$valor];
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

    static function getDataGeneralForMonth(int $type, string $firstDate, string $lastDate) : array
    {
        if ( $type == 1) {
            return $dataTable = [
                "cursos" => [
                    "pascual" => Course::where('institucion_id', Tools::codigoPascualBravo())
                        ->whereBetween("fecha", [$firstDate, $lastDate])->get()->count(),
                    "sapiencia" => Course::where('institucion_id', Tools::codigoSapiencia())
                        ->whereBetween("fecha", [$firstDate, $lastDate])->get()->count(),
                    "itm" => Course::where('institucion_id', Tools::codigoITM())
                        ->whereBetween("fecha", [$firstDate, $lastDate])->get()->count(),
                    "colmayor" => Course::where('institucion_id', Tools::codigoColegioMayor())
                        ->whereBetween("fecha", [$firstDate, $lastDate])->get()->count(),
                    "secremujer" => Course::where('institucion_id', Tools::codigoMujeres())
                        ->whereBetween("fecha", [$firstDate, $lastDate])->get()->count(),
                    "secresalud" => Course::where('institucion_id', Tools::codigoSalud())
                        ->whereBetween("fecha", [$firstDate, $lastDate])->get()->count(),
                    "total" => Course::whereBetween("fecha", [$firstDate, $lastDate])->get()->count()
                ],
                "matriculas" => [
                    "pascual" => Register::where('institucion_id', Tools::codigoPascualBravo())
                        ->whereBetween("fecha", [$firstDate, $lastDate])->get()->count(),
                    "sapiencia" => Register::where('institucion_id', Tools::codigoSapiencia())
                        ->whereBetween("fecha", [$firstDate, $lastDate])->get()->count(),
                    "itm" => Register::where('institucion_id', Tools::codigoITM())
                        ->whereBetween("fecha", [$firstDate, $lastDate])->get()->count(),
                    "colmayor" => Register::where('institucion_id', Tools::codigoColegioMayor())
                        ->whereBetween("fecha", [$firstDate, $lastDate])->get()->count(),
                    "secremujer" => Register::where('institucion_id', Tools::codigoMujeres())
                        ->whereBetween("fecha", [$firstDate, $lastDate])->get()->count(),
                    "secresalud" => Register::where('institucion_id', Tools::codigoSalud())
                        ->whereBetween("fecha", [$firstDate, $lastDate])->get()->count(),
                    "total" => Register::whereBetween("fecha", [$firstDate, $lastDate])->get()->count()
                ],
                "programas" => [
                    "pascual" => Program::where('codigo_institucion', Tools::codigoPascualBravo())
                        ->whereBetween("fecha", [$firstDate, $lastDate])->get()->count(),
                    "sapiencia" => Program::where('codigo_institucion', Tools::codigoSapiencia())
                        ->whereBetween("fecha", [$firstDate, $lastDate])->get()->count(),
                    "itm" => Program::where('codigo_institucion', Tools::codigoITM())
                        ->whereBetween("fecha", [$firstDate, $lastDate])->get()->count(),
                    "colmayor" => Program::where('codigo_institucion', Tools::codigoColegioMayor())
                        ->whereBetween("fecha", [$firstDate, $lastDate])->get()->count(),
                    "secremujer" => Program::where('codigo_institucion', Tools::codigoMujeres())
                        ->whereBetween("fecha", [$firstDate, $lastDate])->get()->count(),
                    "secresalud" => Program::where('codigo_institucion', Tools::codigoSalud())
                        ->whereBetween("fecha", [$firstDate, $lastDate])->get()->count(),
                    "total" => Program::whereBetween("fecha", [$firstDate, $lastDate])->get()->count()
                ],
                "usuarios" => [
                    "pascual" => Student::where('institucion_id', Tools::codigoPascualBravo())
                        ->whereBetween("fecha", [$firstDate, $lastDate])->get()->count(),
                    "sapiencia" => Student::where('institucion_id', Tools::codigoSapiencia())
                        ->whereBetween("fecha", [$firstDate, $lastDate])->get()->count(),
                    "itm" => Student::where('institucion_id', Tools::codigoITM())
                        ->whereBetween("fecha", [$firstDate, $lastDate])->get()->count(),
                    "colmayor" => Student::where('institucion_id', Tools::codigoColegioMayor())
                        ->whereBetween("fecha", [$firstDate, $lastDate])->get()->count(),
                    "secremujer" => Student::where('institucion_id', Tools::codigoMujeres())
                        ->whereBetween("fecha", [$firstDate, $lastDate])->get()->count(),
                    "secresalud" => Student::where('institucion_id', Tools::codigoSalud())
                        ->whereBetween("fecha", [$firstDate, $lastDate])->get()->count(),
                    "total" => Student::whereBetween("fecha", [$firstDate, $lastDate])->get()->count()
                ]
            ];
        }
    }

    static function studentData(int $type = 0, string $firstDate = '', string $lastDate = "") : array
    {
        if ($type == 0) {
            $data = Manager::table("usuario")
                ->join("institucion", "usuario.institucion_id", "=", "institucion.codigo")
                ->select("institucion.nombre", Manager::raw("COUNT(institucion.codigo) AS cantidad"))
                ->groupBy("institucion.nombre")
                ->get();
        } else {
            $data = Manager::table("usuario")
                ->join("institucion", "usuario.institucion_id", "=", "institucion.codigo")
                ->select("institucion.nombre", Manager::raw("COUNT(institucion.codigo) AS cantidad"))
                ->whereBetween("usuario.fecha", [$firstDate, $lastDate])
                ->groupBy("institucion.nombre")
                ->get();
        }

        return $data->all();
    }

    static function getHighestDataRow($worksheet) : int
    {
        return count(array_filter(array_map("array_filter",$worksheet->toArray())));
    }

}