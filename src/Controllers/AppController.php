<?php
namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use App\Models\Student;
use App\Models\Institution;
use App\Models\Rol;
use App\Models\Instance;
use App\Models\Program;
use App\Models\Course;
use App\Models\Register;

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

    public function program(Request $request, Response $response)
    {
        $institutions =Institution::all();
        $instances =Instance::all();
        return $this->view->render($response, "program.twig", ["instituciones" => $institutions, 'instances' => $instances]);
    }

    public function institution(Request $request, Response $response)
    {
        return $this->view->render($response, "institution.twig");
    }

    public function courses(Request $request, Response $response)
    {
        $programs = Program::all();
        return $this->view->render($response, "courses.twig", ['programs' => $programs, 'instances' => $instances, 'institutions' => $institutions]);
    }

    public function search(Request $request, Response $response)
    {
        return $this->view->render($response, "search.twig");
    }

    public function searchStudent(Request $request, Response $response)
    {
        return $this->view->render($response, "search_student.twig");
    }

    public function searchCourse(Request $request, Response $response)
    {
        return $this->view->render($response, "search_course.twig");
    }

    public function searchProgram(Request $request, Response $response)
    {
        return $this->view->render($response, "search_program.twig");
    }

    public function searchStudentsForCourse(Request $request, Response $response)
    {

        $router = $request->getAttribute('route');
        $course = Register::where('curso', $router->getArguments()['id'])->get();
        return $this->view->render($response, 'result_student_for_course.twig', ["course"=>$course]);
    }

    public function searhDataForStudent(Request $request, Response $response)
    {

        $router = $request->getAttribute('route');
        $student = Student::find($router->getArguments()['id']);
        $student_data = Register::where('usuario', $student->usuario)->get();
        return $this->view->render($response, 'result_data_for_student.twig', ["student_data"=>$student_data]);
    }

    public function searchCoursesForPogram(Request $request, Response $response)
    {

        $router = $request->getAttribute('route');
        if(! is_numeric($router->getArguments()['id'])) {
            $program = Program::where('nombre','=', $router->getArguments()['id'])->first();
        } else {
            $program = Program::where('codigo', $router->getArguments()['id'])->first();
        }
        $curses = Course::where('programa', $program->codigo)->get();

        return $this->view->render($response, 'result_curses_for_program.twig', ["curses"=> $curses]);
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