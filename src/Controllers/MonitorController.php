<?php

namespace App\Controllers;


use App\Models\CorreoMonitoreo;
use App\Models\Monitor;
use App\Models\MonitorRegistro;
use Carbon\Carbon;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Eloquent\JsonEncodingException;
use Slim\Http\Request;
use Slim\Http\Response;

class MonitorController extends Controller
{

    function store(Request $request, Response $response){
        Monitor::create($request->getParams());
        return $response->withRedirect($this->router->pathFor("admon.monitoreo"));
    }

    function all(Request $request, Response $response)
    {
        $monitores = Monitor::all();
        $newResponse = $response->withHeader('Content-type', 'application/json');
        return $newResponse->withJson($monitores, 200);
    }

    function delete(Request $request, Response $response, $args)
    {
        $newResponse = $response->withHeader('Content-type', 'application/json');
        if (Monitor::find($args["id"])->delete()) {
            return $newResponse->withJson(
                [
                    "codigo" => 1,
                    "message" => "Monitor eliminado correctamente"
                ],
                200);
        }

        return $newResponse->withJson(
        [
            "codigo" => 0,
            "message" => "Errror al eliminar el monitor"
        ],
        200);
    }

    function show(Request $request, Response $response, $args)
    {
        $newResponse = $response->withHeader('Content-type', 'application/json');
        $monitor = Monitor::find($args["id"]);
        if ($monitor instanceof Monitor) {
            return $newResponse->withJson(
                [
                    "codigo" => 1,
                    "message" => "Monitor visualizado correctamente",
                    "data" => $monitor
                ],
                200);
        }

        return $newResponse->withJson(
            [
                "codigo" => 0,
                "message" => "Errror al traer el monitor",
                "data" => []
            ],
            200);
    }

    function update(Request $request, Response $response, $args){
        $monitor = Monitor::find($args['id']);
        $monitor->name = $request->getParam("name");
        $monitor->url = $request->getParam("url");
        $monitor->interval = $request->getParam("interval");
        $monitor->type = $request->getParam("type");
        $monitor->save();
        return $response->withRedirect($this->router->pathFor("admon.monitoreo"));
    }

    function PausePlay(Request $request, Response $response, $args)
    {
        $newResponse = $response->withHeader('Content-type', 'application/json');
        $monitor = Monitor::find($args['id']);
        $monitor->status = ($monitor->status == 1) ? 0 : 1;
        if ($monitor->save()) {
            return $newResponse->withJson(
                [
                    "codigo" => 1,
                    "message" => "Acción ejecutada correctamente",
                ],
                200);
        }
        return $newResponse->withJson(
            [
                "codigo" => 1,
                "message" => "Error al ejecutar la acción",
            ],
            200);
    }
    function reset(Request $request, Response $response, $args)
    {
        $newResponse = $response->withHeader('Content-type', 'application/json');
        if (MonitorRegistro::where("monitor_id", $args['id'])->delete()) {
            return $newResponse->withJson(
                [
                    "codigo" => 1,
                    "message" => "Acción ejecutada correctamente",
                ],
                200);
        }
        return $newResponse->withJson(
            [
                "codigo" => 1,
                "message" => "Error al ejecutar la acción",
            ],
            200);
    }


    function detailsData(Request $request, Response $response, $args)
    {
        $newResponse = $response->withHeader('Content-type', 'application/json');

        $labels = MonitorRegistro::select(Manager::raw("DISTINCT DATE_FORMAT(created_at, '%H:%i:%m') AS labels"))->where("monitor_id", "=", $args['id'])
            ->whereBetween("created_at", [Carbon::today()->subDay(1), Carbon::today()->addDay()])->get();

        $monitores = MonitorRegistro::select("time")->where("monitor_id", "=", $args['id'])
            ->whereBetween("created_at", [Carbon::today()->subDay(1), Carbon::today()->addDay()])->get();

        return $newResponse->withJson([
            "labels" => $labels,
            "dataset" => $monitores
        ], 200);
    }

    function storeEmail (Request $request, Response $response) {
        CorreoMonitoreo::create($request->getParams());
        return $response->withRedirect($this->router->pathFor("admon.monitoreo"));
    }
    function allEmail (Request $request, Response $response) {
        $correos = CorreoMonitoreo::all();
        $newResponse = $response->withHeader('Content-type', 'application/json');
        return $newResponse->withJson($correos, 200);
    }
    function showEmail(Request $request, Response $response, $args)
    {
        $newResponse = $response->withHeader('Content-type', 'application/json');
        $correo = CorreoMonitoreo::find($args["id"]);
        if ($correo instanceof CorreoMonitoreo) {
            return $newResponse->withJson(
                [
                    "codigo" => 1,
                    "message" => "Correo visualizado correctamente",
                    "data" => $correo
                ],
                200);
        }

        return $newResponse->withJson(
            [
                "codigo" => 0,
                "message" => "Error al traer el correo",
                "data" => []
            ],
            200);
    }

    function updateEmail(Request $request, Response $response, $args){
        $monitor = CorreoMonitoreo::find($args['id']);
        $monitor->correo = $request->getParam("correo");
        $monitor->estado = $request->getParam("estado");
        $monitor->save();
        return $response->withRedirect($this->router->pathFor("admon.monitoreo"));
    }

    function deleteEmail(Request $request, Response $response, $args)
    {
        $newResponse = $response->withHeader('Content-type', 'application/json');
        if (CorreoMonitoreo::find($args["id"])->delete()) {
            return $newResponse->withJson(
                [
                    "codigo" => 1,
                    "message" => "Correo eliminado correctamente"
                ],
                200);
        }

        return $newResponse->withJson(
            [
                "codigo" => 0,
                "message" => "Error al eliminar el correo"
            ],
            200);
    }

}