<?php

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController
{
    public static function login(Router $router)
    {
        $alertas = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST);
            $alertas = $auth->validarLogin();
            if (empty($alertas)) {
                //comprobar que usuario existe
                $usuario = Usuario::where('email', $auth->email);

                if ($usuario) {
                    //verificar el password
                    if ($usuario->comprobarpasswordyverificado($auth->password)) {
                        if (!isset($_SESSION)) {
                            session_start();
                        };
                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre . " " . $usuario->apellido;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        if ($usuario->admin === "1") {
                            $_SESSION['admin'] = $usuario->admin ?? null;

                            header('Location: /admin');
                        } else {
                            header('Location: /cita');
                        }
                    };
                } else {
                    Usuario::setAlerta('error', 'Usuario no encontrado');
                }
            }
        }
        $alertas = Usuario::getAlertas();
        $router->render('auth/login', [
            'alertas' => $alertas
        ]);
    }

    public static function logout()
    {
        session_start();

        $_SESSION = [];
        header('location: /');
    }

    public static function olvide(Router $router)
    {
        $alertas = [];


        if ($_SERVER['REQUEST_METHOD'] ===  'POST') {
            $auth = new Usuario($_POST);

            $alertas = $auth->validaremail();

            if (empty($alertas)) {
                $usuario = Usuario::where('email', $auth->email);

                if ($usuario && $usuario->confirmado === "1") {
                    //generar un token
                    $usuario->crearToken();
                    $usuario->guardar();
                    Usuario::setAlerta('exito', 'Se ha enviado un correo para cambio de contraseña');
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->apellido, $usuario->token);
                    $email->olvideContraseña();
                } else {
                    Usuario::setAlerta('error', 'El usuario no existe o no esta confirmado');
                }
            }
        }
        $alertas = Usuario::getAlertas();
        $router->render('auth/olvide', [
            'alertas' => $alertas
        ]);
    }

    public static function recuperar(Router $router)
    {

        $alertas = [];
        $error = false;
        $token = s($_GET['token']);

        //Buscar usuario por su token

        $usuario = Usuario::where('token', $token);

        if (empty($usuario)) {
            Usuario::setAlerta('error', 'Token no valido');
            $error = true;
        } 

        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            $password = new Usuario($_POST);
            $alertas = $password->verificaspassword();

            if(empty($alertas)){
                $usuario->password = null;
                $usuario->password = $password->password;
                $usuario->hashpasssword();
                $usuario->token = '';
                $resultado = $usuario->guardar();
                if($resultado){
                    header('Location: /');
                }
                
            }
        }

        $alertas = Usuario::getAlertas();
        $router->render('auth/recuperar', [
            'alertas' => $alertas,
            'error' => $error
        ]);
    }

    public static function crear(Router $router)
    {

        $usuario = new Usuario;
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $usuario->sincronizar($_POST);
            $alertas = $usuario->ValidarNuevaCuenta();

            //revisar si alertas esta vacio
            if (empty($alertas)) {
                //verificar que el usuario no este registrado
                $resultado = $usuario->existeUsuario();

                if ($resultado->num_rows) {
                    $alertas = Usuario::getAlertas();
                } else {
                    //hashear contraseña
                    $usuario->hashpasssword();
                    //generar un token unico
                    $usuario->crearToken();
                    //enviar un email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->apellido, $usuario->token);

                    $email->enviarConfirmacion();

                    //crear el usuario

                    $resultado = $usuario->guardar();

                    if ($resultado) {
                        header('Location: /mensaje-cuenta');
                    }
                }
            }
        }
        $router->render('auth/crear-cuenta', [
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function confirmar(Router $router)
    {
        $alertas = [];

        $token = s($_GET['token']);

        $usuario = Usuario::where('token', $token);



        if (empty($usuario)) {

            Usuario::setAlerta('error', 'Token no valido');
        } else {

            $usuario->confirmado = '1';
            $usuario->token = '';
            $usuario->guardar();
            Usuario::setAlerta('exito', 'Correo confirmada correctamente');
        }

        $alertas = Usuario::getAlertas();
        $router->render('auth/confirmar', [
            'alertas' => $alertas
        ]);
    }

    public static function mensaje(Router $router)
    {
        $router->render('auth/mensaje', []);
    }
}
