<?php
namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use App\Models\User;
use App\Models\Student;
use App\Tools\Log;
use App\Tools\Tools;


class UserController extends Controller
{
    public function all(Request $request, Response $response)
    {
        $users = User::all();
        $newResponse = $response->withHeader('Content-type', 'application/json');
        return $newResponse->withJson($users, 200);
    }

    public function store(Request $request, Response $response)
    {
        $data = ['message' => 0];
        $newResponse = $response->withHeader('Content-type', 'application/json');
        try {
            $user = User::create(array_map('trim', $request->getParams()));
            $user->clave = password_hash($request->getParam("documento"), PASSWORD_DEFAULT);
            $user->save();
            Log::i("usuario " . $this->auth->user()->usuario . " registro al " . $request->getParam('usuario') . " en el ". Tools::getMessageModule(0));
            $data = ['message' => 1, 'user' => $user];
            return $newResponse->withJson($data, 200);
        } catch ( \Exception $e ) {
            Log::e("usuario " . $this->auth->user()->usuario . " registro al " . $request->getParam('usuario') . " en el " . Tools::getMessageModule(0));
            return $newResponse->withJson($data, 200);

        }
    }

    public function show(Request $request, Response $response)
    {
        $router = $request->getAttribute('route');
        $user = User::find($router->getArguments()['id'])->toArray();
        try {
            $newResponse = $response->withHeader('Content-type', 'application/json');
            return $newResponse->withJson($user, 200);
        } catch(\Exception $e) {
            return $response->withStatus(500)->write('0');
        }
    }

    function update(Request $request, Response $response) : Response
    {
        $router = $request->getAttribute('route');
        $student = User::updateOrCreate(['id' => $router->getArguments()['id']],$request->getParams());
        try{
            if ($student != false) {
                $data_array = ["message" => 2, "user" => $student];
                Log::i("usuario " . $this->auth->user()->usuario . " actualizo al " . $request->getParam('usuario') . " en el " . Tools::getMessageModule(0), 1);
                $newResponse = $response->withHeader('Content-type', 'application/json');
                return $newResponse->withJson($data_array, 200);
            }
        }catch(\Exception $e) {
            Log::e("usuario " . $this->auth->user()->usuario . " actualizo al " . $request->getParam('usuario') . " en el " . Tools::getMessageModule(0), 1);
            return $response->withStatus(500)->write('0');
        }
    }


    function delete(Request $request, Response $response) : Response
    {
        $router = $request->getAttribute('route');
        $register= User::find($router->getArguments()['id']);
        try {
            if ($register->delete()) {
                Log::i("usuario " . $this->auth->user()->usuario . " elimino al " . $request->getParam('usuario') . " en el " . Tools::getMessageModule(0), 2);
                return $response->withStatus(200)->write('1');
            }
        } catch(\Exception $e) {
            Log::e("usuario " . $this->auth->user()->usuario . " elimino al " . $request->getParam('usuario') . " en el " . Tools::getMessageModule(0), 2);
            return $response->withStatus(500)->write('0');
        }
    }
}