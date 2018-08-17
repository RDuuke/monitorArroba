<?php

namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use App\Models\Student;


class ApiController extends Controller
{

    function alluser(Request $request, Response $response)
    {
        $user = Student::all()->toJson();

        $newResponse = $response->withHeader('Content-type', 'application/json');

        return $newResponse->withJson(["codigo" => 1, "message" => "All student", "students" => $user], 200);

    }
}