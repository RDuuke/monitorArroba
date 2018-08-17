<?php
namespace App\Controllers;

use App\Models\FirstSingIn;
use App\Models\User;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Models\Student;
use App\Models\Institution;
use App\Models\Rol;
use App\Models\Instance;
use App\Models\Program;
use App\Models\Course;
use App\Models\Register;
use App\Models\RegisterArchive;
use App\Models\Module;
use Slim\Http\Stream;

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
        if ($this->auth->user()->id_institucion != 05) {
            $modules = Module::public();
        } else {
            $modules = Module::all();
        }
        return $this->view->render($response, "user.twig", ['roles' => $rols, "instituciones" => $institutions, "modules" => $modules]);
    }

    public function firstIn(Request $request, Response $response)
    {
        return $this->view->render($response, "change_password.twig");
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

    public function changePassword(Request $request, Response $response)
    {
        if ($request->getParam('password_confirmacion') == $request->getParam('password')) {
            $user = User::find($request->getParam('id'));
            $user->clave = password_hash($request->getParam("password"), PASSWORD_DEFAULT);
            $user->save();
            $first = FirstSingIn::find($user->usuario);
            $first->singin = 1;
            $first->save();
            return $response->withRedirect($this->router->pathFor('signout'));
        }
        return $response->withRedirect($this->router->pathFor('firstsingin'));
    }
    public function searchStudentsForCourse(Request $request, Response $response)
    {

        $router = $request->getAttribute('route');
        $course = Course::where('codigo', $router->getArguments()['id'])->first();
        $students = Register::where('curso', $router->getArguments()['id'])->get();
        return $this->view->render($response, 'result_student_for_course.twig', ["students"=>$students, "course" => $course]);
    }

    public function searhDataForStudent(Request $request, Response $response)
    {
        $router = $request->getAttribute('route');
        $student = Student::find($router->getArguments()['id']);
        $student_data = Register::where('usuario', $student->usuario)->get();
        if (! $request->isXhr()) {
            // Do something
            return $this->view->render($response, 'result_data_for_student.twig', ["student_data"=>$student_data, "student" => $student]);
        }
        return $this->view->render($response, '_partials/register_student.twig', ["student_data"=>$student_data, "total" => $student_data->count()]);
    }

    public function searchCoursesForPogram(Request $request, Response $response)
    {

        $router = $request->getAttribute('route');
        if(! is_numeric($router->getArguments()['id'])) {
            $program = Program::where('nombre','=', $router->getArguments()['id'])->first();
        } else {
            $program = Program::where('codigo', $router->getArguments()['id'])->first();
        }
        $curses = Course::where('id_programa', $program->codigo)->get();

        return $this->view->render($response, 'result_curses_for_program.twig', ["curses"=> $curses, "program" => $program]);
    }

    public function registerArchive(Request $request,Response $response)
    {
        $router = $request->getAttribute('route');
        $register = Register::find($router->getArguments()['id']);
        try {
            if(RegisterArchive::updateOrCreate(['usuario' => $register->usuario, 'curso' => $register->curso], $register->toArray())) {
                if ($register->delete()) {
                    $newResponse = $response->withHeader('Content-type', 'application/json');
                    return $newResponse->withJson(['message' => 1], 200);
                }

            }
        } catch(\Exception $e) {
            return $response->withStatus(500)->write('0');
        }
    }
    function upload_students(Request $request, Response $response)
    {
        return $this->view->render($response, "uploadusers.twig");
    }

    function upload_registers(Request $request, Response $response)
    {
        return $this->view->render($response, "uploadregister.twig");
    }
    function upload_courses(Request $request, Response $response)
    {
        return $this->view->render($response, "uploadcourse.twig");
    }

    function downloadArchive(Request $request, Response $response)
    {
        return $this->download('Anexo2.xlsx', $response);

    }

    function downloadStudent(Request $request, Response $response)
    {
        return $this->download('Anexo1.xlsx', $response);
    }

    function downloadCourse(Request $request, Response $response)
    {
        return $this->download('Anexo3.xlsx', $response);
    }

    protected function download(String $filename, Response $response) : Response
    {
        $fh = fopen($this->files . $filename, "rb");
        $stream = new Stream($fh);

        return $response->withHeader('Content-Type', 'application/octet-stream')
            ->withHeader('Content-Type', 'application/download')
            ->withHeader('Content-Description', 'File Transfer')
            ->withHeader('Content-Transfer-Encoding', 'binary')
            ->withHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->withHeader('Expires', '0')
            ->withHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0')
            ->withHeader('Pragma', 'public')
            ->withBody($stream);
    }


}