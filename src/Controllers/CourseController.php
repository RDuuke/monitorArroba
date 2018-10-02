<?php
namespace App\Controllers;

use App\Tools\Log;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Models\Course;
use App\Models\Program;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Respect\Validation\Exceptions\PhpLabelException;
use App\Tools\Tools;


class CourseController extends Controller
{
    protected $creators = [];
    protected $errors = [];

    function all(Request $request, Response $response)
    {
        switch($this->auth->user()->id_institucion)
            {
                case Tools::codigoPascualBravo():
                    $courses = Course::pascual()->get();

                    break;
                case Tools::codigoColegioMayor():
                    $courses = Course::colegio()->get();
                    break;
                case Tools::codigoITM():
                    $courses = Course::itm()->get();
                break;
                case Tools::codigoRutaN():
                    $courses = Course::ruta()->get();
                break;
                case Tools::codigoMujeres():
                    $courses = Course::mujeres()->get();
                break;
                default :
                    $courses = Course::all();
                break;
            }
        $newResponse = $response->withHeader('Content-type', 'application/json');
        return $newResponse->withJson($courses, 200);
    }

    function store(Request $request, Response $response)
    {
        $data = ['message' => 0];
        $newResponse = $response->withHeader('Content-type', 'application/json');
        try {
            $codigo_evaluate =  $request->getParam('id_programa') . $request->getParam('codigo');
            if (Course::checkCodigo(trim($codigo_evaluate))) {
                $course = Course::create(array_map('trim', $request->getParams()));
                $course->codigo = $codigo_evaluate;
                $course->save();
                Log::i(Tools::getMessageCreaterRegisterModule(Tools::codigoCursos, $this->auth->user()->usuario,  $request->getParam('nombre')), Tools::getTypeCreatorAction());
                if ($request->isXhr()) {
                    $data = ['message' => 1, 'course' => $course];
                    return $newResponse->withJson($data, 200);
                }
                $this->flash->addMessage("creators", "Curso creado correctamente");
                return $response->withRedirect($this->router->pathFor("admin.course.add"));
            } else {
                if ($request->isXhr()) {
                    return $response->withStatus(500)->write("El código ingresado ya esta en uso");
                } else {
                    $this->flash->addMessage("errors","El código ingresado ya esta en uso");
                    return $response->withRedirect($this->router->pathFor('admin.course.add'));
                }
            }
        } catch (\Exception $e) {
            Log::e(Tools::getMessageCreaterRegisterModule(Tools::codigoCursos, $this->auth->user()->usuario, $request->getParam('nombre')), Tools::getTypeCreatorAction());
            if ($request->isXhr()) {
                return $response->withStatus(500)->write($e->getMessage());
            } else {
                $this->flash->addMessage("errors", $e->getMessage());
                return $response->withRedirect($this->router->pathFor('admin.course.add'));
            }        }
    }

    function show(Request $request, Response $response)
    {
        $router = $request->getAttribute('route');
        $courses = Course::find($router->getArguments()['id'])->toArray();
        $c = Course::find($router->getArguments()['id']);
        $flag = $c->registers->count();
        $data = ['courses' => $courses, 'flag' => $flag];
        try {
            $newResponse = $response->withHeader('Content-type', 'application/json');
            return $newResponse->withJson($data, 200);
        } catch (\Exception $e) {
            return $response->withStatus(500)->write('0');
        }
    }

    function delete(Request $request, Response $response)
    {
        $router = $request->getAttribute('route');
        $courses= Course::find($router->getArguments()['id']);
        try {

            if($courses->registers->count() < 1) {

                if ($courses->delete()) {
                    Log::i(Tools::getMessageDeleteRegisterModule(Tools::codigoCursos, $this->auth->user()->usuario, $courses->nombre), Tools::getTypeDeleteAction());
                    return $response->withStatus(200)->write('1');
                }
            }
            return $response->withStatus(500)->write('0');
        } catch(\Exception $e) {
            Log::e(Tools::getMessageDeleteRegisterModule(Tools::codigoCursos, $this->auth->user()->usuario, $courses->nombre), Tools::getTypeDeleteAction());
            return $response->withStatus(500)->write('0');
        }
    }

