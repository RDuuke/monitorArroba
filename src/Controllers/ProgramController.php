<?php

namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use App\Models\Program;

class ProgramController extends Controller
{
    function all(Request $request, Response $response)
    {
        $programs = Program::all();
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
            if ($programs->delete()) {
                //Log::i("usuario " . $this->auth->user()->usuario . " elimino al " . $request->getParam('usuario') . " en el " . Tools::getMessageModule(0), 2);
                return $response->withStatus(200)->write('1');
            }
        } catch(\Exception $e) {
            //Log::e("usuario " . $this->auth->user()->usuario . " elimino al " . $request->getParam('usuario') . " en el " . Tools::getMessageModule(0), 2);
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
                //Log::i("usuario " . $this->auth->user()->usuario . " actualizo al " . $request->getParam('usuario') . " en el " . Tools::getMessageModule(0), 1);
            $newResponse = $response->withHeader('Content-type', 'application/json');
            return $newResponse->withJson($data_array, 200);

        } catch (\Exception $e) {
            //Log::e("usuario " . $this->auth->user()->usuario . " actualizo al " . $request->getParam('usuario') . " en el " . Tools::getMessageModule(0), 1);
            return $response->withStatus(500)->write('0');
        }
    }
}