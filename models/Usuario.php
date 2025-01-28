<?php

namespace Model;

class Usuario extends ActiveRecord{
    //Base de datos
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'nombre', 'apellido', 'email', 'password', 'telefono', 'admin', 'confirmado', 'token'];

    //Atributos
    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $password;
    public $telefono;
    public $admin;
    public $confirmado;
    public $token;

    //Definir constructor
    public function __construct($args = []){
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->apellido = $args['apellido'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->admin = $args['admin'] ?? 0;
        $this->confirmado = $args['confirmado'] ?? 0;
        $this->token = $args['token'] ?? '';
    }

    // Mensaje de validación para la creción de una cuenta
    public function validarNuevaCuenta(){
        if(!$this->nombre){
            self::$alertas['error'][] = "Debes añadir un nombre";
        }
        if(!$this->apellido){
            self::$alertas['error'][] = "Debes añadir un apellido";
        }
        if(!$this->email){
            self::$alertas['error'][] = "Debes añadir un email";
        }
        if(!$this->password){
            self::$alertas['error'][] = "Debes añadir un password";
        }
        if(strlen($this->password<6)){
            self::$alertas['error'][] = "El password debe tener al menos 6 caracteres";

        }
        if(!$this->telefono){
            self::$alertas['error'][] = "Debes añadir un telefono";
        }
        return self::$alertas;
    }

    // Verificar si un usuario ya existe
    public  function existeUsuario(){
        $query = "SELECT * FROM " . self::$tabla . " WHERE email = '" . $this->email ."' LIMIT 1";
        $resultado = self::$db->query($query);

        if($resultado->num_rows){
            self::$alertas['error'][] = "El usuario ya está registrado";
        }

        return $resultado;
    }
    //Hashear el password
    public function hashPassword(){
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    public function crearToken(){
        $this->token = md5(uniqid(rand(), true));
    }

    //uso del where
    // public static function where($campo, $valor)
    // {
    //     // Crear la consulta SQL para buscar por el campo y el valor
    //     $query = "SELECT * FROM " . static::$tabla . " WHERE $campo = ? LIMIT 1";
    //     $stmt = self::$db->prepare($query); // Preparar la consulta
    //     $stmt->bind_param('s', $valor); // Asignar el parámetro (valor)
    //     $stmt->execute(); // Ejecutar la consulta
    //     $resultado = $stmt->get_result(); // Obtener los resultados

    //     if ($resultado->num_rows > 0) {
    //         $datos = $resultado->fetch_assoc(); // Obtener los datos como un array asociativo
    //         return new self($datos); // Crear una nueva instancia del modelo con los datos obtenidos
    //     }

    //     return null; // Retorna null si no encuentra ningún resultado
    // }
    
}