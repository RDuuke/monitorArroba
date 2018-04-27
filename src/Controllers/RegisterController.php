<?php

namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use App\Models\Register;

class RegisterController extends Controller
{
    function all(Request $request, Response $response) : Response
    {

            $registers = Register::all()->toArray();
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
            $data_array = ["message" => 3, "register" => null];
            $newResponse = $response->withHeader('Content-type', 'application/json');
            return $newResponse->withJson($data_array, 200);
        }
        try{
            $register = Register::create($request->getParams());
            if ($register !== false) {
                $data_array = ["message" => 1, "register" => $register];
                $newResponse = $response->withHeader('Content-type', 'application/json');
                return $newResponse->withJson($data_array, 200);
            }
        }catch(\Exception $e) {
            return $response->withStatus(500)->write('0');
        }
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
        $router = $request->getAttribute('route');
        $register = Register::updateOrCreate(['id' => $router->getArguments()['id']],$request->getParams());
        try{
            if ($register != false) {
                $data_array = ["message" => 2, "register" => $register];
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
        $register= Register::find($router->getArguments()['id']);
        try {

            if ($register->delete()) {
                return $response->withStatus(200)->write('1');
            }
        } catch(\Exception $e) {
            return $response->withStatus(500)->write('0');
        }
    }

}