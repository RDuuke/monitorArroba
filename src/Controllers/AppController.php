<?php
namespace App\Controllers;

use App\Models\FirstSingIn;
use App\Models\User;
use App\Tools\Tools;
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
        if (!$this->accessModuleRead($response,Tools::codigoUsuarioCampus)) {
            return $response->withRedirect($this->router->pathFor('admin.home'));
        }
        $institutions =Institution::all();
        return $this->view->render($response, "student.twig", ['module_name' => Tools::$Modules[1], "menu_active" => Tools::$MenuActive[0], "instituciones" => $institutions]);
    }

    public function users(Request $request, Response $response)
    {
        if (!$this->accessModuleRead($response,Tools::codigoUsuarioPlataforma)) {
            return $response->withRedirect($this->router->pathFor('admin.home'));
        }

        $rols = Rol::all();
        $institutions =Institution::all();
        if ($this->auth->user()->id_institucion != 05) {
            $modules = Module::public();
        } else {
            $modules = Module::all();
        }
        return $this->view->render($response, "user.twig", ['roles' => $rols, "instituciones" => $institutions, "modules" => $modules, 'module_name' => Tools::$Modules[0], "menu_active" => Tools::$MenuActive[0]]);
    }

    public function firstIn(Request $request, Response $response)
    {
        return $this->view->render($response, "change_password.twig");
    }
    public function registers(Request $request, Response $response)
    {
        if (!$this->accessModuleRead($response,Tools::codigoMatriculas)) {
            return $response->withRedirect($this->router->pathFor('admin.home'));
        }
        return $this->view->render($response, "register.twig", ['module_name' => Tools::$Modules[2], "menu_active" => Tools::$MenuActive[0]]);
    }

    public function instance(Request $request, Response $response)
    {
        if (!$this->accessModuleRead($response,Tools::codigoInstancias)) {
            return $response->withRedirect($this->router->pathFor('admin.home'));
        }
        return $this->view->render($response, "instance.twig", ['module_name' => Tools::$Modules[3], "menu_active" => Tools::$MenuActive[0]]);
    }

    public function program(Request $request, Response $response)
    {
        if (!$this->accessModuleRead($response,Tools::codigoProgramas)) {
            return $response->withRedirect($this->router->pathFor('admin.home'));
        }
        $institutions =Institution::all();
        $instances =Instance::all();
        return $this->view->render($response, "program.twig", ["instituciones" => $institutions, 'instances' => $instances, 'module_name' => Tools::$Modules[5], "menu_active" => Tools::$MenuActive[0]]);
    }

    public function institution(Request $request, Response $response)
    {
        if (!$this->accessModuleRead($response,Tools::codigoInstituciones)) {
            return $response->withRedirect($this->router->pathFor('admin.home'));
        }
        return $this->view->render($response, "institution.twig", ['module_name' => Tools::$Modules[4], "menu_active" => Tools::$MenuActive[0]]);
    }

    public function courses(Request $request, Response $response)
    {
        if (!$this->accessModuleRead($response,Tools::codigoCursos)) {
            return $response->withRedirect($this->router->pathFor('admin.home'));
        }

        $programs = Program::all();
        return $this->view->render($response, "courses.twig", ['programs' => $programs,'module_name' => Tools::$Modules[7], "menu_active" => Tools::$MenuActive[0]]);
    }

    public function search(Request $request, Response $response)
    {
        if (!$this->accessModuleRead($response,Tools::codigoBusqueda)) {
            return $response->withRedirect($this->router->pathFor('admin.home'));
        }
        return $this->view->render($response, "search.twig", ['module_name' => Tools::$Modules[6], "menu_active" => Tools::$MenuActive[0]]);
    }

    public function searchStudent(Request $request, Response $response)
    {
        if (!$this->accessModuleRead($response,Tools::codigoBusqueda)) {
            return $response->withRedirect($this->router->pathFor('admin.home'));
        }
        return $this->view->render($response, "search_student.twig", ["module_name" => ["Búsqueda#admin.search", "Búsqueda usuarios"], "menu_active" => Tools::$MenuActive[1]]);
    }

    public function searchCourse(Request $request, Response $response)
    {
        if (!$this->accessModuleRead($response,Tools::codigoBusqueda)) {
            return $response->withRedirect($this->router->pathFor('admin.home'));
        }
        return $this->view->render($response, "search_course.twig", ["module_name" => ["Búsqueda#admin.search", "Búsqueda Curso"], "menu_active" => Tools::$MenuActive[1]]);
    }

    public function searchProgram(Request $request, Response $response)
    {
        if (!$this->accessModuleRead($response,Tools::codigoBusqueda)) {
            return $response->withRedirect($this->router->pathFor('admin.home'));
        }

        return $this->view->render($response, "search_program.twig", ["module_name" => ["Búsqueda#admin.search", "Búsqueda Programa"], "menu_active" => Tools::$MenuActive[1]]);
    }

    public function userAdd(Request $request, Response $response)
    {
        if (!$this->accessModuleReadAndWrite($response,Tools::codigoBusqueda)) {
            return $response->withRedirect($this->router->pathFor('admin.home'));
        }
        $institutions =Institution::all();
        return $this->view->render($response, "content_form_add.twig", ["form" => "user.twig", "instituciones" => $institutions, "module_name" => ["Usuarios @Monitor#admin.users","Agregar Usuario @Monitor"], "menu_active" => Tools::$MenuActive[0]]);
    }
    public function studentAdd(Request $request, Response $response)
    {
        if (!$this->accessModuleReadAndWrite($response,Tools::codigoUsuarioCampus)) {
            return $response->withRedirect($this->router->pathFor('admin.home'));
        }
        $institutions =Institution::all();
        return $this->view->render($response, "content_form_add.twig", ["form" => "student.twig", "instituciones" => $institutions, "module_name" => ["Usuarios Campus#admin.students", "Agregar Usuario Campus"], "menu_active" => Tools::$MenuActive[0]]);
    }

    public function programAdd(Request $request, Response $response)
    {
        if (!$this->accessModuleReadAndWrite($response,Tools::codigoProgramas)) {
            return $response->withRedirect($this->router->pathFor('admin.home'));
        }
        $institutions =Institution::all();
        $instances =Instance::all();
        return $this->view->render($response, "content_form_add.twig", ["form" => "program.twig", "instances" => $instances, "instituciones" => $institutions, "module_name" => ["Programas#admin.program", "Agregar Programas"], "menu_active" => Tools::$MenuActive[0]]);
    }

    public function courseAdd(Request $request, Response $response)
    {
        if (!$this->accessModuleReadAndWrite($response,Tools::codigoCursos)) {
            return $response->withRedirect($this->router->pathFor('admin.home'));
        }
        $programs = Program::all();
        return $this->view->render($response, "content_form_add.twig", ["form" => "course.twig", "module_name" => ["Cursos#admin.courses", "Agregar curso"], "programs" => $programs, "menu_active" => Tools::$MenuActive[0]]);
    }

    public function instanceAdd(Request $request, Response $response)
    {
        if (!$this->accessModuleReadAndWrite($response,Tools::codigoInstancias)) {
            return $response->withRedirect($this->router->pathFor('admin.home'));
        }
        return $this->renderForm("instance.twig", ["Instancias#admin.instance", "agregar instancia"], $response);
    }


    public function institutionAdd(Request $request, Response $response)
    {
        if (!$this->accessModuleReadAndWrite($response,Tools::codigoInstituciones)) {
            return $response->withRedirect($this->router->pathFor('admin.home'));
        }
        return $this->renderForm("institution.twig", ["Instituciones#admin.institution", "agregar institución"], $response);
    }

    public function registerAdd(Request $request, Response $response)
    {
        if (!$this->accessModuleReadAndWrite($response,Tools::codigoMatriculas)) {
            return $response->withRedirect($this->router->pathFor('admin.home'));
        }
        switch($this->auth->user()->id_institucion)
        {
            case "01":
                $courses = Course::pascual()->get();
                break;
            case "02":
                $courses = Course::colegio()->get();
                break;
            case "03":
                $courses = Course::itm()->get();
                break;
            case  "04":
                $courses = Course::ruta()->get();
                break;
            default :
                $courses = Course::all();
                break;
        }
        return $this->view->render($response, "content_form_add.twig", ["form" =>"register.twig", "module_name" => ["Matriculas#admin.register","agregar matricula"], "courses" => $courses, "menu_active" => Tools::$MenuActive[0]]);

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
        return $this->view->render($response, 'result_student_for_course.twig', ["students"=>$students, "course" => $course, "module_name" => ["Búsqueda#admin.search", "Usuarios Matriculados"], "menu_active" => Tools::$MenuActive[1]]);
    }

    public function searhDataForStudent(Request $request, Response $response)
    {
        if (!$this->accessModuleRead($response,Tools::codigoBusqueda)) {
            return $response->withRedirect($this->router->pathFor('admin.home'));
        }

        $router = $request->getAttribute('route');
        $student = Student::find($router->getArguments()['id']);
        $student_data = Register::where('usuario', $student->usuario)->get();
        if (! $request->isXhr()) {
            return $this->view->render($response, 'result_data_for_student.twig', ["student_data"=>$student_data, "student" => $student, "module_name" => ["Búsqueda#admin.search", "Cursos del usuarios"], "menu_active" => Tools::$MenuActive[1]]);
        }
        return $this->view->render($response, '_partials/register_student.twig', ["student_data"=>$student_data, "total" => $student_data->count()]);
    }

    public function searchCoursesForPogram(Request $request, Response $response)
    {
        if (!$this->accessModuleRead($response,Tools::codigoBusqueda)) {
            return $response->withRedirect($this->router->pathFor('admin.home'));
        }

        $router = $request->getAttribute('route');
        if(! is_numeric($router->getArguments()['id'])) {
            $program = Program::where('nombre','=', $router->getArguments()['id'])->first();
        } else {
            $program = Program::where('codigo', $router->getArguments()['id'])->first();
        }
        $curses = Course::where('id_programa', $program->codigo)->get();

        return $this->view->render($response, 'result_curses_for_program.twig', ["curses"=> $curses, "program" => $program, "module_name" => ["Búsqueda#admin.search",  "Cursos por programa"], "menu_active" => Tools::$MenuActive[1]]);
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
        if (!$this->accessModuleReadAndWrite($response,Tools::codigoUsuarioCampus)) {
            return $response->withRedirect($this->router->pathFor('admin.home'));
        }

        $institutions = Institution::all();
        return $this->view->render($response, "uploadusers.twig", ["module_name" => ["Usuarios Plataforma#admin.students", "Creación Masivamente"], "institutions" => $institutions, "menu_active" => Tools::$MenuActive[2]]);
    }

    function upload_registers(Request $request, Response $response)
    {
        if (!$this->accessModuleReadAndWrite($response,Tools::codigoMatriculas)) {
            return $response->withRedirect($this->router->pathFor('admin.home'));
        }

        return $this->view->render($response, "uploadregister.twig", ["module_name" => ["Matriculas#admin.register", "Creación Masivamente"], "menu_active" => Tools::$MenuActive[2]]);
    }
    function upload_courses(Request $request, Response $response)
    {
        if (!$this->accessModuleReadAndWrite($response,Tools::codigoCursos)) {
            return $response->withRedirect($this->router->pathFor('admin.home'));
        }
        return $this->view->render($response, "uploadcourse.twig", ["module_name" => ["Cursos#admin.courses", "Creación Masivamente"], "menu_active" => Tools::$MenuActive[2]]);
    }

    function upload_students_archive(Request $request, Response $response)
    {
        if (!$this->accessModuleReadAndWrite($response,Tools::codigoUsuarioCampus)) {
            return $response->withRedirect($this->router->pathFor('admin.home'));
        }
        return $this->view->render($response, "uploadarchive.twig", ["module_name" => ["Usuario Plataforma#admin.students", "Archivar Masivamente"], "menu_active" => Tools::$MenuActive[2]]);
    }

    function upload_register_de_enroll(Request $request, Response $response)
    {
        if (!$this->accessModuleReadAndWrite($response,Tools::codigoMatriculas)) {
            return $response->withRedirect($this->router->pathFor('admin.home'));
        }
        return $this->view->render($response, "uploaddeenroll.twig", ["module_name" => ["Matricula#admin.register", "Desmatricular Masivamente"], "menu_active" => Tools::$MenuActive[2]]);
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

    function downloadStudentArchive(Request $request, Response $response)
    {
        return $this->download('Anexo10.xlsx', $response);
    }
    function downloadRegisterArchive(Request $request, Response $response)
    {
        return $this->download('Anexo11.xlsx', $response);
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

    protected function renderForm(String $partial, $module_name, Response $response) {
        return $this->view->render($response, "content_form_add.twig", ['form' => $partial, 'module_name' => $module_name]);
    }

    protected function accessModuleRead(Response $response, Int $module)
    {

        if (! $this->auth->getPermissionForModule($module) || $this->auth->getPermissionForModule($module) == 0) {
            return false;
        }
        return true;
    }
    protected function accessModuleReadAndWrite(Response $response, Int $module)
    {

        if (! $this->auth->getPermissionForModule($module) == 3) {
            return false;
        }
        return true;
    }
}