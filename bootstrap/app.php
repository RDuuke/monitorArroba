<?php
require_once dirname(__DIR__) . DS . "vendor" . DS ."autoload.php";

use App\Tools\Tools;
use Slim\App;
use Illuminate\Database\Capsule\Manager;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;
use App\Controllers\AppController;
use App\Controllers\AuthController;
use App\Controllers\StudentController;
use App\Controllers\RegisterController;
use App\Controllers\UserController;
use App\Controllers\InstanceController;
use App\Controllers\ProgramController;
use App\Controllers\InstitutionController;
use App\Controllers\CourseController;
use App\Auth\Auth;
use Slim\Flash\Messages;
/*
$app = new App([
    'settings' => [
        'determineRouteBeforeAppMiddeware' => false,
        'displayErrorDetails' => true,
    'db' => [
            'driver' => 'mysql',
            'host' => 'localhost',
            'database' => 'arroba_monitor',
            'username' => 'root',
            'password' => '',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ]
    ]
]);
*/
$app = new App([
    'settings' => [
        'determineRouteBeforeAppMiddeware' => false,
        'displayErrorDetails' => true,
        'db' => [
            'driver' => 'mysql',
            'host' => '195.190.82.247',
            'database' => 'gestion_arroba',
            'username' => 'arrobamedellin',
            'password' => 'vex3SP83xLeGQFNZ',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
            'options' => [\PDO::ATTR_EMULATE_PREPARES => true],
        ],
        'db_moodle' => [
            'driver' => 'mysql',
            'host' => '195.190.82.247',
            'database' => 'zadmin_mdlmainsiteproduccion',
            'username' => 'arrobamedellin',
            'password' => 'vex3SP83xLeGQFNZ',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
            'options' => [\PDO::ATTR_EMULATE_PREPARES => true],
        ]
    ]
]);

$container = $app->getContainer();
$capsule = new Manager;
$capsule->addConnection($container['settings']['db']);
$capsule->addConnection($container['settings']['db_moodle'], "db_moodle");
$capsule->setAsGlobal();
$capsule->bootEloquent();
$container['db'] = function ($container) use ($capsule) {
    return $capsule;
};
$container['flash'] = function ($container) {
    return new Messages;
};
$container['auth'] = function ($container) {
    return new Auth($container);
};
$container['tmp'] = function($container) {
    return dirname(__DIR__) . DS . "resource" . DS . "tmp" . DS;
};
$container['files'] = function($container) {
    return dirname(__DIR__) . DS . "resource" . DS . "files" . DS;
};
$container['view'] = function ($container) {
    $view = new Twig(__DIR__ . "/../views",[
        'cache' => false
    ]);
    $view->addExtension(new TwigExtension(
        $container->router,
        $container->request->getUri()
    ));
    $view->getEnvironment()->addGlobal('modulo_plataforma', Tools::codigoUsuarioPlataforma);
    $view->getEnvironment()->addGlobal('modulo_campus', Tools::codigoUsuarioCampus);
    $view->getEnvironment()->addGlobal('modulo_instancias', Tools::codigoInstancias);
    $view->getEnvironment()->addGlobal('modulo_instituciones', Tools::codigoInstituciones);
    $view->getEnvironment()->addGlobal('modulo_programas', Tools::codigoProgramas);
    $view->getEnvironment()->addGlobal('modulo_cursos', Tools::codigoCursos);
    $view->getEnvironment()->addGlobal('modulo_matriculas', Tools::codigoMatriculas);
    $view->getEnvironment()->addGlobal('modulo_busqueda', Tools::codigoBusqueda);

    $view->getEnvironment()->addGlobal('auth', [
        'check' => $container->auth->check(),
        'user' => $container->auth->user(),
    ]);

    $view->getEnvironment()->addGlobal('base_url', (isset($_SERVER['HTTPS']) ? "https" : "http") . "://" . $_SERVER['HTTP_HOST']. "");

    $view->getEnvironment()->addGlobal('flash', $container->flash);

    $function = new Twig_SimpleFunction('getPermission', function ($module, $user) {
        $permission = \App\Models\Permission::where("modulo_id", $module)->where("user_id", $user)->first();
        if ($permission->permiso != null) {
            return $permission->permiso;
        }
        return 0;
    });
    $view->getEnvironment()->addFunction($function);

    $function = new Twig_SimpleFunction('getInstance', function ($codigo) {
        $i = substr($codigo,0, 1);
        $nombre = \App\Models\Instance::where("codigo", $i)->first();
        return $nombre->nombre;
    });
    $view->getEnvironment()->addFunction($function);

    $function = new Twig_SimpleFunction('getInstitution', function ($codigo) {
        $i = substr($codigo,1, 2);
        $nombre = \App\Models\Institution::where("codigo", $i)->first();
        return $nombre->nombre;
    });
    $view->getEnvironment()->addFunction($function);
    return $view;
};
$container['notFoundHandler'] = function ($container) {
    return function ($request, $response) use ($container) {
        return $container['view']
            ->render($container['response'], "errors/404.twig");
    };
};
$container['phpErrorHandler'] = function ($container) {
    return function ($request, $response) use ($container) {
        return $container['view']
            ->render($container['response'], "errors/500.twig");
    };
};

$container['AppController'] = function ($container)
{
    return new AppController($container);
};

$container['AuthController'] = function ($container)
{
    return new AuthController($container);
};

$container['StudentController'] = function($container)
{
    return new StudentController($container);
};

$container['RegisterController'] = function($container)
{
    return new RegisterController($container);
};

$container['UserController'] = function($container)
{
    return new UserController($container);
};

$container['InstanceController'] = function($container)
{
    return new InstanceController($container);
};

$container['ProgramController'] = function($container)
{
    return new ProgramController($container);
};

$container['InstitutionController'] = function($container)
{
    return new InstitutionController($container);
};

$container['CourseController'] = function($container)
{
    return new CourseController($container);
};

require_once dirname(__DIR__) . "/src/routes.php";