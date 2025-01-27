<?php

namespace Controllers;

use Classes\Email;
use MVC\Router;
use Model\Usuario;

class LoginController
{
    public static function login(Router $router)
    {
        $router->render('auth/login');
    }
    public static function logout()
    {
        echo 'Cerrando Sesión ';
    }
    public static function recuperar()
    {
        echo 'Recuperar contraseña';
    }
    public static function olvide(Router $router)
    {
        $router->render('auth/olvide-password');
    }
    public static function crear(Router $router)
    {
        // Crear una nueva instancia de Usuario
        $usuario = new Usuario();
        
        // Alertas vacías
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario->sincronizar($_POST);
            // Validar
            $alertas = $usuario->validarNuevaCuenta();

            //Revisar alertas esté vacío
            if (empty($alertas)) {
                // Verificar no está verificado
                $resultado = $usuario->existeUsuario();
                if ($resultado->num_rows) {
                    $alertas = Usuario::getAlertas();
                }else{
                    //Hashear el password
                    $usuario->hashPassword();

                    //Generar un token
                    $usuario->crearToken();

                    //Enviar el Email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    
                    $email->enviarConfirmacion();

                    //Crear el usuario
                    $resultado = $usuario->crear();
                    // debuguear($usuario);
                    if($resultado){
                        echo 'Usuario Creado';
                    }
                }
            }
            
        }
        $router->render('auth/crear-cuenta', [
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    // test de confirmar cuenta
    public static function confirmar(Router $router)
    {
        // $router->render('auth/crear-cuenta');
        $alertas = [];
        $token = $_GET['token'] ?? ''; // Obtén el token de la URL

        if (!$token) {
            Usuario::setAlerta('error', 'Token no válido');
        } else {
            // Buscar el usuario por su token
            $usuario = Usuario::where('token', $token);

            if (empty($usuario)) {
                Usuario::setAlerta('error', 'Token no válido o el usuario ya está confirmado');
            } else {
                // Confirmar la cuenta
                $usuario->confirmado = 1; // O el valor correspondiente en la base de datos
                $usuario->token = null; // Elimina el token después de confirmar

                // Guardar los cambios
                $usuario->guardar();
                Usuario::setAlerta('exito', 'Cuenta confirmada correctamente');
            }
        }

        // Obtener las alertas
        $alertas = Usuario::getAlertas();

        // Renderizar la vista
        // $router->render('auth/confirmar-cuenta', [
        //     'alertas' => $alertas
        // ]);
        $router->render('auth/confirmar-cuenta', [
            'alertas' => $alertas
        ]);
    }
}