<?php

namespace MVC;

class Router
{
    public array $getRoutes = [];
    public array $postRoutes = [];

    public function get($url, $fn)
    {
        $this->getRoutes[$url] = $fn;
    }

    public function post($url, $fn)
    {
        $this->postRoutes[$url] = $fn;
    }

    // public function dispatch()
    // {
    //     $method = $_SERVER['REQUEST_METHOD']; // Detecta el método (GET o POST)
    //     $url = rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'); // Obtén la URL normalizada
    //     $url = str_replace('/index.php', '', $url); // Asegúrate de quitar "index.php" si está presente

    //     var_dump($method, $url, $this->getRoutes, $this->postRoutes); // Depuración

    //     // Verifica si la URL está registrada en las rutas GET
    //     if ($method === 'GET' && isset($this->getRoutes[$url])) {
    //         call_user_func($this->getRoutes[$url]); // Llama al controlador asociado
    //         return; // Detén la ejecución para evitar problemas
    //     }

    //     // Verifica si la URL está registrada en las rutas POST
    //     if ($method === 'POST' && isset($this->postRoutes[$url])) {
    //         call_user_func($this->postRoutes[$url]); // Llama al controlador asociado
    //         return; // Detén la ejecución para evitar problemas
    //     }

    //     // Si ninguna ruta coincide, muestra un error 404
    //     // http_response_code(404);
    //     // echo "Página no encontrada";
    // }


    public function comprobarRutas()
    {

        // Proteger Rutas...
        session_start();

        // Arreglo de rutas protegidas...
        // $rutas_protegidas = ['/admin', '/propiedades/crear', '/propiedades/actualizar', '/propiedades/eliminar', '/vendedores/crear', '/vendedores/actualizar', '/vendedores/eliminar'];

        // $auth = $_SESSION['login'] ?? null;
        //Cambios(original)
        // $currentUrl = $_SERVER['PATH_INFO'] ?? '/';
        // $method = $_SERVER['REQUEST_METHOD'];

        // if ($method === 'GET') {
        //     $fn = $this->getRoutes[$currentUrl] ?? null;
        // } else {
        //     $fn = $this->postRoutes[$currentUrl] ?? null;
        // }


        // if ($fn) {
        //     // Call user fn va a llamar una función cuando no sabemos cual sera
        //     call_user_func($fn, $this); // This es para pasar argumentos
        // } else {
        //     echo "Página No Encontrada o Ruta no válida";
        // }
        //Cambios(nuevo)
        $method = $_SERVER['REQUEST_METHOD']; // Detecta el método (GET o POST)
        $url = rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), ''); // Obtén la URL normalizada
        $url = str_replace('/index.php', '', $url); // Asegúrate de quitar "index.php" si está presente

        // Depuración para verificar datos
        // var_dump($method, $url, $this->getRoutes, $this->postRoutes);

        // Verifica si la URL está registrada en las rutas GET
        if ($method === 'GET' && isset($this->getRoutes[$url])) {
            $callback = $this->getRoutes[$url];
            call_user_func($callback, $this); // Pasa $this (instancia de Router) como argumento
            return;
        }

        // Verifica si la URL está registrada en las rutas POST
        if ($method === 'POST' && isset($this->postRoutes[$url])) {
            $callback = $this->postRoutes[$url];
            call_user_func($callback, $this); // Pasa $this (instancia de Router) como argumento
            return;
        }

        // Si la URL no está registrada en las rutas, redirigir a index
        header('Location: /');
        exit;
    }

    public function render($view, $datos = [])
    {

        // Leer lo que le pasamos  a la vista
        foreach ($datos as $key => $value) {
            $$key = $value;  // Doble signo de dolar significa: variable variable, básicamente nuestra variable sigue siendo la original, pero al asignarla a otra no la reescribe, mantiene su valor, de esta forma el nombre de la variable se asigna dinamicamente
        }

        ob_start(); // Almacenamiento en memoria durante un momento...

        // entonces incluimos la vista en el layout
        include_once __DIR__ . "/views/$view.php";
        $contenido = ob_get_clean(); // Limpia el Buffer
        include_once __DIR__ . '/views/layout.php';
    }
}