    function update(Request $request, Response $response)
    {
        $router = $request->getAttribute('route');
        try {
            $codigo_evaluate = $request->getParam('id_programa') . $request->getParam('codigo');
            $course = Course::updateOrCreate(['id' => $router->getArguments()['id']], $request->getParams());
            if ($codigo_evaluate != $course->codigo) {
                if (Course::checkCodigo($codigo_evaluate)) {
                    $course->codigo = $codigo_evaluate;
                    $course->save();
                    Log::i(Tools::getMessageUpdateRegisterModule(Tools::codigoCursos, $this->auth->user()->usuario, $course->nombre), Tools::getTypeUpdateAction());
                    $data_array = ["message" => 2, "course" => $course];
                } else {
                    $data_array = ["message" => 4, "course" => $course];
                }
                $newResponse = $response->withHeader('Content-type', 'application/json');
                return $newResponse->withJson($data_array, 200);
            }
            $data_array = ["message" => 4, "course" => $course];
            $newResponse = $response->withHeader('Content-type', 'application/json');
            return $newResponse->withJson($data_array, 200);
                //Log::i("usuario " . $this->auth->user()->usuario . " actualizo al " . $request->getParam('usuario') . " en el " . Tools::getMessageModule(0), 1);

        } catch (\Exception $e) {
            Log::e(Tools::getMessageUpdateRegisterModule(Tools::codigoCursos, $this->auth->user()->usuario,$request->getParam('nombre')), Tools::getTypeUpdateAction());
            return $response->withStatus(500)->write('0');
        }
    }

    function search(Request $request, Response $response)
    {
        $router = $request->getAttribute('route');
        $param = $router->getArguments()['params'] . "%";

        switch($this->auth->user()->id_institucion)
        {
                case Tools::codigoPascualBravo():
                    $all_courses = Course::pascual()->where("nombre","LIKE", $param)->orwhere("codigo", "LIKE", $param)->get()->toArray();
                    break;
                case Tools::codigoColegioMayor():
                    $all_courses = Course::colegio()->where("nombre","LIKE", $param)->orwhere("codigo","LIKE", $param)->get();
                    break;
                case Tools::codigoITM():
                    $all_courses = Course::itm()->where("nombre","LIKE", $param)->orwhere("codigo","LIKE", $param)->get();
                    break;
                case Tools::codigoRutaN():
                    $all_courses = Course::ruta()->where("nombre","LIKE", $param)->orwhere("codigo","LIKE", $param)->get();
                default :
                    $all_courses = Course::where("nombre","LIKE", $param)->orwhere("codigo","LIKE", $param)->get()->toArray();
                    break;
            }
            $courses = $all_courses;

        try {
            return $this->view->render($response, "_partials/search_course.twig", ["courses" => $courses]);
        } catch (\Exception $e) {
            return $response->withStatus(500)->write('0');
        }
    }

    function upload(Request $request, Response $response)
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
                    $highestRow = $worksheet->getHighestDataRow();
                    for ($row = 2; $row <= $highestRow; $row++) {
                        $data = [
                            "id_programa" => substr(trim($worksheet->getCell('A' . $row)->getvalue()), 0 , 5),
                            "codigo" => trim($worksheet->getCell('A' . $row)->getvalue()),
                            "nombre" => trim($worksheet->getCell('B' . $row)->getvalue()),
                        ];
                        if (! Program::checkCodigo($data['id_programa'])) {
                            if (Course::checkCodigo($data['codigo'])) {
                                $data['message'] = str_replace(":codigo", $data['codigo'], Tools::getMessageCourse(2));
                                $data['codigo_proccess'] = Tools::getCodigoCourse(2);
                                array_push($this->creators, $data);
                                unset($data);
                                continue;
                            }
                            $data['message'] = str_replace(":codigo", $data['codigo'], Tools::getMessageCourse(1));
                            $data['codigo_proccess'] = Tools::getCodigoCourse(1);
                            array_push($this->errors, $data);
                            unset($data);
                            continue;
                        }
                        $data['message'] = str_replace(":codigo", $data['id_programa'], Tools::getMessageCourse(0));
                        $data['codigo_proccess'] = Tools::getCodigoCourse(0);
                        array_push($this->errors, $data);
                        unset($data);
                    }
                    $responseData = ['message' => 1, 'totalR' => ($highestRow - 1), 'totalC' => count($this->creators), 'totalE' => count($this->errors), 'creators' => $this->creators, 'errors' => $this->errors];
                    $newResponse = $response->withHeader('Content-type', 'application/json');
                    return $newResponse->withJson($responseData, 200);
                } catch ( PhpLabelException $exception) {
                    die( "Error :" . $exception->getMessage());
                }
            }
        }
        return false;
    }

    function proccess(Request $request, Response $response)
    {
        $dataOK = $request->getParam('data');
        for($i = 0; $i < count($dataOK); $i++){
            Course::updateOrCreate(['codigo' => $dataOK[$i]['codigo']], $dataOK[$i]);
        }
    }
}