<?php
namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use App\Models\Instance;
use App\Models\Institution;
use App\Tools\Tools;
use App\Tools\Log;


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
                //$this->writeArchiveDatabase($request);
                $instance = Instance::create(array_map('trim', $request->getParams()));
                Log::i("usuario " . $this->auth->user()->usuario . " creo la " . $instance->nombre . " en el " . Tools::getMessageModule(3), 0);
                if($request->isXhr()) {
                    $data = ['message' => 1, 'instance' => $instance];
                    return $newResponse->withJson($data, 200);
                }
                $this->flash->addMessage("creators", "Instancia creada correctamente");
                return $response->withRedirect($this->router->pathFor("admin.instance.add"));
            } else {
                if ($request->isXhr()) {
                    return $response->withStatus(500)->write($data);
                } else {
                    $this->flash->addMessage("errors","El código ingresado ya esta en uso");
                    return $response->withRedirect($this->router->pathFor('admin.instance.add'));
                }
            }
        } catch (\Exception $e) {
            Log::i("usuario " . $this->auth->user()->usuario . " creo la " . $instance->nombre . " en el " . Tools::getMessageModule(3), 0);
            if ($request->isXhr()) {
                return $response->withStatus(500)->write($e->getMessage());
            } else {
                $this->flash->addMessage("errors", $e->getMessage());
                return $response->withRedirect($this->router->pathFor('admin.instance.add'));
            }
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
            Log::i("usuario " . $this->auth->user()->usuario . " actualizo la " . $instance->nombre . " en el " . Tools::getMessageModule(3), 1);
            $newResponse = $response->withHeader('Content-type', 'application/json');
            return $newResponse->withJson($data_array, 200);

        } catch (\Exception $e) {
            Log::e("usuario " . $this->auth->user()->usuario . " actualizo al " . $request->getParam('usuario') . " en el " . Tools::getMessageModule(3), 1);
            return $response->withStatus(500)->write('0');
        }
    }

    function delete(Request $request, Response $response) : Response
    {
        $router = $request->getAttribute('route');
        $instance= Instance::find($router->getArguments()['id']);
        try {
            if(Institution::checkinstance($instance->codigo)) {
                if ($instance->delete()) {
                    Log::i("usuario " . $this->auth->user()->usuario . " elimino al " . $instance->nombre . " en el " . Tools::getMessageModule(3), 2);
                    return $response->withStatus(200)->write('1');
                }
            }
            return $response->withStatus(500)->write('0');
        } catch(\Exception $e) {
            Log::e("usuario " . $this->auth->user()->usuario . " elimino al " . $instance->nombre . " en el " . Tools::getMessageModule(3), 2);
            return $response->withStatus(500)->write('0');
        }
    }

    protected function writeArchiveDatabase(Request $request)
    {
        $database = BASE_DIR . "bootstrap" . DS . "database.php";
        if (is_writable($database)) {
            if ($archive = fopen($database, "a+")) {
                $contenido = fread($archive, filesize($database));
                fclose($archive);
                $array = explode(PHP_EOL, $contenido);
                $index = (count($array) - 1);
                $array[$index - 1] = "          'db_" . $request->getParam('nombre') . "' => [";
                $array[$index] = "              'driver' => 'mysql',";
                $array[$index + 1] = "              'host' => '10.0.4.30',";
                $array[$index + 2] = "              'database' => '". $request->getParam('bd') . "',";
                $array[$index + 3] = "              'username' => 'arrobamedellin',";
                $array[$index + 4] = "              'password' => 'vex3SP83xLeGQFNZ',";
                $array[$index + 5] = "              'charset' => 'utf8',";
                $array[$index + 6] = "              'collation' => 'utf8_unicode_ci',";
                $array[$index + 7] = "              'prefix' => '',";
                $array[$index + 8] = "              'options' => [\PDO::ATTR_EMULATE_PREPARES => true],";
                $array[$index + 9] = "          ],";
                $array[$index + 10] = "     ]";
                $array[$index + 11] = "];";

                $contenido = implode(PHP_EOL, $array);
                $archive = fopen($database, 'w');
                fwrite($archive, $contenido);
                fclose($archive);
            }
        }
    }
}