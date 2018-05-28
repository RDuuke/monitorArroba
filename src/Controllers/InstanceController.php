<?php
namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use App\Models\Instance;


class InstanceController extends Controller
{
    function all(Request $request, Response $response)
    {
        $instance = Instance::all();
        $newResponse = $response->withHeader('Content-type', 'application/json');
        return $newResponse->withJson($instance, 200);
    }

    function store(Request $request, Response $response)
    {

        $data = ['message' => 0];
        $newResponse = $response->withHeader('Content-type', 'application/json');
        try {

            if (Instance::checkCodigo(trim($request->getParam('codigo')))) {
                $instance = Instance::create(array_map('trim', $request->getParams()));
                $data = ['message' => 1, 'instance' => $instance];
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
        $instance = Instance::find($router->getArguments()['id'])->toArray();
        try {
            $newResponse = $response->withHeader('Content-type', 'application/json');
            return $newResponse->withJson($instance, 200);
        } catch (\Exception $e) {
            return $response->withStatus(500)->write('0');
        }
    }

    function update(Request $request, Response $response) : Response
    {
        $router = $request->getAttribute('route');
        try {
            $instance = Instance::updateOrCreate(['id' => $router->getArguments()['id']], $request->getParams());

            $data_array = ["message" => 2, "instance" => $instance];
                //Log::i("usuario " . $this->auth->user()->usuario . " actualizo al " . $request->getParam('usuario') . " en el " . Tools::getMessageModule(0), 1);
            $newResponse = $response->withHeader('Content-type', 'application/json');
            return $newResponse->withJson($data_array, 200);

        } catch (\Exception $e) {
            //Log::e("usuario " . $this->auth->user()->usuario . " actualizo al " . $request->getParam('usuario') . " en el " . Tools::getMessageModule(0), 1);
            return $response->withStatus(500)->write('0');
        }
    }

    function delete(Request $request, Response $response) : Response
    {
        $router = $request->getAttribute('route');
        $instance= Instance::find($router->getArguments()['id']);
        try {
            if ($instance->delete()) {
                //Log::i("usuario " . $this->auth->user()->usuario . " elimino al " . $request->getParam('usuario') . " en el " . Tools::getMessageModule(0), 2);
                return $response->withStatus(200)->write('1');
            }
        } catch(\Exception $e) {
            //Log::e("usuario " . $this->auth->user()->usuario . " elimino al " . $request->getParam('usuario') . " en el " . Tools::getMessageModule(0), 2);
            return $response->withStatus(500)->write('0');
        }
    }
}