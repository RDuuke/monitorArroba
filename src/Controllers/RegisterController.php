<?php

namespace App\Controllers;

use App\Models\Instance;
use App\Models\RegisterArchive;
use App\Models\Student;
use App\Tools\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Respect\Validation\Exceptions\PhpLabelException;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Models\Register;
use App\Tools\Tools;
use App\Models\Course;

class RegisterController extends Controller
{
    protected $errors = [];
    protected $creators = [];
    protected $alerts = [];

    function all(Request $request, Response $response) : Response
    {
            if ($this->auth->user()->id_institucion != Tools::codigoMedellin()) {
                $registers = Register::where('institucion_id', $this->auth->user()->id_institucion)->get();
            } else {
                $registers = Register::all();
            }
            $newResponse = $response->withHeader('Content-type', 'application/json');
            return $newResponse->withJson($registers, 200);
    }

    function store(Request $request, Response $response)
    {
        $validate = Register::where([
            ['curso', '=', $request->getParam('curso')],
            ['usuario', '=', $request->getParam('usuario')]
        ])->get();

        if ($validate->count() != 0) {
            if ($request->isXhr()) {
                $data_array = ["message" => 3, "register" => null];
                $newResponse = $response->withHeader('Content-type', 'application/json');
                return $newResponse->withJson($data_array, 200);
            }
            $this->flash->addMessage("errors", "El usuario ".$request->getParam('usuario')." ya esta matriculado en el curso " . $request->getParam('curso'));
            return $response->withRedirect($this->router->pathFor("admin.register.add"));
        }
        try{
            $register = $this->saveRegister($request);
            if ($request->isXhr()) {
                if ($register->save()) {
                    Log::i(Tools::getMessageCreaterRegisterModule(Tools::codigoMatriculas, $this->auth->user()->usuario, $request->getParam('curso') . "  " . $request->getParam('usuario')), Tools::getTypeCreatorAction());
                    $register->fecha = date('Y-m-d h:i:s');
                    $data_array = ["message" => 1, "register" => $register];
                    $newResponse = $response->withHeader('Content-type', 'application/json');
                    return $newResponse->withJson($data_array, 200);
                }
            } else {
                if ($register->save()) {
                    Log::i(Tools::getMessageCreaterRegisterModule(Tools::codigoMatriculas, $this->auth->user()->usuario, $request->getParam('curso') . "  " . $request->getParam('usuario')), Tools::getTypeCreatorAction());
                    $this->flash->addMessage("creators", "Matricula creada correctamente");
                    return $response->withRedirect($this->router->pathFor("admin.register.add"));
                }
            }
        }catch(\Exception $e) {
            Log::e(Tools::getMessageCreaterRegisterModule(Tools::codigoMatriculas, $this->auth->user()->usuario, $request->getParam('codigo') . "  " . $request->getParam('usuario')), Tools::getTypeCreatorAction());
            if ($request->isXhr()) {
                return $response->withStatus(500)->write(0);
            } else {
                $this->flash->addMessage("errors", $e->getMessage());
                return $response->withRedirect($this->router->pathFor('admin.register.add'));
            }        }
    }

    function show(Request $request, Response $response) : Response
    {
        $router = $request->getAttribute('route');
        $register= Register::find($router->getArguments()['id'])->toArray();
        try {
            $newResponse = $response->withHeader('Content-type', 'application/json');
            return $newResponse->withJson($register, 200);
        } catch(\Exception $e) {
            return $response->withStatus(500)->write('0');
        }
    }

    function update(Request $request, Response $response) : Response
    {
        print_r($request->getParams());
        die;
        $router = $request->getAttribute('route');
        $register = Register::updateOrCreate(['id' => $router->getArguments()['id']],$request->getParams());
        try{
            if ($register != false) {
                Log::i(Tools::getMessageUpdateRegisterModule(Tools::codigoMatriculas, $this->auth->user()->usuario, $register->curso . "  " . $register->usuario), Tools::getTypeUpdateAction());
                $data_array = ["message" => 2, "register" => $register];
                $newResponse = $response->withHeader('Content-type', 'application/json');
                return $newResponse->withJson($data_array, 200);
            }
        }catch(\Exception $e) {
            Log::e(Tools::getMessageUpdateRegisterModule(Tools::codigoMatriculas, $this->auth->user()->usuario, $register->curso . "  " . $register->usuario), Tools::getTypeUpdateAction());
            return $response->withStatus(500)->write('0');
        }
    }

    function delete(Request $request, Response $response) : Response
    {
        $router = $request->getAttribute('route');
        $register= Register::find($router->getArguments()['id']);
        try {

            if ($register->delete()) {
                Log::i(Tools::getMessageDeleteRegisterModule(Tools::codigoMatriculas, $this->auth->user()->usuario, $register->curso . "  " . $register->usuario), Tools::getTypeDeleteAction());
                return $response->withStatus(200)->write('1');
            }
        } catch(\Exception $e) {
            Log::e(Tools::getMessageDeleteRegisterModule(Tools::codigoMatriculas, $this->auth->user()->usuario, $register->curso . "  " . $register->usuario), Tools::getTypeDeleteAction());
            return $response->withStatus(500)->write('0');
        }
    }

