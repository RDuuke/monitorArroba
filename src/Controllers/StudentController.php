<?php
namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use App\Models\Student;

class StudentController extends Controller
{
    function store(Request $request, Response $response)
    {
        try{
            if (Student::create($request->getParams())) {
                return $response->withStatus(200)->write('1');
            }
        }catch(\Exception $e) {
            return $response->withStatus(500)->write('0');
        }
    }

    function delete(Request $request, Response $response)
    {
        $router = $request->getAttribute('route');
        $student= Student::find($router->getArguments()['id']);
        try {

            if ($student->delete()) {
                return $response->withStatus(200)->write('1');
            }
        } catch(\Exception $e) {
            return $response->withStatus(500)->write('0');
        }
    }

}