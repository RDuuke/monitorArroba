<?php
namespace App\Controllers;

use App\Tools\Log;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Models\Student;
use App\Tools\Tools;
use App\Models\Permission;

class StudentController extends Controller
{
    protected $errors = [];
    protected $creators = [];
    protected $alerts = [];


    function store(Request $request, Response $response) : Response
    {
        try{
            $student = Student::create(array_map('trim', $request->getParams()));
            $student->clave = trim($request->getParam('documento'));
            $student->correo = trim($request->getParam('usuario'));
            $student->save();

            if ($student !== false) {
                $data_array = ["message" => 1, "user" => $student];
                $newResponse = $response->withHeader('Content-type', 'application/json');
                Log::i("El usuario_monitor " . $this->auth->user()->usuario . " registro al usuario_campus " . $student->usuario . " en " . Tools::getMessageModule(1));
                return $newResponse->withJson($data_array, 200);
            }
        }catch(\Exception $e) {
            Log::e("El usuario_monitor " . $this->auth->user()->usuario . " no registro al usuario_campus ".$student->usuario." por " +$e->getMessage() + " en " + Tools::getMessageModule(1));
            return $response->withStatus(500)->write($e->getMessage());
        }
    }

    function delete(Request $request, Response $response) : Response
    {
        $router = $request->getAttribute('route');
        $student= Student::find($router->getArguments()['id']);
        try {
            if ($student->registers->count() < 1) {

                if ($student->delete()) {
                    Log::i("El usuario_monitor " . $this->auth->user()->usuario . " elimino al usuario_campus " . $student->usuario . " en " . Tools::getMessageModule(1), 2);

                    return $response->withStatus(200)->write('1');
                }
            }
        } catch(\Exception $e) {
            Log::e("El usuario_monitor " . $this->auth->user()->usuario . " no elimino al usuario_campus ".$student->usuario." por " + $e->getMessage() + " en " + Tools::getMessageModule(1), 2);
            return $response->withStatus(500)->write('0');
        }
    }

    function show(Request $request, Response $response) : Response
    {
        $router = $request->getAttribute('route');
        $student= Student::find($router->getArguments()['id'])->toArray();
        try {
            $newResponse = $response->withHeader('Content-type', 'application/json');
            return $newResponse->withJson($student, 200);
        } catch(\Exception $e) {
            return $response->withStatus(500)->write('0');
        }
    }

    function update(Request $request, Response $response) : Response
    {
        $router = $request->getAttribute('route');
        $student = Student::updateOrCreate(['id' => $router->getArguments()['id']],array_map('trim',$request->getParams()));
        try{
            if ($student != false) {
                $data_array = ["message" => 2, "user" => $student];
                $newResponse = $response->withHeader('Content-type', 'application/json');
                Log::i("El usuario_monitor " . $this->auth->user()->usuario . " actualizo al usuario_campus " . $student->usuario . " en " . Tools::getMessageModule(1), 1);
                return $newResponse->withJson($data_array, 200);
            }
        }catch(\Exception $e) {
            Log::e("El usuario_monitor " . $this->auth->user()->usuario . " no actualizo al usuario_campus ".$student->usuario." por " + $e->getMessage() + " en " + Tools::getMessageModule(1), 1);
            return $response->withStatus(500)->write('0');
        }
    }
    function all(Request $request, Response $response) : Response
    {

            if($this->auth->user()->id_institucion == '01') {
                $students = Student::pascualbravo();
            } else if ($this->auth->user()->id_institucion == '02') {
                $students = Student::colegiomayor();
            } else if ($this->auth->user()->id_institucion == '03') {
                $students = Student::itm();
            } else if ($this->auth->user()->id_institucion == '04') {
                $students = Student::rutann();
            } else {
                $students = Student::all();
            }
            $newResponse = $response->withHeader('Content-type', 'application/json');
            return $newResponse->withJson($students, 200);
    }

