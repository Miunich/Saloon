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
                    
                    //Crear el usuario
                    $resultado = $usuario->guardar();
                    
                    
                    // debuguear($usuario);
                    if($resultado){
                        //Enviar el Email
                        $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                        
                        $email->enviarConfirmacion();
                        header('Location: /mensaje');
                    } else {
                        $alertas = Usuario::getAlertas();
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
        
        $alertas = [];
        $token = s($_GET['token']);
        // debuguear($token);

        $usuario = Usuario::where('token', $token);
        
        if(!$usuario){
            //Mostrar mensaje de error
            Usuario::setAlerta('error', 'Token no válido');
        }else{
            //Modificar a usuario confirmado
            debuguear($usuario);

            $usuario->confirmado = 1;
            
            // $usuario->token = '';
            // $usuario->guardar();
        }

        $router->render('auth/confirmar-cuenta',[
            'alertas' => $alertas,
            'usuario' => $usuario
            ]);
    }

    public static function mensaje(Router $router)
    {
        $router->render('auth/mensaje');
    } 
}

// public static function confirmar(Router $router)
//     {
//         $alertas = [];
//         $token = s($_GET['token']);
//         // debuguear($token);

//         $usuario = Usuario::where('token', $token);
//         // debuguear($usuario);
//         $router->render('auth/confirmar-cuenta',[
//             'alertas' => $alertas,
//             'usuario' => $usuario
//             ]);
//     }