    function upload(Request $request, Response $response) : Response
    {
        $uploadedFiles = $request->getUploadedFiles();
        $archive = $uploadedFiles['archive'];
        if ($archive->getError() == UPLOAD_ERR_OK) {
            $filename = Tools::moveUploadedFile($archive, $this->tmp);
            if ($filename != false) {
                try {
                    $reader = IOFactory::createReader('Xlsx');
                    $reader->setReadDataOnly(true);
                    $spreadsheet = $reader->load($this->tmp . DS . $filename);
                    $worksheet = $spreadsheet->getActiveSheet();
                    $highestRow = Tools::getHighestDataRow($worksheet);
                    //$highestRow = $worksheet->getHighestDataRow();
                    for ($row = 2; $row <= $highestRow; $row++) {
                        $data = [
                            "curso" => trim($worksheet->getCell('A' . $row)->getvalue()),
                            "instancia" => trim($worksheet->getCell('B' . $row)->getvalue()),
                            "usuario" => trim($worksheet->getCell('C' . $row)->getvalue()),
                            "rol" => trim($worksheet->getCell('D' . $row)->getvalue()),
                        ];
                        if ($this->auth->user()->id_institucion != Tools::codigoMedellin()) {
                            $data['institucion_id'] = $this->auth->user()->id_institucion;
                        } else {
                            $data['institucion_id'] = $request->getParam('codigo_institucion');
                        }
                        if (!filter_var(trim($data['usuario']), FILTER_VALIDATE_EMAIL)) {
                            $data['message'] = Tools::getMessageRegister(0);
                            $data['codigo'] = Tools::getCodigoRegister(0);
                            array_push($this->errors, $data);
                            unset($data);
                            continue;
                        }
                        $student = Student::where('usuario', $data['usuario'])->get();
                        if ($student->count() == 0) {
                            $data['message'] = str_replace(':usuario', $data['usuario'], Tools::getMessageRegister(5));
                            $data['codigo'] = Tools::getCodigoRegister(5);
                            array_push($this->errors, $data);
                            unset($data);
                            continue;
                        }
                        $course = Course::where('codigo', $data['curso'])->get();
                        if ($course->count() == 0) {
                            $data['message'] = str_replace(':codigo', $data['curso'], Tools::getMessageRegister(1));
                            $data['codigo'] = Tools::getCodigoRegister(1);
                            array_push($this->errors, $data);
                            unset($data);
                            continue;
                        }
                        $register = Register::where(['curso' => $data['curso'], 'usuario' => $data['usuario']])->get();
                        if ($register->count() == 1) {
                            $data['message'] = str_replace(":usuario", $data['usuario'], str_replace(":curso", $data['curso'], Tools::getMessageRegister(6)));
                            $data['codigo'] = Tools::getCodigoRegister(6);
                            array_push($this->alerts, $data);
                            unset($data);
                            continue;
                        }

                        $data['instancia'] = empty($data['instancia']) ? substr($data['curso'], 0, 1):$data['instancia'];
                        $instance = Instance::where('codigo', $data['instancia'])->get();
                        if ($instance->count() == 0) {
                            $data['message'] = str_replace(':instancia', $data['instancia'], Tools::getMessageRegister(3));
                            $data['codigo'] = Tools::getCodigoRegister(3);
                            array_push($this->errors, $data);
                            unset($data);
                            continue;
                        }
                        if (array_search($data['rol'], Tools::$Roles) === false) {
                            $data['message'] = str_replace(':rol', $data['rol'], Tools::getMessageRegister(2));
                            $data['codigo'] = Tools::getCodigoRegister(2);
                            array_push($this->errors, $data);
                            unset($data);
                            continue;
                        }
                        $data['message'] = Tools::getMessageRegister(4);
                        $data['codigo'] = Tools::getCodigoRegister(4);
                        array_push($this->creators, $data);
                        unset($data);
                    }
                    $responseData = ['message' => 1, 'totalR' => ($highestRow - 1), 'totalC' => count($this->creators), 'totalA' => count($this->alerts), 'totalE' => count($this->errors), 'creators' => $this->creators, 'alerts' => $this->alerts, 'errors' => $this->errors];
                    $newResponse = $response->withHeader('Content-type', 'application/json');
                    Log::i(Tools::getMessageImportModule(Tools::codigoMatriculas, $this->auth->user()->usuario), Tools::getTypeAction(5));
                    return $newResponse->withJson($responseData, 200);

                } catch ( PhpLabelException $exception) {
                    Log::e(Tools::getMessageImportModule(Tools::codigoMatriculas, $this->auth->user()->usuario), Tools::getTypeAction(5));
                    die( "Error :" . $exception->getMessage());
                }
            }
        }
        return false;
    }

