<?php

namespace Model;

class Cita extends ActiveRecord{
    protected static $tabla = 'citas';
    protected static $columnasDB = ['id','fecha', 'hora', 'usuarioid'];

    public $id;
    public $fecha;
    public $hora;
}