    function upload(Request $request, Response $response)
    {
        $uploadedFiles = $request->getUploadedFiles();
        $archive = $uploadedFiles['archive'];

        if ($archive->getError() == UPLOAD_ERR_OK) {
            $filename = Tools::moveUploadedFile($archive, $this->tmp);
            if ($filename != false) {
                try {
                    $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
                    $reader->setReadDataOnly(true);
                    $spreadsheet = $reader->load($this->tmp . DS . $filename);
                    $worksheet = $spreadsheet->getActiveSheet();
                    $highestRow = $worksheet->getHighestDataRow();
                    for ($row=2; $row <= $highestRow; $row++) {
                        $data = [
                            "usuario" => trim($worksheet->getCell('A'. $row)->getvalue()),
                            "clave" => trim($worksheet->getCell('E'. $row)->getvalue()),
                            "nombres" => trim($worksheet->getCell('C'. $row)->getvalue()),
                            "apellidos" => trim($worksheet->getCell('D'. $row)->getvalue()),
                            "correo" => trim($worksheet->getCell('A'. $row)->getvalue()),
                            "documento" => trim($worksheet->getCell('E'. $row)->getvalue()),
                            "institucion" => trim($worksheet->getCell('F'. $row)->getvalue()),
                            "genero" => trim($worksheet->getCell('G'. $row)->getvalue()),
                            "ciudad" => trim($worksheet->getCell('H'. $row)->getvalue()),
                            "departamento" => trim($worksheet->getCell('I'. $row)->getvalue()),
                            "pais" => trim($worksheet->getCell('J'. $row)->getvalue()),
                            "telefono" => trim($worksheet->getCell('K'. $row)->getvalue()),
                            "celular" => trim($worksheet->getCell('L'. $row)->getvalue()),
                            "direccion" => trim($worksheet->getCell('M'. $row)->getvalue())
                        ];
                        if (!filter_var(trim($data['usuario']), FILTER_VALIDATE_EMAIL)) {
                            $data['message'] = Tools::getMessageUser(5);
                            $data['codigo'] = Tools::getCodigoUser(5);
                            array_push($this->errors, $data);
                            unset($data);
                            continue;
                        }
                        $student_document = Student::where('documento', $data['documento'])->get();

                        if ($student_document->count() == 1){
                            $filter = $student_document->where('usuario', $data['usuario']);
                            if ($filter->count() == 0){
                                $data['message'] = str_replace(':usuario', $student_document[0]->usuario, Tools::getMessageUser(4));
                                $data['codigo'] = Tools::getCodigoUser(4);
                                array_push($this->alerts, $data);
                                unset($data);
                                continue;
                            }
                        }
                        $student = Student::where('usuario', '=', $data['usuario'])->get();

                        if ($student->count() == 0) {
                            if($this->auth->user()->id_institucion == Tools::codigoMedellin() || Tools::getInstitutionForCodigo($this->user()->id_institucion == $data['institucion'])) {
                                $data['message'] = Tools::getMessageUser(0);
                                $data['codigo'] = Tools::getCodigoUser(0);
                                array_push($this->creators, $data);
                                //Student::create($data);
                            }else {
                                $data['message'] = Tools::getMessageUser(2);
                                $data['codigo'] = Tools::getCodigoUser(2);
                                array_push($this->errors, $data);;
                            }
                        } else {
                            $filter = $student->where('documento',$data['documento']);

                            if($filter->count() == 0) {
                                $data['message'] = str_replace(":documento", $student[0]->documento, Tools::getMessageUser(1));
                                $data['codigo'] = Tools::getCodigoUser(1);
                            }else {
                                $data['message'] = Tools::getMessageUser(3);
                                $data['codigo'] = Tools::getCodigoUser(3);
                            }
                            array_push($this->errors, $data);
                        }
                        unset($data);
                    }
                    $responseData = ['message' => 1, 'creators' => $this->creators, 'errors' => $this->errors, 'alerts' => $this->alerts,'totalr' => ($highestRow-1), 'totalc' => count($this->creators), 'totale' => count($this->errors), 'totala' => count($this->alerts)];
                    $newResponse = $response->withHeader('Content-type', 'application/json');
                    return $newResponse->withJson($responseData, 200);
                } catch(\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
                    die('Error loading file: '.$e->getMessage());
                }

            }
        }
        echo $archive->getError();
        return false;
    }