    function proccess(Request $request, Response $response)
    {
        $data = [
          "status" => 1,
          "creators" => 0,
          "errors" => 0
        ];
        $c = 0;
        $e = 0;
        $dataOK = $request->getParam('data');
        for($i = 0; $i < count($dataOK); $i++){
            if (Register::updateOrCreate(['usuario' => $dataOK[$i]['usuario'], 'curso' => $dataOK[$i]['curso']], $dataOK[$i]) instanceof Register){
               $c++;
            }
            $e++;
        }
        $data["creators"] = $c;
        $data["errors"] = $e;
        $newResponse = $response->withHeader('Content-type', 'application/json');
        return $newResponse->withJson($data, 200);
    }

    function uploadDeEnRoll(Request $request, Response $response)
    {
        $uploadedFiles = $request->getUploadedFiles();
        $archive = $uploadedFiles['archive'];
        if ($archive->getError() == UPLOAD_ERR_OK) {
            $filename = Tools::moveUploadedFile($archive, $this->tmp);
            if ($filename != false) {
                try {
                    $reader = IOFactory::createReader('Xlsx');
                    $reader->setReadDataOnly(true);
                    $spreadsheet = $reader->load($this->tmp . DS . $filename);
                    $worksheet = $spreadsheet->getActiveSheet();
                    $highestRow = Tools::getHighestDataRow($worksheet);
                    for ($row = 2; $row <= $highestRow; $row++) {
                        $data = [
                            "curso" => trim($worksheet->getCell('A' . $row)->getvalue()),
                            "usuario" => trim($worksheet->getCell('B' . $row)->getvalue())
                        ];

                        if (!filter_var(trim($data['usuario']), FILTER_VALIDATE_EMAIL)) {
                            $data['message'] = Tools::getMessageRegister(0);
                            $data['codigo'] = Tools::getCodigoRegister(0);
                            array_push($this->errors, $data);
                            unset($data);
                            continue;
                        }

                        $student = Student::where('usuario', $data['usuario'])->get();

                        if ($student->count() == 0) {
                            $data['message'] = str_replace(':usuario', $data['usuario'], Tools::getMessageRegister(5));
                            $data['codigo'] = Tools::getCodigoRegister(5);
                            array_push($this->errors, $data);
                            unset($data);
                            continue;
                        }
                        $course = Course::where('codigo', $data['curso'])->get();
                        if ($course->count() == 0) {
                            $data['message'] = str_replace(':codigo', $data['curso'], Tools::getMessageRegister(1));
                            $data['codigo'] = Tools::getCodigoRegister(1);
                            array_push($this->errors, $data);
                            unset($data);
                            continue;
                        }
                        $register = Register::where(['curso' => $data['curso'], 'usuario' => $data['usuario']])->first();
                        if ($register instanceof Register) {
                            $data['instancia'] = $register->instancia;
                            $data['rol'] = $register->rol;
                            $data['message'] = "Registro correcto para desmatricular";
                            $data['codigo'] = "C01";
                            array_push($this->creators, $data);
                            unset($data);
                            continue;
                        }
                    }
                    $responseData = ['message' => 1, 'totalR' => ($highestRow - 1), 'totalC' => count($this->creators), 'totalE' => count($this->errors), 'creators' => $this->creators, 'errors' => $this->errors];
                    $newResponse = $response->withHeader('Content-type', 'application/json');
                    Log::i(Tools::getMessageImportModule(Tools::codigoMatriculas, $this->auth->user()->usuario), Tools::getTypeAction(5));
                    return $newResponse->withJson($responseData, 200);
                } catch ( PhpLabelException $exception) {
                    Log::e(Tools::getMessageImportModule(Tools::codigoMatriculas, $this->auth->user()->usuario), Tools::getTypeAction(5));
                    die( "Error :" . $exception->getMessage());
                }
            }
        }
    }

    function proccess_register_de_en_roll(Request $request, Response $response)
    {
        $dataOK = $request->getParam('data');
        for($i = 0; $i < count($dataOK); $i++){
            Register::where(['usuario' => $dataOK[$i]['usuario'], 'curso' => $dataOK[$i]['curso']])->delete();
            RegisterArchive::Create($dataOK[$i]);
        }
    }
    function getCourses(Request $request, Response $response) : Response
    {
        switch($this->auth->user()->id_institucion)
        {
            case "01":
                $courses = Course::pascual()->get();
                break;
            case "02":
                $courses = Course::colegio()->get();
                break;
            case "03":
                $courses = Course::itm()->get();
                break;
            case  "04":
                $courses = Course::ruta()->get();
                break;
            default :
                $courses = Course::all();
                break;
        }
         try {
            $newResponse = $response->withHeader('Content-type', 'application/json');
            return $newResponse->withJson($courses, 200);
        } catch(\Exception $e) {
            return $response->withStatus(500)->write('0');
        }
    }

    protected function saveRegister(Request $request)
    {
        $register = new Register;
        $register->curso = $request->getParam('curso');
        $register->instancia = substr($request->getParam('curso'), 0, 1);
        $register->usuario = $request->getParam('usuario');
        $register->rol = $request->getParam('rol');
        $register->institucion_id = $request->getParam('institucion_id');
        return $register;
    }

}