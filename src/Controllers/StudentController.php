<?php
namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use App\Models\Student;
use App\Tools\Tools;

class StudentController extends Controller
{
    protected $errors = [];
    protected $creators = [];


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
                return $newResponse->withJson($data_array, 200);
            }
        }catch(\Exception $e) {
            return $response->withStatus(500)->write('0');
        }
    }

    function delete(Request $request, Response $response) : Response
    {
        $router = $request->getAttribute('route');
        $student= Student::find($router->getArguments()['id']);
        try {

            if ($student->delete()) {
                return $response->withStatus(200)->write('1');
            }
        } catch(\Exception $e) {
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
                return $newResponse->withJson($data_array, 200);
            }
        }catch(\Exception $e) {
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
                    $reader->setReadDataOnly(TRUE);
                    $spreadsheet = $reader->load($this->tmp . DS . $filename);
                    $worksheet = $spreadsheet->getActiveSheet();
                    $highestRow = $worksheet->getHighestRow(); // e.g. 10
                    for ($row=2; $row <= $highestRow; $row++) {
                        $data = [
                            "usuario" => trim($worksheet->getCell('A'. $row)->getvalue()),
                            "clave" => trim($worksheet->getCell('B'. $row)->getvalue()),
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
                        $student = Student::where([
                                                    ['usuario', '=', $data['usuario']]
                                                ])->get();
                        if ($student->count() == 0) {
                            if((strtolower($data['institucion']) == strtolower($this->auth->user()->institution->nombre) || ($this->auth->user()->institution->codigo == Tools::codigoMedellin()))) {
                                $data['message'] = Tools::getMessageUser(0);
                                array_push($this->creators, $data);
                                Student::create($data);
                            }else {
                                $data['message'] = Tools::getMessageUser(2);
                                array_push($this->errors, $data);
                            }
                        } else {
                            $filter = $student->where('documento', $data['documento']);
                            if($filter->count() == 0) {
                                $data['message'] = Tools::getMessageUser(1);
                            }else {
                                $data['message'] = Tools::getMessageUser(3);
                            }
                            array_push($this->errors, $data);
                        }
                        unset($data);
                    }
                    $responseData = ['message' => 1, 'creators' => $this->creators, 'errors' => $this->errors, 'totalr' => ($highestRow-1), 'totalc' => count($this->creators), 'totale' => count($this->errors)];
                    $newResponse = $response->withHeader('Content-type', 'application/json');
                    return $newResponse->withJson($responseData, 200);
                } catch(\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
                    die('Error loading file: '.$e->getMessage());
                }

            }
        }
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
}