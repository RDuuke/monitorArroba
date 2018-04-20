<?php
namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use App\Models\Student;
use Illuminate\Contracts\Support\Responsable;

class StudentController extends Controller
{
    function store(Request $request, Response $response) : Response
    {
        try{
            $student = Student::create($request->getParams());
            if ($student !== false) {
                $data_array = ["message" => 1, "user" => $student];
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
        $student= Student::find($router->getArguments()['id']);
        try {

            if ($student->delete()) {
                return $response->withStatus(200)->write('1');
            }
        } catch(\Exception $e) {
            return $response->withStatus(500)->write('0');
        }
    }

    function show(Request $request, Response $response) : Response
    {
        $router = $request->getAttribute('route');
        $student= Student::find($router->getArguments()['id'])->toArray();
        try {
            $newResponse = $response->withHeader('Content-type', 'application/json');
            return $newResponse->withJson($student, 200);
        } catch(\Exception $e) {
            return $response->withStatus(500)->write('0');
        }
    }

    function update(Request $request, Response $response) : Response
    {
        $router = $request->getAttribute('route');
        $student = Student::updateOrCreate(['id' => $router->getArguments()['id']],$request->getParams());
        try{
            if ($student != false) {
                $data_array = ["message" => 2, "user" => $student];
                $newResponse = $response->withHeader('Content-type', 'application/json');
                return $newResponse->withJson($data_array, 200);
            }
        }catch(\Exception $e) {
            return $response->withStatus(500)->write('0');
        }
    }
    function all(Request $request, Response $response) : Response
    {

            $students = Student::getForInstitution($this->auth->getInstitution());
            $student = json_encode($json, JSON_FORCE_OBJECT);
            $newResponse = $response->withHeader('Content-type', 'application/json');
            return $newResponse->withJson($students, 200);
    }

    function upload(Request $request, Response $response)
    {
        echo '<pre>';
        $uploadedFiles = $request->getUploadedFiles();
        $archive = $uploadedFiles['archive'];
        if ($archive->getError() == UPLOAD_ERR_OK) {
            $this->moveUploadedFile($archive);
        }
        print_r($archive);
    }

    private function moveUploadedFile($uploadedFile) {
        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
        $basename = bin2hex(random_bytes(8)); // see http://php.net/manual/en/function.random-bytes.php
        $filename = sprintf('%s.%0.8s', $basename, $extension);

        $uploadedFile->moveTo($this->tmp . DIRECTORY_SEPARATOR . $filename);
    }
}