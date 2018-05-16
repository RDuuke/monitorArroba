<?php
namespace App\Auth;
use App\Models\User;
use App\Tools\Tools;

class Auth
{
    public function attempt($email, $password)
    {
        $user = User::where('usuario', '=', $email)->first();
        if (!$user) {
            return false;
        }
        if (password_verify($password, $user->clave)) {
            $_SESSION['user'] = $user->id;
            return true;
        }
        return false;
    }
    public function check()
    {
        return isset($_SESSION['user']);
    }
    public function user()
    {
        return User::find($_SESSION['user']);
    }
    public function logout()
    {
        unset($_SESSION['user']);
    }
    public function isAdmin()
    {
        if ($this->user()->id_rol == 1) {
            return 1;
        }
        return 0;
    }

    public function getInstitution()
    {
        if ( $this->user()->institution->codigo == Tools::codigoMedellin()) {
              return "%";
        }
        return $this->user()->institution->nombre;
    }

    public function getCodigoInstitution()
    {
        return $this->user()->id_institucion;
    }
}