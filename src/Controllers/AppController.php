<?php
namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use App\Models\Student;
use App\Models\Institution;

class AppController extends Controller
{

    public function index(Request $request,Response $response)
    {
        return $this->view->render($response, "index.twig");
    }

    public function home(Request $request,Response $response)
    {
        return $this->view->render($response, "home.twig");
    }

    public function users(Request $request,Response $response)
    {
        //$students = Student::getForInstitution($this->auth->getInstitution());
        //$institutions = Institution::all();
        return $this->view->render($response, "users.twig");
    }

    function upload(Request $request, Response $response)
    {
        return $this->view->render($response, "uploadusers.twig");
    }
}