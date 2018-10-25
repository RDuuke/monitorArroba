<?php

namespace App\Controllers;


use App\Models\Student;
use App\Tools\Tools;
use Illuminate\Database\Capsule\Manager;
use Slim\Http\Response;
use Slim\Http\Request;

class ReportController extends Controller
{

    function filterForMonth(Request $request, Response $response, $args)
    {
        /*$firstDate = date("Y") . "-" . date("m") . "-" . "1";
        $lastDate = date("Y") . "-" . date("m") . "-" . "31";

        $data = Manager::table("usuario")
            ->join("institucion", "usuario.institucion_id", "=", "institucion.codigo")
            ->select("institucion.nombre", Manager::raw("COUNT(institucion.codigo) AS cantidad"))
            ->whereBetween("usuario.fecha", [$firstDate, $lastDate])
            ->groupBy("institucion.nombre")
            ->get();
        print_r($data->all());*/
        $dataTable = Tools::getDataGeneralForMonth(1, $args['incial'], $args['final']);
        return $this->view->render($response, "_partials/report.twig", ["data_table" => $dataTable]);

    }

    function studentForMonth (Request $request, Response $response)
    {
        $firstDate = date("Y") . "-" . date("m") . "-" . "1";
        $lastDate = date("Y") . "-" . date("m") . "-" . "31";
        $data = Tools::studentData(1, $firstDate, $lastDate);
        $newResponse = $response->withHeader('Content-type', 'application/json');

        return $newResponse->withJson($data, 200);
    }

    function consolidated(Request $request, Response $response)
    {
        $data = Tools::studentData();

        $newResponse = $response->withHeader('Content-type', 'application/json');

        return $newResponse->withJson($data, 200);
    }
}