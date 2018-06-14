<?php
require_once dirname(__DIR__) . DS . "vendor" . DS ."autoload.php";

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
$container = $app->getContainer();
$capsule = new Manager;
$capsule->addConnection($container['settings']['db']);
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
$container['view'] = function ($container) {
    $view = new Twig(__DIR__ . "/../views",[
        'cache' => false
    ]);
    $view->addExtension(new TwigExtension(
        $container->router,
        $container->request->getUri()
    ));

    $view->getEnvironment()->addGlobal('auth', [
        'check' => $container->auth->check(),
        'user' => $container->auth->user()
    ]);

    $view->getEnvironment()->addGlobal('base_url', (isset($_SERVER['HTTPS']) ? "https" : "http") . "://" . $_SERVER['HTTP_HOST']. "");

    $view->getEnvironment()->addGlobal('flash', $container->flash);

    return $view;
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