<?php
namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use App\Models\Course;
use App\Models\Program;


class CourseController extends Controller
{
    function all(Request $request, Response $response)
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
        $newResponse = $response->withHeader('Content-type', 'application/json');
        return $response->withJson($courses, 200);
    }

    function store(Request $request, Response $response)
    {
        $data = ['message' => 0];
        $newResponse = $response->withHeader('Content-type', 'application/json');
        try {
            $codigo_evaluate =  $request->getParam('programa') . $request->getParam('codigo');
            if (Course::checkCodigo(trim($codigo_evaluate))) {
                $course = Course::create(array_map('trim', $request->getParams()));
                $course->codigo = $codigo_evaluate;
                $course->save();
                $data = ['message' => 1, 'course' => $course];
                return $newResponse->withJson($data, 200);
            }
            return $newResponse->withJson($data, 200);
        } catch (\Exception $e) {
            return $newResponse->withJson($data, 200);
        }
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
                    //Log::i("usuario " . $this->auth->user()->usuario . " elimino al " . $request->getParam('usuario') . " en el " . Tools::getMessageModule(0), 2);
                    return $response->withStatus(200)->write('1');
                }
            }
            return $response->withStatus(500)->write('0');
        } catch(\Exception $e) {
            //Log::e("usuario " . $this->auth->user()->usuario . " elimino al " . $request->getParam('usuario') . " en el " . Tools::getMessageModule(0), 2);
            return $response->withStatus(500)->write('0');
        }
    }

    function update(Request $request, Response $response)
    {
        $router = $request->getAttribute('route');
        try {
            $codigo_evaluate = $request->getParam('programa') . $request->getParam('codigo');
            $course = Course::updateOrCreate(['id' => $router->getArguments()['id']], $request->getParams());
            if ($codigo_evaluate != $course->codigo) {
                if (Course::checkCodigo($codigo_evaluate)) {
                    $course->codigo = $codigo_evaluate;
                    $course->save();
                    $data_array = ["message" => 2, "course" => $course];
                } else {
                    $data_array = ["message" => 4, "course" => $course];
                }
                $newResponse = $response->withHeader('Content-type', 'application/json');
                return $newResponse->withJson($data_array, 200);
            }
            $newResponse = $response->withHeader('Content-type', 'application/json');
            return $newResponse->withJson($data_array, 200);
                //Log::i("usuario " . $this->auth->user()->usuario . " actualizo al " . $request->getParam('usuario') . " en el " . Tools::getMessageModule(0), 1);

        } catch (\Exception $e) {
            //Log::e("usuario " . $this->auth->user()->usuario . " actualizo al " . $request->getParam('usuario') . " en el " . Tools::getMessageModule(0), 1);
            return $response->withStatus(500)->write('0');
        }
    }

    function search(Request $request, Response $response)
    {
        #TODO Cursos por institucion
        #TODO Consultar por codigo
        $router = $request->getAttribute('route');
        $param = $router->getArguments()['params']. "%";
        $courses = Course::where("nombre","LIKE", $param)->get()->toArray();
        try {
            $newResponse = $response->withHeader('Content-type', 'application/json');
            return $newResponse->withJson($courses, 200);
        } catch (\Exception $e) {
            return $response->withStatus(500)->write('0');
        }
    }
}