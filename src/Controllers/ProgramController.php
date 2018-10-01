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
            if ($this->auth->user()->id_institucion != Tools::codigoMedellin()) {
                $programs = Program::where('codigo_institucion', $this->auth->user()->id_institucion)->get();

            } else {
                $programs = Program::all();
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
                $program->fecha = date('Y-m-d h:i:s');
                if ($request->isXhr()) {
                    $data = ['message' => 1, 'program' => $program];
                    return $newResponse->withJson($data, 200);
                }
                $this->flash->addMessage("creators","Programa creado correctamente");
                return $response->withRedirect($this->router->pathFor("admin.program.add"));
            }else {
                if ($request->isXhr()) {
                    return $response->withStatus(500)->write("El código ingresado ya esta en uso");
                } else {
                    $this->flash->addMessage("errors","El código ingresado ya esta en uso");
                    return $response->withRedirect($this->router->pathFor('admin.program.add'));
                }
            }
        } catch (\Exception $e) {
            if ($request->isXhr()) {
                return $response->withStatus(500)->write($e->getMessage());
            } else {
                $this->flash->addMessage("errors", $e->getMessage());
                return $response->withRedirect($this->router->pathFor('admin.program.add'));
            }        }
    }

    function show(Request $request, Response $response)
    {
        $router = $request->getAttribute('route');
        $instance = Program::find($router->getArguments()['id'])->toArray();
        try {
            $newResponse = $response->withHeader('Content-type', 'application/json');
            return $newResponse->withJson($instance, 200);
        } catch (\Exception $e) {
            return $response->withStatus(500)->write($e->getMessage());
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
            return $response->withStatus(500)->write($programs->course->count());
        } catch(\Exception $e) {
            Log::e("usuario " . $this->auth->user()->usuario . " elimino al " . $programs->nombre . " en el " . Tools::getMessageModule(5), 2);
            return $response->withStatus(500)->write($e->getMessage());
        }
    }

    function update(Request $request, Response $response) : Response
    {
        $router = $request->getAttribute('route');
        try {
            $program = Program::updateOrCreate(['id' => $router->getArguments()['id']], $request->getParams());
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
            $programs = Program::where("nombre","LIKE", $param)->orWhere("codigo", "LIKE", $param)->where("codigo_institucion", $this->auth->user()->id_institucion)->get();
        } else {
            $programs = Program::where("nombre","LIKE", $param)->orWhere("codigo", "LIKE", $param)->get();
        }
        try {
            return $this->view->render($response, "_partials/search_program.twig", ['programs' => $programs]);
        } catch (\Exception $e) {
            return $response->withStatus(500)->write($e->getMessage());
        }
    }
}