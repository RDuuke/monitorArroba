<?php
namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use App\Models\Institution;


class InstitutionController extends Controller
{
    function all (Request $request, Response $response)
    {
        $institutions = Institution::all();
        $newResponse = $response->withHeader('Content-type', 'application/json');
        return $newResponse->withJson($institutions, 200);
    }

    function store(Request $request, Response $response)
    {

        $data = ['message' => 0];
        $newResponse = $response->withHeader('Content-type', 'application/json');
        try {

            if (Institution::checkCodigo(trim($request->getParam('codigo')))) {
                $institution = Institution::create(array_map('trim', $request->getParams()));
                if ($request->isXhr()) {
                    $data = ['message' => 1, 'institution' => $institution];
                    return $newResponse->withJson($data, 200);
                }
                $this->flash->addMessage("creators", "Institución creada correctamente");
                return $response->withRedirect($this->router->pathFor("admin.institution.add"));
            } else {
                if ($request->isXhr()) {
                    return $response->withStatus(500)->write("El código ingresado ya esta en uso");
                } else {
                    $this->flash->addMessage("errors","El código ingresado ya esta en uso");
                    return $response->withRedirect($this->router->pathFor('admin.{.add'));
                }
            }
            return $newResponse->withJson($data, 200);
        } catch (\Exception $e) {
            if ($request->isXhr()) {
                $data['text'] = $e->getMessage();
                return $newResponse->withJson($data, 200);
            } else {
                $this->flash->addMessage("errors", $e->getMessage());
                return $response->withRedirect($this->router->pathFor('admin.institution.add'));
            }
        }
    }

    function show(Request $request, Response $response)
    {
        $router = $request->getAttribute('route');
        $institution = Institution::find($router->getArguments()['id'])->toArray();
        try {
            $newResponse = $response->withHeader('Content-type', 'application/json');
            return $newResponse->withJson($institution, 200);
        } catch (\Exception $e) {
            return $response->withStatus(500)->write('0');
        }
    }

    function delete(Request $request, Response $response) : Response
    {
        $router = $request->getAttribute('route');
        $institution = Institution::find($router->getArguments()['id']);
        try {
            if ($institution->programs->count() < 1) {

                if ($institution->delete()) {
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

    function update(Request $request, Response $response) : Response
    {
        $router = $request->getAttribute('route');
        try {
            $institution = Institution::updateOrCreate(['id' => $router->getArguments()['id']], $request->getParams());

            $data_array = ["message" => 2, "institution" => $institution];
                //Log::i("usuario " . $this->auth->user()->usuario . " actualizo al " . $request->getParam('usuario') . " en el " . Tools::getMessageModule(0), 1);
            $newResponse = $response->withHeader('Content-type', 'application/json');
            return $newResponse->withJson($data_array, 200);

        } catch (\Exception $e) {
            //Log::e("usuario " . $this->auth->user()->usuario . " actualizo al " . $request->getParam('usuario') . " en el " . Tools::getMessageModule(0), 1);
            return $response->withStatus(500)->write('0');
        }
    }
}