<?php
namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use App\Models\Student;
use App\Models\Institution;
use App\Models\Rol;

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

    public function students(Request $request,Response $response)
    {
        //$students = Student::getForInstitution($this->auth->getInstitution());
        //$institutions = Institution::all();
        return $this->view->render($response, "student.twig");
    }

    public function users(Request $request, Response $response)
    {
        $rols = Rol::all();
        $institutions =Institution::all();
        return $this->view->render($response, "user.twig", ['roles' => $rols, "instituciones" => $institutions]);
    }

    public function registers(Request $request, Response $response)
    {
        return $this->view->render($response, "register.twig");
    }

    public function instance(Request $request, Response $response)
    {
        return $this->view->render($response, "instance.twig");
    }

    function upload_students(Request $request, Response $response)
    {
        return $this->view->render($response, "uploadusers.twig");
    }

    function upload_registers(Request $request, Response $response)
    {
        return $this->view->render($response, "uploadregister.twig");
    }
}