    function checkEmailStudents(Request $request, Response $response)
    {
        $newResponse = $response->withHeader('Content-type', 'application/json');
        if(Student::where('usuario', '=', $request->getParam('usuario'))->count() > 0 ) {
            return $newResponse->withJson(['message' => 1], 200);
        }
        return $newResponse->withJson(['message' => 0], 200);
    }

    function getDataForEmailStudents(Request $request, Response $response)
    {
        $newResponse = $response->withHeader('Content-type', 'application/json');
        $data = Student::where('usuario', '=', $request->getParam('usuario'))->get()->first();
        if($data != false) {
            return $newResponse->withJson($data, 200);
        }
        return $newResponse->withJson(['message' => 0, 'student' => null], 200);
    }

    function search(Request $request, Response $response)
    {

        $router = $request->getAttribute('route');
        $param = $router->getArguments()['params']. "%";

        if($this->auth->user()->id_institucion == '01') {
            Student::where("usuario","LIKE", $param)
                ->where("","Institución Universitaria Pascual Bravo")
                ->orWhere("documento", "LIKE", $param)
                ->get()->toArray();
        } else if ($this->auth->user()->id_institucion == '02') {
            Student::where("usuario","LIKE", $param)
                ->where("","Institución Universitaria Colegio Mayor de Antioquia")
                ->orWhere("documento", "LIKE", $param)
                ->get()->toArray();
        } else if ($this->auth->user()->id_institucion == '03') {
            Student::where("usuario","LIKE", $param)
                ->where("","Institución Universitaria ITM")
                ->orWhere("documento", "LIKE", $param)
                ->get()->toArray();
        } else if ($this->auth->user()->id_institucion == '04') {
            Student::where("usuario","LIKE", $param)
                ->where("","Ruta N")
                ->orWhere("documento", "LIKE", $param)
                ->get()->toArray();
        } else {
            $students = Student::where("usuario","LIKE", $param)
                ->orWhere("documento", "LIKE", $param)
                ->get()->toArray();
        }

        try {
            return $this->view->render($response, "_partials/search_student.twig", ["students" => $students]);
        } catch (\Exception $e) {
            return $response->withStatus(500)->write('0');
        }
    }

    function proccess(Request $request, Response $response)
    {
        $dataOK = $request->getParam('data');
        for($i = 0; $i < count($dataOK); $i++){
            Student::create($dataOK[$i]);
        }
    }
    function permissionAll(Request $request, Response $response){
        $router = $request->getAttribute('route');
        $permissions = Permission::where('user_id',"=",$router->getArguments()['id'])->get();
        return $this->view->render($response, "_partials/permission.twig", ['permissions' => $permissions]);
    }
    function permission(Request $request, Response $response)
    {
        $data = [];

        $dataOK = $request->getParams();
        $router = $request->getAttribute('route');
        $id = $router->getArguments()['id'];
        $writing  = isset($dataOK['writing']) ? $dataOK['writing'] : 0;
        $reading  = isset($dataOK['reading']) ? $dataOK['reading'] : 0;
        $data = ['permiso' => ($writing + $reading), 'modulo_id' => $dataOK['modules'], 'user_id' => $id];
        $permission = Permission::updateOrCreate(['modulo_id' => $dataOK['modules'], 'user_id' => $id], $data);
        $newResponse = $response->withHeader('Content-type', 'application/json');
        if ($permission != false){
            $message = ["status" => 1, "module" => $permission->module->nombre, "writing" => $writing, "reading" => $reading, "id" => $permission->id];
            return $newResponse->withJson($message, 200);
        }
        return "no";
    }

    function remove(Request $request, Response $response)
    {
        $newResponse = $response->withHeader('Content-type', 'application/json');
        $router = $request->getAttribute('route');
        $permission = Permission::find($router->getArguments()['id']);
        if ($permission->delete()){
            $message = ["status" => 1];
            return $newResponse->withJson($message, 200);
        }

    }
}