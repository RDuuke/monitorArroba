<?php

namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use App\Models\Program;
use App\Tools\Log;
use App\Tools\Tools;

class ProgramController extends Controller
{
    function all(Request $request, Response $response)
    {
            switch($this->auth->user()->id_institucion)
            {
                case "01":
                    $programs = Program::pascual();
                    break;
                case "02":
                    $programs = Program::colegio();
                    break;
                case "03":
                    $programs = Program::itm();
                break;
                case  "04":
                    $programs = Program::ruta();
                break;
                default :
                    $programs = Program::all();
                break;
            }
            $newResponse = $response->withHeader('Content-type', 'application/json');
        return $newResponse->withJson($programs, 200);
    }

    function store(Request $request, Response $response)
    {

        $data = ['message' => 0];
        $newResponse = $response->withHeader('Content-type', 'application/json');
        try {
            $codigo_evaluate = $request->getParam('instance') . $request->getParam('codigo_institucion') . $request->getParam('codigo_program');
            if (Program::checkCodigo(trim($codigo_evaluate))) {
                $program = Program::create(array_map('trim', $request->getParams()));
                $program->codigo = $codigo_evaluate;
                $program->save();
                $data = ['message' => 1, 'program' => $program];
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
        $instance = Program::find($router->getArguments()['id'])->toArray();
        try {
            $newResponse = $response->withHeader('Content-type', 'application/json');
            return $newResponse->withJson($instance, 200);
        } catch (\Exception $e) {
            return $response->withStatus(500)->write('0');
        }
    }

    function delete(Request $request, Response $response) : Response
    {
        $router = $request->getAttribute('route');
        $programs= Program::find($router->getArguments()['id']);
        try {
            if ($programs->course->count() < 1) {

                if ($programs->delete()) {
                    Log::i("usuario " . $this->auth->user()->usuario . " elimino al " . $programs->nombre . " en el " . Tools::getMessageModule(5), 2);
                    return $response->withStatus(200)->write('1');
                }
            }
            return $response->withStatus(500)->write('0');
        } catch(\Exception $e) {
            Log::e("usuario " . $this->auth->user()->usuario . " elimino al " . $programs->nombre . " en el " . Tools::getMessageModule(5), 2);
            return $response->withStatus(500)->write('0');
        }
    }

    function update(Request $request, Response $response) : Response
    {
        $router = $request->getAttribute('route');
        try {
            $codigo_evaluate = $request->getParam('instance') . $request->getParam('codigo_institucion') . $request->getParam('codigo_program');
            $program = Program::updateOrCreate(['id' => $router->getArguments()['id']], $request->getParams());
            $program->codigo = $codigo_evaluate;
            $program->save();
            $data_array = ["message" => 2, "program" => $program];
            Log::i("usuario " . $this->auth->user()->usuario . " actualizo al " . $request->getParam('usuario') . " en el " . Tools::getMessageModule(0), 1);
            $newResponse = $response->withHeader('Content-type', 'application/json');
            return $newResponse->withJson($data_array, 200);

        } catch (\Exception $e) {
            Log::e("usuario " . $this->auth->user()->usuario . " actualizo al " . $request->getParam('usuario') . " en el " . Tools::getMessageModule(0), 1);
            return $response->withStatus(500)->write('0');
        }
    }

    function search(Request $request, Response $response)
    {
        $router = $request->getAttribute('route');
        $param = $router->getArguments()['params']. "%";
        if ($this->auth->user()->id_institucion != "05") {
            $programs = Program::where("nombre","LIKE", $param)->orWhere("codigo", "LIKE", $param)->where("codigo_institucion", $this->auth->user()->id_institucion)->get()->toArray();
        } else {
            $programs = Program::where("nombre","LIKE", $param)->orWhere("codigo", "LIKE", $param)->get()->toArray();
        }
        try {
            $newResponse = $response->withHeader('Content-type', 'application/json');
            return $newResponse->withJson($programs, 200);
        } catch (\Exception $e) {
            return $response->withStatus(500)->write('0');
        }
    }
}