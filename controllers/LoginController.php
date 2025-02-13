<?php

namespace Controllers;

use Classes\Email;
use MVC\Router;
use Model\Usuario;

class LoginController
{
    public static function login(Router $router)
    {
        $alertas = [];


        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $auth = new Usuario($_POST);

            $alertas = $auth->validarLogin();
            
            if(empty($alertas)){
                //Comprobar que existe el usuario
                $usuario = Usuario::where('email', $auth->email);

                if($usuario){
                    //Verificar el password
                    if($usuario->comprobarPasswordAndVerificado($auth->password)){
                        session_start();

                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre . " " . $usuario->apellido;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        //Redireccionamiento
                        if($usuario->admin === "1"){
                            $_SESSION['admin'] = $usuario->admin ?? null;
                            header('Location: /admin');
                        }else{
                            header('Location: /cita');
                        }

                        
                    }
                }else{
                    Usuario::setAlerta('error', 'El usuario no existe');
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

        header('Location: /');
    }
    public static function recuperar(Router $router)
    {
        $alertas = [];
        $error = false;

        $token = s($_GET['token']);
        
        //Buscar usuario por token
        $usuario = Usuario::where('token', $token);

        if(empty($usuario)){
            Usuario::setAlerta('error', 'Token no válido');
            $error = true;
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            //Leer el nuevo password y guardarlo en la base de datos
            $password = new Usuario($_POST);
            $alertas = $password->validarPassword();

            if(empty($alertas)){
                $usuario->password = $password->password;
                $usuario->hashPassword();
                $usuario->token = null;

                $resultado = $usuario->actualizar($usuario->id);

                if ($resultado) {
                    header('Location: /');
                }


                
            }

        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/recuperar-password', [
            'alertas' => $alertas,
            'error' => $error
        ]);
    }
    public static function olvide(Router $router)
    {
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $auth = new Usuario($_POST);
            $alertas = $auth->validarEmail();
            
            if(empty($alertas)){
                $usuario = Usuario::where('email', $auth->email);

                if($usuario && $usuario->confirmado === "1"){

                    //Generar un token de un solo uso
                    $usuario->crearToken();
                    $usuario->actualizar($usuario->id);

                    // Enviar el email

                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarInstrucciones();

                    //Alerta de exito
                    Usuario::setAlerta('exito', 'Revisa tu email para recuperar tu contraseña');
                    
                } else{
                    Usuario::setAlerta('error', 'El usuario no existe o no está confirmado');
                    
                }
            }
        }
        $alertas = Usuario::getAlertas();

        $router->render('auth/olvide-password', [
            'alertas' => $alertas
        ]);
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

            $usuario->confirmado = 1;
            $usuario->token = null;
            // debuguear($usuario);
            $usuario->actualizar($usuario->id);
            Usuario::setAlerta('exito', 'Cuenta confirmada Correctamente');
        }

        //Obtener alertas
        $alertas = Usuario::getAlertas();
        
        //Renderizar la vista
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
