<?php
ini_set('memory_limit', '-1');
date_default_timezone_set("America/Bogota");
ini_set('display_errors', '1');
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR .  ".." . DIRECTORY_SEPARATOR  . "vendor" . DIRECTORY_SEPARATOR . "autoload.php";

$config = require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "bootstrap" . DIRECTORY_SEPARATOR . "database.php";
$capsule = new \Illuminate\Database\Capsule\Manager();
$capsule->addConnection($config['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$today = \Carbon\Carbon::today();
$yersterday = $today->subDay()->toDateString();
$users = Illuminate\Database\Capsule\Manager::table("usuario")->whereDate("fecha", $yersterday)->get();
$register = \Illuminate\Database\Capsule\Manager::table("matricula")->whereDate("fecha", $yersterday)->get();

ob_start();
?>

    <h3>Cifras de usuarios y matrículas realizadas el día: <i><u><?= $today->format('d-m-Y') ?></u></i>.</h3>
    <table border="1" width="30%">
        <thead>
            <tr>
                <th>Módulos</th>
                <th>Cantidad</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Usuarios</td>
                <td align="right"><?= $users->count() ?></td>
            </tr>
            <tr>
                <td>Matrículas</td>
                <td align="right"><?= $register->count() ?></td>
            </tr>
        </tbody>
    </table>
    <p><small style="text-align: center">Servicio de @Monitor.</small></p>
<?php
$template = ob_get_contents();
ob_clean();
echo $template;
die;
$transport = new \Swift_SmtpTransport("smtp.gmail.com", 465, "ssl");

$transport->setUsername($config["smtp"]["usuario"])
    ->setPassword($config["smtp"]["password"])
    ->setStreamOptions(
        [
            "ssl" => [
                "allow_self_signed" => true,
                "verify_peer" => false
            ]
        ]
    );

$mailer = new \Swift_Mailer($transport);
$message = (new \Swift_Message("Cifra de usuario y matriculas"))
    ->setFrom($config["smtp"]["usuario"], "@Monitor")
    ->setTo("juuanduuke@gmail.com");

$message->setBody($template, "text/html");

$mailer->send($message);